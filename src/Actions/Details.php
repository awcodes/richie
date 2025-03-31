<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Nodes\Details as DetailsExtension;
use Awcodes\Richie\Tiptap\Nodes\DetailsContent as DetailsContentExtension;
use Awcodes\Richie\Tiptap\Nodes\DetailsSummary as DetailsSummaryExtension;

class Details extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.details'))
            ->icon(icon: 'richie-details')
            ->iconButton()
            ->command(name: 'setDetails')
            ->active(name: 'details')
            ->converterExtensions([
                new DetailsExtension,
                new DetailsContentExtension,
                new DetailsSummaryExtension,
            ]);
    }
}
