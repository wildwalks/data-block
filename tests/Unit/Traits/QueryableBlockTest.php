<?php

use HiFolks\DataType\Block;

$fruitsArray = [
    "avocado" =>
    [
        'name' => 'Avocado',
        'fruit' => '🥑',
        'wikipedia' => 'https://en.wikipedia.org/wiki/Avocado',
        'color' => 'green',
        'rating' => 8,
        'tags' => [
            'green',
            'soft',
            'Smashtacular '
        ],
    ],
    "apple" =>
    [
        'name' => 'Apple',
        'fruit' => '🍎',
        'wikipedia' => 'https://en.wikipedia.org/wiki/Apple',
        'color' => 'red',
        'rating' => 7,
        'tags' => [
            'red',
            'sweet',
            'core'
        ],
    ],
    "banana" =>
    [
        'name' => 'Banana',
        'fruit' => '🍌',
        'wikipedia' => 'https://en.wikipedia.org/wiki/Banana',
        'color' => 'yellow',
        'rating' => 8.5,
        'tags' => [
            'yellow',
            'soft',
            'sweet',
            'appealing'
        ],
    ],
    "cherry" =>
    [
        'name' => 'Cherry',
        'fruit' => '🍒',
        'wikipedia' => 'https://en.wikipedia.org/wiki/Cherry',
        'color' => 'red',
        'rating' => 9,
        'tags' => [
            'red',
            'sour',
            'Cherrysh'
        ],
    ],
];

test(
    'Test query greater than x',
    function () use ($fruitsArray): void {
        $data = Block::make($fruitsArray);
        $highRated = $data->where("rating", ">", 8);
        expect($highRated)->tohaveCount(2);
        $sorted = $data->where("rating", ">", 8)->orderBy("rating", "desc");
        expect($sorted)->tohaveCount(2);


    },
);

test(
    'group by',
    function () use ($fruitsArray): void {
        $table = Block::make($fruitsArray);
        $grouped = $table->groupBy("color");
        expect($grouped->getBlock("red"))->tohaveCount(2);
        expect($grouped->getBlock("yellow"))->tohaveCount(1);
        expect($grouped->getBlock("NotExists"))->tohaveCount(0);

    },
);

test(
    'group by 2',
    function (): void {
        $data = Block::make([
            ['type' => 'fruit', 'name' => 'apple'],
            ['type' => 'fruit', 'name' => 'banana'],
            ['type' => 'vegetable', 'name' => 'carrot'],
        ]);
        $grouped = $data->groupBy('type');
        expect($grouped->getBlock("fruit"))->tohaveCount(2);
        expect($grouped->getBlock("vegetable"))->tohaveCount(1);
        expect($grouped->getBlock("NotExists"))->tohaveCount(0);

    },
);

test(
    'where method, in operator',
    function () use ($fruitsArray): void {
        $data = Block::make($fruitsArray);
        $greenOrBlack = $data->where("color", "in", ["green", "black"]);
        expect($greenOrBlack)->tohaveCount(1);
        $noResult = $data->where("color", "in", []);
        expect($noResult)->tohaveCount(0);
        $greenOrRed = $data->where("color", "in", ["green", "red"]);
        expect($greenOrRed)->tohaveCount(3);
    },
);

test(
    'where method, has operator',
    function () use ($fruitsArray): void {
        $data = Block::make($fruitsArray);
        $sweet = $data->where("tags", "has", "sweet");
        expect($sweet)->tohaveCount(2);
        $noResult = $data->where("tags", "has", "not-existent");
        expect($noResult)->tohaveCount(0);
        $softFruit = $data->where("tags", "has", "soft");
        expect($softFruit)->tohaveCount(2);
    },
);
