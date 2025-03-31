<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class ClearContent extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.clear_content'))
            ->icon(icon: 'richie-erase')
            ->iconButton()
            ->command(name: 'clearContent', attributes: true);
    }
}
