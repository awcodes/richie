<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class EnterFullscreen extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.enter_fullscreen'))
            ->icon(icon: 'richie-fullscreen-enter')
            ->iconButton()
            ->alpineClickHandler('toggleFullscreen($event)')
            ->extraAttributes([
                'x-show' => '! fullscreen',
            ]);
    }
}
