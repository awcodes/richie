<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Awcodes\Richie\Tiptap\Extensions\Color as ColorExtension;
use Filament\Forms\Components\ColorPicker;
use Filament\Support\Enums\MaxWidth;

class Color extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.color'))
            ->icon(icon: 'richie-color')
            ->iconButton()
            ->jsExtension('Color')
            ->converterExtensions(new ColorExtension)
            ->modalWidth(MaxWidth::Small)
            ->form([
                ColorPicker::make('color'),
            ])
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setColor',
                            arguments: [[
                                $data['color'],
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
