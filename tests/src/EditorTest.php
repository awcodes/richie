<?php

use Awcodes\Richie\Actions\Bold;
use Awcodes\Richie\Tests\Fixtures\TestForm;
use Filament\Forms\ComponentContainer;

it('registers toolbar actions', function () {
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->toolbar([
            Bold::make('Bold'),
        ], merge: false);

    $actions = $field->getToolbarActions();

    expect($actions)
        ->toBeArray()
        ->toHaveCount(1)
        ->and($actions[0])->toBeInstanceOf(Bold::class);
});

it('registers suggestion actions', function () {
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->suggestions([
            Bold::make('Bold'),
        ], merge: false);

    $actions = $field->getSuggestions();

    expect($actions)
        ->toBeArray()
        ->toHaveCount(1)
        ->and($actions[0])->toBeInstanceOf(Bold::class);
});

it('registers merge tags', function () {
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->mergeTags(['name', 'email', 'phone']);

    $tags = $field->getMergeTags();

    expect($tags)
        ->toBeArray()
        ->toHaveCount(3)
        ->toContain('name', 'email', 'phone');
});

it('registers mentions', function () {
    $items = [
        ['label' => 'Batman', 'id' => 1],
        ['label' => 'Robin', 'id' => 2],
        ['label' => 'Joker', 'id' => 3],
        ['label' => 'Poison Ivy', 'id' => 4],
        ['label' => 'Harley Quinn', 'id' => 5],
    ];

    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->mentionItems($items);

    $mentions = $field->getMentionItems();

    expect($mentions)
        ->toBeArray()
        ->toHaveCount(5)
        ->toEqual($items);
});

it('supports custom document structure', function () {
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->customDocument('heading block+');

    expect($field->getCustomDocument())
        ->toBe('heading block+');
});

it('supports heading levels', function () {
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->headingLevels([2, 3]);

    expect($field->getHeadingLevels())
        ->toBeArray()
        ->toHaveCount(2)
        ->toContain(2, 3);
});
