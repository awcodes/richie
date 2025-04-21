<?php

namespace Awcodes\Richie\Support;

use Filament\Actions\ActionGroup;
use Filament\Actions\StaticAction;
use Filament\Support\Enums\ActionSize;

class BubbleMenu extends ActionGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->size(ActionSize::Small);
    }

    /**
     * @param  array<StaticAction | ActionGroup>  $actions
     */
    public static function make(array $actions): static
    {
        foreach ($actions as $action) {
            $action->grouped();
        }

        $static = app(static::class, ['actions' => $actions]);
        $static->configure();

        return $static;
    }

    public function getActions(): array
    {
        return array_map(
            fn (StaticAction | ActionGroup $action): \Filament\Actions\StaticAction | \Filament\Actions\ActionGroup => $action->defaultView($action::GROUPED_VIEW)->iconButton(),
            $this->actions,
        );
    }
}
