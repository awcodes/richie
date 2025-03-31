<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Code as CodeExtension;

class Code extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.code'))
            ->icon(icon: 'richie-code')
            ->iconButton()
            ->command(name: 'toggleCode')
            ->active(name: 'code')
            ->converterExtensions(new CodeExtension);
    }
}
