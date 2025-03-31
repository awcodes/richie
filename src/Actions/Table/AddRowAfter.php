<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class AddRowAfter extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.add_row_after'))
            ->icon(icon: 'richie-table-add-row-after')
            ->iconButton()
            ->command(name: 'addRowAfter');
    }
}
