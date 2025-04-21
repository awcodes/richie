<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingThree extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.three'))
            ->icon(icon: 'richie-heading-three')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 3])
            ->visible(fn (RichieEditor $component): bool => in_array(3, $component->getHeadingLevels()))
            ->active(name: 'heading', attributes: ['level' => 3])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
