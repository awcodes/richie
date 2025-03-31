<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;

class ToggleSidebar extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.sidebar'))
            ->icon(icon: 'richie-sidebar')
            ->iconButton()
            ->alpineClickHandler('toggleSidebar($event)')
            ->visible(function (RichieEditor $component) {
                return (! $component->isSidebarHidden()) &&
                (! $component->isDisabled()) && (filled($component->getMergeTags()) || filled($component->getSidebarActions()));
            });
    }
}
