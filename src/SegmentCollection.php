<?php declare(strict_types=1);

namespace FreshleafMedia\Autofocus;

use Iterator;

class SegmentCollection implements Iterator
{
    /** @param Segment[] $segments */
    public function __construct(
        protected array $segments = [],
    )
    {
    }

    public function getMaxIntensitySegment(): Segment
    {
        $highestSegment = $this->segments[0];

        foreach ($this->segments as $segment) {
            if ($segment->intensity > $highestSegment->intensity) {
                $highestSegment = $segment;
            }
        }

        return $highestSegment;
    }

    public function getMinIntensitySegment(): Segment
    {
        $lowestSegment = $this->segments[0];

        foreach ($this->segments as $segment) {
            if ($segment->intensity < $lowestSegment->intensity) {
                $lowestSegment = $segment;
            }
        }

        return $lowestSegment;
    }

    public function current(): Segment
    {
        return current($this->segments);
    }

    public function next(): void
    {
        next($this->segments);
    }

    public function key(): int
    {
        return key($this->segments);
    }

    public function valid(): bool
    {
        return key($this->segments) !== null;
    }

    public function rewind(): void
    {
        reset($this->segments);
    }
}
