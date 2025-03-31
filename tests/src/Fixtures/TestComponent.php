<?php

namespace Awcodes\Richie\Tests\Fixtures;

use Awcodes\Richie\RichieEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class TestComponent extends TestForm
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                TextInput::make('title'),
                TextInput::make('slug'),
                RichieEditor::make('content'),
            ]);
    }
}
