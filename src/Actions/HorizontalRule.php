<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Nodes\HorizontalRule as HorizontalRuleExtension;

class HorizontalRule extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.horizontal_rule'))
            ->icon(icon: 'richie-hr')
            ->iconButton()
            ->command(name: 'setHorizontalRule')
            ->converterExtensions(new HorizontalRuleExtension);
    }
}
