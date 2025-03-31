<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Nodes\CodeBlockHighlight as CodeBlockExtension;

class CodeBlock extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.code_block'))
            ->icon(icon: 'richie-code-block')
            ->iconButton()
            ->command(name: 'toggleCodeBlock')
            ->active(name: 'codeBlock')
            ->converterExtensions(new CodeBlockExtension([
                'languageClassPrefix' => 'language-',
            ]));
    }
}
