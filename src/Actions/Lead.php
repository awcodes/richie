<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Awcodes\Richie\Tiptap\Nodes\Lead as LeadExtension;

class Lead extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.lead'))
            ->icon(icon: 'richie-lead')
            ->iconButton()
            ->command(name: 'toggleLead')
            ->active(name: 'lead')
            ->converterExtensions(new LeadExtension);
    }
}
