<?php

namespace Awcodes\Richie\Actions\Table;

use Awcodes\Richie\RichieAction;

class DeleteColumn extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.table.delete_column'))
            ->icon(icon: 'richie-table-delete-column')
            ->iconButton()
            ->command(name: 'deleteColumn');
    }
}
