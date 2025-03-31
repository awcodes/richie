<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Nodes\Blockquote as BlockquoteExtension;

class Blockquote extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.blockquote'))
            ->icon(icon: 'richie-blockquote')
            ->iconButton()
            ->command(name: 'toggleBlockquote')
            ->active(name: 'blockquote')
            ->converterExtensions(new BlockquoteExtension);
    }
}
