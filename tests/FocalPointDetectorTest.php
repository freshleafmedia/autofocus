<?php

use FreshleafMedia\Autofocus\FocalPointDetector;
use FreshleafMedia\Autofocus\Image;

test('Detection of focal point', function (string $path, int $expectedX, int $expectedY) {
    $detector = new FocalPointDetector();

    $point = $detector->getPoint(new Imagick($path));

    expect($point->x)->toBe($expectedX);
    expect($point->y)->toBe($expectedY);
})
->with([
    [__DIR__ . '/assets/owl.jpg', 178, 325],
    [__DIR__ . '/assets/bench.jpg', 283, 157],
    [__DIR__ . '/assets/bike.jpg', 262, 178],
]);

test('Debug method returns Image', function () {
    $detector = new FocalPointDetector();

    $image = $detector->debug(new Imagick(__DIR__ . '/assets/owl.jpg'));

    expect($image)->toBeInstanceOf(Image::class);
});
