<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingTwo extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.two'))
            ->icon(icon: 'richie-heading-two')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 2])
            ->visible(function (RichieEditor $component) {
                return in_array(2, $component->getHeadingLevels());
            })
            ->active(name: 'heading', attributes: ['level' => 2])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
