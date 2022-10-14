<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

class Point
{
    public function __construct(
        public int $x,
        public int $y,
    )
    {
    }

    public function __toString()
    {
        return '(' . $this->x . ', ' . $this->y . ')';
    }
}
