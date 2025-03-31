<?php

namespace Awcodes\Richie\Concerns;

use Closure;

trait HasMentions
{
    protected array | Closure | null $mentions = null;

    public function mentions(array | Closure $mentions): static
    {
        $this->mentions = $mentions;

        return $this;
    }

    public function getMentions(): array
    {
        return $this->evaluate($this->mentions) ?? [];
    }
}
