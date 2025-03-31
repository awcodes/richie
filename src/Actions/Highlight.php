<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Marks\Highlight as HighlightExtension;

class Highlight extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.highlight'))
            ->icon(icon: 'richie-highlight')
            ->iconButton()
            ->command(name: 'toggleHighlight')
            ->active(name: 'highlight')
            ->converterExtensions(new HighlightExtension);
    }
}
