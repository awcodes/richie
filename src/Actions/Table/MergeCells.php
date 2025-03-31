<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class MergeCells extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.merge_cells'))
            ->icon(icon: 'richie-table-merge-cells')
            ->iconButton()
            ->command(name: 'mergeCells');
    }
}
