<?php

use FreshleafMedia\Autofocus\Segment;
use FreshleafMedia\Autofocus\SegmentCollection;

test('Get max segment', function () {
    $segments = [
        new Segment(0, 0, 10, 10, 1),
        new Segment(1, 0, 10, 10, 5),
        new Segment(2, 0, 10, 10, 4),
        new Segment(3, 0, 10, 10, 3),
        new Segment(4, 0, 10, 10, 1),
    ];

    $segmentCollection = new SegmentCollection($segments);

    expect($segmentCollection->getMaxIntensitySegment())->toBe($segments[1]);
});

test('Get min segment', function () {
    $segments = [
        new Segment(0, 0, 10, 10, 12),
        new Segment(1, 0, 10, 10, 6),
        new Segment(2, 0, 10, 10, 3),
        new Segment(3, 0, 10, 10, 15),
        new Segment(4, 0, 10, 10, 25),
    ];

    $segmentCollection = new SegmentCollection($segments);

    expect($segmentCollection->getMinIntensitySegment())->toBe($segments[2]);
});
