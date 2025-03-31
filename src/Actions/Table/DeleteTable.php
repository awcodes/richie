<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class DeleteTable extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.delete'))
            ->icon(icon: 'richie-table-delete')
            ->iconButton()
            ->command(name: 'deleteTable');
    }
}
