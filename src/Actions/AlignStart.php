<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Extensions\TextAlign;

class AlignStart extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.align.start'))
            ->icon(icon: 'richie-align-start')
            ->iconButton()
            ->command(name: 'setTextAlign', attributes: 'start')
            ->jsExtension('TextAlign')
            ->converterExtensions(new TextAlign(['types' => ['heading', 'paragraph']]));
    }
}
