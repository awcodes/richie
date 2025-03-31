<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Nodes\OrderedList as OrderedListExtension;

class OrderedList extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.ordered_list'))
            ->icon(icon: 'richie-list-ordered')
            ->iconButton()
            ->command(name: 'toggleOrderedList')
            ->active(name: 'unorderedList')
            ->converterExtensions(new OrderedListExtension);
    }
}
