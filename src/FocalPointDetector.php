<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

use Imagick;


class FocalPointDetector
{
    public function __construct(
        protected int $segmentSize = 20,
    )
    {
    }

    public function getPoint(Imagick $image): Point
    {
        return (new Image($image, $this->segmentSize))
            ->getSegments()
            ->getMaxIntensitySegment()
            ->midPoint();
    }

    public function debug(Imagick $image): Image
    {
        return new Image($image, $this->segmentSize);
    }
}