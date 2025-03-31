<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class Redo extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.redo'))
            ->icon(icon: 'richie-redo')
            ->iconButton()
            ->command(name: 'redo');
    }
}
