<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Awcodes\Richie\Tiptap\Marks\Link as LinkExtension;
use Filament\Forms\Components;

class Link extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.link.label'))
            ->icon('richie-link')
            ->iconButton()
            ->active('link')
            ->jsExtension('Link')
            ->converterExtensions(new LinkExtension)
            ->form([
                Components\Grid::make(['md' => 3])
                    ->schema([
                        Components\TextInput::make('href')
                            ->label(fn () => trans('richie::richie.link.href'))
                            ->columnSpan('full')
                            ->requiredWithout('id')
                            ->validationAttribute('URL'),
                        Components\TextInput::make('id')
                            ->label(fn () => trans('richie::richie.link.id')),
                        Components\Select::make('target')
                            ->label(fn () => trans('richie::richie.link.target.label'))
                            ->selectablePlaceholder(false)
                            ->options([
                                '' => trans('richie::richie.link.target.self'),
                                '_blank' => trans('richie::richie.link.target.new_window'),
                                '_parent' => trans('richie::richie.link.target.parent'),
                                '_top' => trans('richie::richie.link.target.top'),
                            ]),
                        Components\TextInput::make('hreflang')
                            ->label(fn () => trans('richie::richie.link.hreflang')),
                        Components\TextInput::make('rel')
                            ->label(fn () => trans('richie::richie.link.rel'))
                            ->columnSpan('full'),
                        Components\TextInput::make('referrerpolicy')
                            ->label(fn () => trans('richie::richie.link.referrerpolicy'))
                            ->columnSpan('full'),
                    ]),
            ])
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setLink',
                            arguments: [[
                                ...$data,
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
