<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class ExitFullscreen extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.exit_fullscreen'))
            ->icon(icon: 'richie-fullscreen-exit')
            ->iconButton()
            ->alpineClickHandler('toggleFullscreen($event)')
            ->extraAttributes([
                'x-show' => 'fullscreen',
            ]);
    }
}
