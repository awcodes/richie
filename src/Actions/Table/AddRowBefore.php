<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class AddRowBefore extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.add_row_before'))
            ->icon(icon: 'richie-table-add-row-before')
            ->iconButton()
            ->command(name: 'addRowBefore');
    }
}
