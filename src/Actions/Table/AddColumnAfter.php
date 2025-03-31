<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class AddColumnAfter extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.add_column_after'))
            ->icon(icon: 'richie-table-add-column-after')
            ->iconButton()
            ->command(name: 'addColumnAfter');
    }
}
