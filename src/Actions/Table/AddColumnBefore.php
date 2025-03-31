<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class AddColumnBefore extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.add_column_before'))
            ->icon(icon: 'richie-table-add-column-before')
            ->iconButton()
            ->command(name: 'addColumnBefore');
    }
}
