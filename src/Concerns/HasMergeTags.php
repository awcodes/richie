<?php

namespace Awcodes\Richie\Concerns;

use Closure;

trait HasMergeTags
{
    protected array | Closure | null $mergeTags = null;

    protected bool | Closure | null $shouldShowInSidebar = null;

    public function mergeTags(array | Closure $tags, bool | Closure $showInSidebar = true): static
    {
        $this->mergeTags = $tags;
        $this->shouldShowInSidebar = $showInSidebar;

        return $this;
    }

    public function getMergeTags(): array
    {
        if ($this->shouldShowInSidebar()) {
            return $this->evaluate($this->mergeTags) ?? [];
        }

        return [];
    }

    public function shouldShowInSidebar(): bool
    {
        return $this->evaluate($this->shouldShowInSidebar) ?? true;
    }
}
