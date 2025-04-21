<?php

namespace Awcodes\Richie;

use Awcodes\Richie\Support\EditorCommand;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasPlaceholder;
use Illuminate\Support\Collection;
use Livewire\Component;

class RichieEditor extends Field
{
    use Concerns\HasBubbleMenus;
    use Concerns\HasControls;
    use Concerns\HasCustomStyles;
    use Concerns\HasMentions;
    use Concerns\HasMergeTags;
    use Concerns\HasSidebar;
    use Concerns\HasSuggestions;
    use Concerns\HasToolbar;
    use Concerns\InteractsWithBlocks;
    use Concerns\InteractsWithMedia;
    use HasExtraInputAttributes;
    use HasPlaceholder;

    protected string $view = 'richie::richie-editor';

    protected array | Closure | null $headingLevels = null;

    protected string | Closure | null $customDocument = null;

    protected array | Closure | null $linkProtocols = null;

    protected bool | Closure | null $wordCount = null;

    protected array | bool | Closure $enableInputRules = true;

    protected array | bool | Closure $enablePasteRules = true;

    protected array | Closure | null $nodePlaceholders = null;

    protected array | bool | null $showOnlyCurrentPlaceholder = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (RichieEditor $component, $state) {
            if (! $state) {
                return null;
            }

            $state = $this->renderBlockViews($state, $component);

            $component->state($state);
        });

        $this->afterStateUpdated(function (RichieEditor $component, Component $livewire): void {
            $livewire->validateOnly($component->getStatePath());
        });

        $this->dehydrateStateUsing(function ($state): ?array {
            if (! $state) {
                return null;
            }

            return $this->sanitizeBlocksBeforeSave($state);
        });

        $this->registerListeners([]);

        $this->registerActions($this->getActionsToRegister());
    }

    public function getAllActions(): Collection
    {
        return collect([
            ...$this->getControls(),
            ...$this->getSuggestions(),
            ...$this->getBubbleMenuActions(),
            ...$this->getSidebarActions(),
            ...$this->getToolbarActions(),
        ]);
    }

    public function getActionsToRegister(): array
    {
        return $this->getAllActions()
            ->map(fn ($action): \Closure => fn (): Action => $action)
            ->toArray();
    }

    public function getAllowedExtensions(): array
    {
        return $this->getAllActions()
            ->map(fn ($action) => $action->getJsExtension())
            ->unique()
            ->toArray();
    }

    public function headingLevels(array | Closure $levels): static
    {
        $this->headingLevels = $levels;

        return $this;
    }

    public function getHeadingLevels(): array
    {
        return $this->evaluate($this->headingLevels) ?? [1, 2, 3];
    }

    public function customDocument(string | Closure | null $customDocument): static
    {
        $this->customDocument = $customDocument;

        return $this;
    }

    public function getCustomDocument(): ?string
    {
        return $this->evaluate($this->customDocument);
    }

    public function linkProtocols(array | Closure $linkProtocols): static
    {
        $this->linkProtocols = $linkProtocols;

        return $this;
    }

    public function getLinkProtocols(): array
    {
        return $this->evaluate($this->linkProtocols) ?? [];
    }

    public function wordCount(bool | Closure $wordCount = true): static
    {
        $this->wordCount = $wordCount;

        return $this;
    }

    public function shouldShowWordCount(): bool
    {
        return $this->evaluate($this->wordCount) ?? false;
    }

    public function enableInputRules(array | bool | Closure $rules = true): static
    {
        $this->enableInputRules = $rules;

        return $this;
    }

    public function enablePasteRules(array | bool | Closure $rules = true): static
    {
        $this->enablePasteRules = $rules;

        return $this;
    }

    public function getEnableInputRules(): bool | array
    {
        return $this->evaluate($this->enableInputRules);
    }

    public function getEnablePasteRules(): bool | array
    {
        return $this->evaluate($this->enablePasteRules);
    }

    public function nodePlaceholders(array | Closure | null $nodePlaceholders): static
    {
        $this->nodePlaceholders = $nodePlaceholders;

        return $this;
    }

    public function getNodePlaceholders(): ?array
    {
        return $this->evaluate($this->nodePlaceholders);
    }

    public function showOnlyCurrentPlaceholder(bool | Closure | null $showOnlyCurrent): static
    {
        $this->showOnlyCurrentPlaceholder = $showOnlyCurrent;

        return $this;
    }

    public function getShowOnlyCurrentPlaceholder(): ?bool
    {
        return $this->evaluate($this->showOnlyCurrentPlaceholder);
    }

    /**
     * @param  array<EditorCommand>  $commands
     * @param  array<string, mixed>  $editorSelection
     */
    public function runCommands(array $commands, array $editorSelection): void
    {
        $key = $this->getKey();
        $livewire = $this->getLivewire();

        $livewire->dispatch(
            event: 'run-richie-commands',
            awaitRichieComponent: $key,
            livewireId: $livewire->getId(),
            key: $key,
            editorSelection: $editorSelection,
            commands: array_map(fn (EditorCommand $command): array => $command->toArray(), $commands),
        );
    }
}
