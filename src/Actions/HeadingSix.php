<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\RichieEditor;
use Tiptap\Nodes\Heading as HeadingExtension;

class HeadingSix extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.heading.six'))
            ->icon(icon: 'richie-heading-six')
            ->iconButton()
            ->command(name: 'toggleHeading', attributes: ['level' => 6])
            ->visible(fn (RichieEditor $component): bool => in_array(6, $component->getHeadingLevels()))
            ->active(name: 'heading', attributes: ['level' => 6])
            ->jsExtension('Heading')
            ->converterExtensions(new HeadingExtension);
    }
}
