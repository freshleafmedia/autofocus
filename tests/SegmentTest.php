<?php

use FreshleafMedia\Autofocus\Segment;

test('Segment midpoint', function (int $x, int $y, int $midX, int $midY) {
    $segment = new Segment($x, $y, 10, 10, 0);

    expect($segment->midPoint()->x)->toBe($midX);
    expect($segment->midPoint()->y)->toBe($midY);
})
->with([
    [0, 0, 5, 5],
    [20, 30, 25, 35],
    [0, 300, 5, 305],
]);

