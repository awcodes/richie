<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class Undo extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.undo'))
            ->icon(icon: 'richie-undo')
            ->iconButton()
            ->command(name: 'undo');
    }
}
