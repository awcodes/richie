<?php

namespace Awcodes\Richie;

use Awcodes\Richie\Concerns\HasCustomStyles;
use Closure;
use Filament\Infolists\Components\Entry;

class RichieEntry extends Entry
{
    use HasCustomStyles;

    protected array | Closure | null $mergeTagsMap = null;

    protected string $view = 'richie::richie-entry';

    public function mergeTagsMap(array | Closure $map): static
    {
        $this->mergeTagsMap = $map;

        return $this;
    }

    public function getMergeTagsMap(): array
    {
        return $this->evaluate($this->mergeTagsMap) ?? [];
    }
}
