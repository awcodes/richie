<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Italic as ItalicExtension;

class Italic extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.italic'))
            ->icon(icon: 'richie-italic')
            ->iconButton()
            ->command(name: 'toggleItalic')
            ->active(name: 'italic')
            ->converterExtensions(new ItalicExtension);
    }
}
