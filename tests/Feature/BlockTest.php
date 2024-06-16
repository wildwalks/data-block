<?php

use HiFolks\DataType\Block;

test('load JSON object HTTP', function () {
    $jsonString = file_get_contents("./tests/data/story.json");

    $composerContent = Block::fromJsonString($jsonString);
    expect($composerContent->get("story.name"))->toBe("Home");
    expect($composerContent->getBlock("story.content"))->toBeInstanceOf(Block::class);
    expect($composerContent->get("story.content"))->toHaveKey("body");
    $bodyComponents = $composerContent->getBlock("story.content.body");
    expect($bodyComponents)->toHaveCount(10);
    expect($bodyComponents->get("0.headline"))->toBe("New banner");
    expect($bodyComponents->get("1.headline"))->toBe("Hello Everyone");
    expect($bodyComponents->get("2.headline"))->toBe("We don't know what we don't know.");
    expect($composerContent->get("cv"))->toBe(1717763755);
});


it('load JSON object', function (): void {
    $file = "./composer.json";
    $composerContent = Block::fromJsonFile($file);
    expect($composerContent->get("name"))->toBe("hi-folks/data-block");
    expect($composerContent->get("authors.0.name"))->toBe("Roberto B.");
    $composerContent->set("authors.0.name", "Test");
    expect($composerContent->get("authors.0.name"))->toBe("Test");
});

it('export to array', function (): void {
    $file = "./composer.json";
    $composerContent = Block::fromJsonFile($file);
    $array = $composerContent->toArray();
    expect($array)->toBeArray();
    expect($array)->toHaveKeys(["name","authors"]);
    expect($array["authors"])->toHaveKeys([0]);
    expect($array["authors"][0])->toHaveKeys(["name"]);
    expect($array["authors"][0]["name"])->toBe("Roberto B.");

});
