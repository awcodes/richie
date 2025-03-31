<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Filament\Forms\Components;
use Filament\Forms\Get;
use Tiptap\Nodes\Table as TableExtension;
use Tiptap\Nodes\TableCell;
use Tiptap\Nodes\TableHeader;
use Tiptap\Nodes\TableRow;

class Table extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.label'))
            ->icon('richie-table')
            ->iconButton()
            ->active('table')
            ->jsExtension('Table')
            ->converterExtensions([
                new TableExtension,
                new TableRow,
                new TableCell,
                new TableHeader,
            ])
            ->fillForm([
                'rows' => 2,
                'cols' => 3,
                'withHeaderRow' => true,
            ])
            ->form([
                Components\TextInput::make('rows')
                    ->label(fn () => trans('richie::richie.table.rows'))
                    ->numeric()
                    ->required()
                    ->dehydrateStateUsing(function (Get $get, $state) {
                        if ($get('withHeaderRow')) {
                            return $state + 1;
                        }

                        return $state;
                    }),
                Components\TextInput::make('cols')
                    ->label(fn () => trans('richie::richie.table.columns'))
                    ->numeric()
                    ->required(),
                Components\Checkbox::make('withHeaderRow')
                    ->label(fn () => trans('richie::richie.table.header_row')),
            ])
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'insertTable',
                            arguments: [[
                                $data,
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
