<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class SplitCell extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.split_cell'))
            ->icon(icon: 'richie-table-split-cell')
            ->iconButton()
            ->command(name: 'splitCell');
    }
}
