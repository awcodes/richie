<?php

namespace Awcodes\Richie;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RichieManager
{
    /**
     * @var array<RichieAction>|Closure
     */
    protected array | Closure $actions = [];

    protected array $registeredActionPaths = [];

    public function registerActionPath(string $in, string $for): static
    {
        if (! File::isDirectory($in) || in_array($in, $this->registeredActionPaths)) {
            return $this;
        }

        $actions = collect(File::allFiles($in))
            ->map(
                fn ($file) => Str::of($file->getRelativePathname())
                    ->before('.php')
                    ->replace('/', '\\')
                    ->start($for . '\\')
                    ->toString()
            )
            ->filter(fn ($action): bool => is_subclass_of($action, RichieAction::class))
            ->mapWithKeys(function ($action) {
                $action = $action::make(class_basename($action));

                return [$action->getJsExtension() => $action];
            })
            ->all();

        $this->actions = [...$this->actions, ...$actions];
        $this->registeredActionPaths[] = $in;

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions ?? [];
    }

    public function getAction(string $name): ?RichieAction
    {
        return $this->getActions()[$name] ?? null;
    }
}
