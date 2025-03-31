<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class TabletViewport extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.tablet_viewport'))
            ->icon(icon: 'richie-tablet')
            ->iconButton()
            ->alpineClickHandler('toggleViewport("tablet")')
            ->extraAttributes([
                'x-show' => 'fullscreen',
            ]);
    }
}
