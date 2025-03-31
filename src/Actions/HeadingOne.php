<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingOne extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.one'))
            ->icon(icon: 'richie-heading-one')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 1])
            ->visible(function (RichieEditor $component) {
                return in_array(1, $component->getHeadingLevels());
            })
            ->active(name: 'heading', attributes: ['level' => 1])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
