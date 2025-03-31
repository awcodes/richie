<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Extensions\TextAlign;

class AlignCenter extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.align.center'))
            ->icon(icon: 'richie-align-center')
            ->iconButton()
            ->command(name: 'setTextAlign', attributes: 'center')
            ->active(attributes: ['textAlign' => 'center'])
            ->jsExtension('TextAlign')
            ->converterExtensions(new TextAlign(['types' => ['heading', 'paragraph']]));
    }
}
