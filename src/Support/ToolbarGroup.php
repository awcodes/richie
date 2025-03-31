<?php

namespace Awcodes\Richie\Support;

use Filament\Actions\ActionGroup;
use Filament\Actions\StaticAction;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\IconPosition;

class ToolbarGroup extends ActionGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Formatting')
            ->icon('heroicon-s-chevron-down')
            ->iconPosition(IconPosition::After)
            ->color('gray')
            ->size(ActionSize::Small)
            ->button();
    }

    /**
     * @param  array<StaticAction | ActionGroup>  $actions
     */
    public static function make(array $actions): static
    {
        foreach ($actions as $action) {
            $action->grouped();
            $action->close();
        }

        $static = app(static::class, ['actions' => $actions]);
        $static->configure();

        return $static;
    }
}
