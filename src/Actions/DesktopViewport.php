<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class DesktopViewport extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.desktop_viewport'))
            ->icon(icon: 'richie-desktop')
            ->iconButton()
            ->alpineClickHandler('toggleViewport("desktop")')
            ->extraAttributes([
                'x-show' => 'fullscreen',
            ]);
    }
}
