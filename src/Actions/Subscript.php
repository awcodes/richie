<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Subscript as SubscriptExtension;

class Subscript extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.subscript'))
            ->icon(icon: 'richie-subscript')
            ->iconButton()
            ->command(name: 'toggleSubscript')
            ->active(name: 'subscript')
            ->converterExtensions(new SubscriptExtension);
    }
}
