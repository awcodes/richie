<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Awcodes\Richie\Tiptap\Nodes\Grid as GridExtension;
use Awcodes\Richie\Tiptap\Nodes\GridColumn as GridColumnExtension;
use Filament\Forms\Components;
use Filament\Forms\Get;

class Grid extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.grid.label'))
            ->icon('richie-grid')
            ->iconButton()
            ->active('grid')
            ->jsExtension('Grid')
            ->converterExtensions([
                new GridExtension,
                new GridColumnExtension,
            ])
            ->fillForm([
                'columns' => 2,
                'stack_at' => 'md',
                'asymmetric' => false,
            ])
            ->form([
                Components\Grid::make(2)
                    ->schema([
                        Components\View::make('richie::components.grid-preview')
                            ->columnSpanFull(),
                        Components\TextInput::make('columns')
                            ->label(fn () => trans('richie::richie.grid.columns'))
                            ->required()
                            ->live()
                            ->minValue(2)
                            ->maxValue(12)
                            ->numeric()
                            ->step(1),
                        Components\Select::make('stack_at')
                            ->label(fn () => trans('richie::richie.grid.stack_at'))
                            ->live()
                            ->selectablePlaceholder(false)
                            ->options([
                                'none' => 'Don\'t Stack',
                                'sm' => 'sm',
                                'md' => 'md',
                                'lg' => 'lg',
                            ])
                            ->default('md'),
                        Components\Toggle::make('asymmetric')
                            ->label(fn () => trans('richie::richie.grid.asymmetric'))
                            ->default(false)
                            ->live()
                            ->columnSpanFull(),
                        Components\TextInput::make('left_span')
                            ->label(fn () => trans('richie::richie.grid.left_span'))
                            ->required()
                            ->live()
                            ->minValue(1)
                            ->maxValue(12)
                            ->numeric()
                            ->visible(fn (Get $get) => $get('asymmetric')),
                        Components\TextInput::make('right_span')
                            ->label(fn () => trans('richie::richie.grid.right_span'))
                            ->required()
                            ->live()
                            ->minValue(1)
                            ->maxValue(12)
                            ->numeric()
                            ->visible(fn (Get $get) => $get('asymmetric')),
                    ]),
            ])
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'insertGrid',
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
