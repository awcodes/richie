<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingFour extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.four'))
            ->icon(icon: 'richie-heading-four')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 4])
            ->visible(fn (RichieEditor $component): bool => in_array(4, $component->getHeadingLevels()))
            ->active(name: 'heading', attributes: ['level' => 4])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
