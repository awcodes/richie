<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class MobileViewport extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.mobile_viewport'))
            ->icon(icon: 'richie-mobile')
            ->iconButton()
            ->alpineClickHandler('toggleViewport("mobile")')
            ->extraAttributes([
                'x-show' => 'fullscreen',
            ]);
    }
}
