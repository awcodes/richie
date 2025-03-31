<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Awcodes\Richie\Tiptap\Nodes\Embed as EmbedExtension;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Get;

class Embed extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.embed.label'))
            ->icon('richie-embed')
            ->iconButton()
            ->active('embed')
            ->jsExtension('Embed')
            ->converterExtensions(new EmbedExtension)
            ->fillForm([
                'responsive' => true,
                'width' => 16,
                'height' => 9,
            ])
            ->form([
                TextInput::make('src')
                    ->label(fn () => trans('richie::richie.embed.url'))
                    ->live()
                    ->required(),
                CheckboxList::make('options')
                    ->hiddenLabel()
                    ->gridDirection('row')
                    ->columns(3)
                    ->visible(function (Get $get) {
                        return $get('src');
                    })
                    ->options(function (Get $get) {
                        if (str_contains($get('src'), 'youtu')) {
                            return [
                                'controls' => trans('richie::richie.embed.controls'),
                                'nocookie' => trans('richie::richie.embed.nocookie'),
                            ];
                        }

                        return [
                            'autoplay' => trans('richie::richie.embed.autoplay'),
                            'loop' => trans('richie::richie.embed.loop'),
                            'title' => trans('richie::richie.embed.title'),
                            'byline' => trans('richie::richie.embed.byline'),
                            'portrait' => trans('richie::richie.embed.portrait'),
                        ];
                    })
                    ->dehydrateStateUsing(function (Get $get, $state) {
                        if (str_contains($get('src'), 'youtu')) {
                            return [
                                'controls' => in_array('controls', $state) ? 1 : 0,
                                'nocookie' => in_array('nocookie', $state) ? 1 : 0,
                            ];
                        } else {
                            return [
                                'autoplay' => in_array('autoplay', $state) ? 1 : 0,
                                'loop' => in_array('loop', $state) ? 1 : 0,
                                'title' => in_array('title', $state) ? 1 : 0,
                                'byline' => in_array('byline', $state) ? 1 : 0,
                                'portrait' => in_array('portrait', $state) ? 1 : 0,
                            ];
                        }
                    }),
                TimePicker::make('start_at')
                    ->label(fn () => trans('richie::richie.embed.start_at'))
                    ->live()
                    ->date(false)
                    ->visible(function (Get $get) {
                        return str_contains($get('src'), 'youtu');
                    })
                    ->afterStateHydrated(function (TimePicker $component, $state): void {
                        if (! $state) {
                            return;
                        }

                        $state = CarbonInterval::seconds($state)->cascade();
                        $component->state(Carbon::parse($state->h . ':' . $state->i . ':' . $state->s)->format('Y-m-d H:i:s'));
                    })
                    ->dehydrateStateUsing(function ($state): int | float {
                        if (! $state) {
                            return 0;
                        }

                        return Carbon::parse($state)->diffInSeconds('00:00:00');
                    }),
                Checkbox::make('responsive')
                    ->default(true)
                    ->live()
                    ->label(fn () => trans('richie::richie.embed.responsive'))
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $set('width', '16');
                            $set('height', '9');
                        } else {
                            $set('width', '640');
                            $set('height', '480');
                        }
                    })
                    ->columnSpan('full'),
                Group::make([
                    TextInput::make('width')
                        ->live()
                        ->required()
                        ->label(fn () => trans('richie::richie.embed.width'))
                        ->default('16'),
                    TextInput::make('height')
                        ->live()
                        ->required()
                        ->label(fn () => trans('richie::richie.embed.height'))
                        ->default('9'),
                ])->columns(['md' => 2]),
            ])
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setEmbed',
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
