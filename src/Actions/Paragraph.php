<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class Paragraph extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.paragraph'))
            ->icon(icon: 'richie-paragraph')
            ->iconButton()
            ->command(name: 'setParagraph')
            ->active(name: 'paragraph');
    }
}
