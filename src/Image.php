<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

use Imagick;
use ImagickDraw;
use ImagickPixel;

class Image
{
    protected SegmentCollection $segments;

    public function __construct(
        protected Imagick $image,
        protected int $segmentSize,
    )
    {
        $this
            ->greyscale()
            ->detectEdges()
            ->centraliseFocus()
            ->makeSegments();
    }

    protected function greyscale(): static
    {
        $this->image = $this->image->fxImage('intensity');

        return $this;
    }

    protected function detectEdges(): static
    {
        $this->image->gaussianBlurImage(1, 2);

//        $this->image->convolveImage([-1, -1, -1, -1, 8, -1, -1, -1, -1,]); // Laplacian:0
//        $this->image->convolveImage([0, -1, 0, -1, 4, -1, 0, -1, 0,]); // Laplacian:1

//        $this->image->cannyEdgeImage(10, 1, 0.1, 0.5); // Imagick v7 required

        $this->image->edgeImage(1);

        return $this;
    }

    /** @return Segment[] */
    protected function makeSegments(): self
    {
        $segments = [];

        for ($x = 0; $x < $this->image->getImageWidth(); ++$x) {
            for ($y = 0; $y < $this->image->getImageHeight(); ++$y) {
                $focus = $this->factor($x + $this->segmentSize / 2, $y + $this->segmentSize / 2, $this->image->getImageWidth(), $this->image->getImageHeight()) * 0.15;
                $width = min($this->image->getImageWidth() - $x, $this->segmentSize);
                $height = min($this->image->getImageHeight() - $y, $this->segmentSize);
                $pixels = $width * $height;
                $sum = 0;

                for ($sx = 0; $sx < $width; ++$sx) {
                    for ($sy = 0; $sy < $height; ++$sy) {
                        $pixelColours = $this->image
                            ->getImagePixelColor(($x + $sx), ($y + $sy))
                            ->getColor(2);

                        $intensity = array_sum($pixelColours) / (count($pixelColours) * 255);

                        $sum += $intensity;
                    }
                }

                $focus += ($sum / $pixels) * 0.85;
                $segments[] = new Segment($x, $y, $width, $height, $focus * 5);
                $y += $this->segmentSize;
            }
            $x += $this->segmentSize;
        }

        $this->segments = new SegmentCollection($segments);

        return $this;
    }

    protected function factor(int $x, int $y, int $w, int $h): float
    {
        $x = abs($x - ($w / 2)) / ($w / 2);
        $y = abs($y - ($h / 2)) / ($h / 2);
        $x /= 2;
        $y /= 2;

        return 1 - ($x + $y);
    }

    protected function centraliseFocus(): static
    {
        $this->image->setImageBackgroundColor(new ImagickPixel('#000'));
        $this->image->vignetteImage(150, 150, (int)($this->image->getImageWidth() / 10), (int)($this->image->getImageHeight() / 10));

        return $this;
    }

    public function drawGrid(): static
    {
        foreach ($this->getSegments() as $segment) {
            $draw = new ImagickDraw();

            $draw->setFillColor(new ImagickPixel('transparent'));
            $draw->setStrokeColor(new ImagickPixel('rgba(0,255,0,0.4)'));
            $draw->setStrokeWidth(1);

            $draw->rectangle(
                $segment->x,
                $segment->y,
                $segment->x + $segment->w,
                $segment->y + $segment->h,
            );

            $this->image->drawImage($draw);
        }

        return $this;
    }

    public function drawHeatMap(): static
    {
        $segmentMax = $this->getSegments()->getMaxIntensitySegment();
        $segmentMin = $this->getSegments()->getMinIntensitySegment();

        $intensityMax = $segmentMax->intensity;
        $intensityMin = $segmentMin->intensity;
        $intensityRange = ($intensityMax + 0.2) - $intensityMin;

        foreach ($this->getSegments() as $segment) {
            $normalisedIntensity = ($segment->intensity - $intensityMin) / $intensityRange;

            $draw = new ImagickDraw();

            if ($segment->x === $segmentMax->x && $segment->y === $segmentMax->y) {
                $draw->setFillColor(new ImagickPixel('rgba(0,255,0,0.5)'));
            } else {
                $draw->setFillColor(new ImagickPixel('rgba(255,0,0,' . $normalisedIntensity . ')'));
            }

            $draw->rectangle(
                $segment->x,
                $segment->y,
                $segment->x + $segment->w,
                $segment->y + $segment->h,
            );

            $this->image->drawImage($draw);
        }

        return $this;
    }

    public function getSegments(): SegmentCollection
    {
        return $this->segments;
    }

    public function getRawImage(): Imagick
    {
        return $this->image;
    }
}
