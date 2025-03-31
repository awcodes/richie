<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Underline as UnderlineExtension;

class Underline extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.underline'))
            ->icon(icon: 'richie-underline')
            ->iconButton()
            ->command(name: 'toggleUnderline')
            ->active(name: 'underline')
            ->converterExtensions(new UnderlineExtension);
    }
}
