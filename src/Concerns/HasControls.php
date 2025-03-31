<?php

namespace Awcodes\Richie\Concerns;

use Awcodes\Richie\Actions;
use Awcodes\Richie\RichieAction;
use Closure;

trait HasControls
{
    protected array | Closure | null $controls = null;

    /**
     * @param  array<RichieAction> | Closure  $actions
     */
    public function controls(array | Closure $actions): static
    {
        $this->controls = $actions;

        return $this;
    }

    /**
     * @return array<RichieAction>
     */
    public function getControls(): array
    {
        return $this->evaluate($this->controls) ?? [
            Actions\MobileViewport::make('controlToggleMobileViewport'),
            Actions\TabletViewport::make('controlToggleTabletViewport'),
            Actions\DesktopViewport::make('controlToggleDesktopViewport'),
            Actions\Undo::make('controlUndo'),
            Actions\Redo::make('controlRedo'),
            Actions\ClearContent::make('controlClearContent'),
            Actions\EnterFullscreen::make('controlEnterFullscreen'),
            Actions\ExitFullscreen::make('controlExitFullscreen'),
            Actions\ToggleSidebar::make('controlToggleSidebar'),
        ];
    }
}
