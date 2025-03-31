<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class RemoveMedia extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.media.remove'))
            ->icon(icon: 'richie-trash')
            ->iconButton()
            ->command(name: 'deleteSelection');
    }
}
