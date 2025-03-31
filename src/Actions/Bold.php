<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Bold as BoldExtension;

class Bold extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.bold'))
            ->icon(icon: 'richie-bold')
            ->iconButton()
            ->command(name: 'toggleBold')
            ->active(name: 'bold')
            ->converterExtensions(new BoldExtension);
    }
}
