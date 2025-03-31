<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Extensions\TextAlign;

class AlignJustify extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.align.justify'))
            ->icon(icon: 'richie-align-justify')
            ->iconButton()
            ->command(name: 'setTextAlign', attributes: 'justify')
            ->active(attributes: ['textAlign' => 'justify'])
            ->jsExtension('TextAlign')
            ->converterExtensions(new TextAlign(['types' => ['heading', 'paragraph']]));
    }
}
