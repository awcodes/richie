<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Superscript as SuperscriptExtension;

class Superscript extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.superscript'))
            ->icon(icon: 'richie-superscript')
            ->iconButton()
            ->command(name: 'toggleSuperscript')
            ->active(name: 'superscript')
            ->converterExtensions(new SuperscriptExtension);
    }
}
