<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Extensions\TextAlign;

class AlignEnd extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.align.end'))
            ->icon(icon: 'richie-align-end')
            ->iconButton()
            ->command(name: 'setTextAlign', attributes: 'end')
            ->active(attributes: ['textAlign' => 'end'])
            ->jsExtension('TextAlign')
            ->converterExtensions(new TextAlign(['types' => ['heading', 'paragraph']]));
    }
}
