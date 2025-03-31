<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingFive extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.five'))
            ->icon(icon: 'richie-heading-five')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 5])
            ->visible(function (RichieEditor $component) {
                return in_array(5, $component->getHeadingLevels());
            })
            ->active(name: 'heading', attributes: ['level' => 5])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
