<?php

namespace Awcodes\Richie\Concerns;

use Awcodes\Richie\RichieAction;
use Closure;

trait HasSidebar
{
    protected array | Closure | null $sidebarActions = null;

    protected bool | Closure | null $isSidebarHidden = null;

    /**
     * @param  array<RichieAction> | Closure  $actions
     */
    public function sidebar(array | Closure $actions): static
    {
        $this->sidebarActions = $actions;

        return $this;
    }

    public function hiddenSidebar(bool | Closure $condition = true): static
    {
        $this->isSidebarHidden = $condition;

        return $this;
    }

    /**
     * @return array<RichieAction>
     */
    public function getSidebarActions(): array
    {
        return $this->evaluate($this->sidebarActions) ?? [];
    }

    public function isSidebarHidden(): bool
    {
        return $this->evaluate($this->isSidebarHidden) ?? false;
    }
}
