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
    $field = (new Awcodes\Richie\RichieEditor('content'))
        ->container(ComponentContainer::make(TestForm::make()))
        ->mentions(['Bruce Banner', 'Tony Stark']);

    $mentions = $field->getMentions();

    expect($mentions)
        ->toBeArray()
        ->toHaveCount(2)
        ->toContain('Bruce Banner', 'Tony Stark');
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
