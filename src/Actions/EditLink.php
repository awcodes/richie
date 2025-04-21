<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieEditor;
use Awcodes\Richie\Support\EditorCommand;
use Exception;

class EditLink extends Link
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.link.edit'))
            ->icon('richie-edit')
            ->active(null)
            ->fillForm(fn (array $arguments): array => $arguments)
            ->action(function (RichieEditor $component, array $arguments, array $data): void {
                $isSingleCharacterSelection = ($arguments['editorSelection']['head'] ?? null) === ($arguments['editorSelection']['anchor'] ?? null);

                $component->runCommands(
                    [
                        ...($isSingleCharacterSelection ? [new EditorCommand(
                            name: 'extendMarkRange',
                            arguments: ['link'],
                        )] : []),
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
