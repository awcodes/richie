<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Strike as StrikeExtension;

class Strike extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.strike'))
            ->icon(icon: 'richie-strike')
            ->iconButton()
            ->command(name: 'toggleStrike')
            ->active(name: 'strike')
            ->converterExtensions(new StrikeExtension);
    }
}
