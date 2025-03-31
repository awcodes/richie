<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Awcodes\Richie\Tiptap\Nodes\Media as ImageExtension;
use Filament\Forms\Components;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.media.label'))
            ->icon('richie-media')
            ->iconButton()
            ->active('media')
            ->jsExtension('Media')
            ->converterExtensions(new ImageExtension)
            ->fillForm(function (RichieEditor $component, array $arguments) {
                $source = isset($arguments['src']) && $arguments['src'] !== ''
                    ? $component->getDirectory() . Str::of($arguments['src'])
                        ->after($component->getDirectory())
                    : null;

                $arguments['src'] = $source;

                return $arguments;
            })
            ->form(function (RichieEditor $component) {
                return [
                    Components\Grid::make()
                        ->schema([
                            Components\Group::make([
                                $component->getUploader(),
                            ])->columnSpan(1),
                            Components\Group::make([
                                Components\TextInput::make('link_text')
                                    ->label(fn () => trans('richie::richie.media.link_text'))
                                    ->required()
                                    ->visible(fn (Get $get) => $get('type') == 'document'),
                                Components\TextInput::make('alt')
                                    ->label(fn () => trans('richie::richie.media.alt'))
                                    ->hidden(fn (Get $get) => $get('type') == 'document')
                                    ->hintAction(
                                        Action::make('alt_hint_action')
                                            ->label('?')
                                            ->color('primary')
                                            ->tooltip('Learn how to describe the purpose of the image.')
                                            ->url('https://www.w3.org/WAI/tutorials/images/decision-tree', true)
                                    ),
                                Components\TextInput::make('title')
                                    ->label(fn () => trans('richie::richie.media.title')),
                                Components\Group::make([
                                    Components\TextInput::make('width')
                                        ->label(fn () => trans('richie::richie.media.width')),
                                    Components\TextInput::make('height')
                                        ->label(fn () => trans('richie::richie.media.height')),
                                ])->columns()->hidden(fn (Get $get) => $get('type') == 'document'),
                                Components\ToggleButtons::make('alignment')
                                    ->label(fn () => trans('richie::richie.media.alignment.label'))
                                    ->options([
                                        'start' => trans('richie::richie.media.alignment.start'),
                                        'center' => trans('richie::richie.media.alignment.center'),
                                        'end' => trans('richie::richie.media.alignment.end'),
                                    ])
                                    ->grouped()
                                    ->afterStateHydrated(function (Components\ToggleButtons $component, $state) {
                                        if (! $state) {
                                            $component->state('start');
                                        }
                                    }),
                                Components\Checkbox::make('loading')
                                    ->label(fn () => trans('richie::richie.media.loading'))
                                    ->dehydrateStateUsing(function ($state): ?string {
                                        if ($state) {
                                            return 'lazy';
                                        }

                                        return null;
                                    })
                                    ->hidden(fn (Get $get) => $get('type') == 'document'),
                            ])->columnSpan(1),
                        ]),
                    Components\Hidden::make('type')
                        ->default('document'),
                ];
            })
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $source = str_starts_with($data['src'], 'http')
                    ? $data['src']
                    : Storage::disk($component->getUploader()->getDiskName())->url($data['src']);

                if ($component->useRelativePaths()) {
                    $source = (string) Str::of($source)
                        ->replace(config('app.url'), '')
                        ->ltrim('/')
                        ->prepend('/');
                }

                $component->runCommands(
                    [
                        new EditorCommand(
                            name: 'setMedia',
                            arguments: [[
                                ...$data,
                                'src' => $source,
                            ]],
                        ),
                    ],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }
}
