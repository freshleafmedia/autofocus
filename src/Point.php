<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

use Stringable;

class Point implements Stringable
{
    public function __construct(
        public int $x,
        public int $y,
    )
    {
    }

    public function __toString(): string
    {
        return '(' . $this->x . ', ' . $this->y . ')';
    }
}
