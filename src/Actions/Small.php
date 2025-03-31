<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Marks\Small as SmallExtension;

class Small extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.small'))
            ->icon(icon: 'richie-small')
            ->iconButton()
            ->command(name: 'toggleSmall')
            ->active(name: 'small')
            ->converterExtensions(new SmallExtension);
    }
}
