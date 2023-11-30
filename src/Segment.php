<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

class Segment
{
    public function __construct(
        public int $x,
        public int $y,
        public int $w,
        public int $h,
        public float $intensity = 0.0,
    )
    {
    }

    public function midPoint(): Point {
        return new Point(
            $this->x + intdiv($this->w, 2),
            $this->y + intdiv($this->h, 2),
        );
    }
}
