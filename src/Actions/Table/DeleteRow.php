<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class DeleteRow extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.delete_row'))
            ->icon(icon: 'richie-table-delete-row')
            ->iconButton()
            ->command(name: 'deleteRow');
    }
}
