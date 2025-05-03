<?php

namespace Awcodes\Richie\Concerns;

use Awcodes\Richie\Actions;
use Awcodes\Richie\RichieAction;
use Closure;
use Illuminate\Support\Facades\Blade;

trait HasSuggestions
{
    protected array | Closure | null $suggestions = null;

    protected bool | Closure | null $mergeSuggestionActions = null;

    /**
     * @param  array<RichieAction> | Closure  $actions
     */
    public function suggestions(array | Closure $actions, bool | Closure $merge = true): static
    {
        $this->suggestions = $actions;
        $this->mergeSuggestionActions = $merge;

        $this->registerActions($actions);

        return $this;
    }

    /**
     * @return array<RichieAction>
     */
    public function getSuggestions(): array
    {
        if ($this->evaluate($this->mergeSuggestionActions)) {
            return [
                ...$this->getDefaultSuggestions(),
                ...$this->evaluate($this->suggestions),
            ];
        }

        return $this->evaluate($this->suggestions) ?? $this->getDefaultSuggestions();
    }

    /**
     * @return array{name: string, label: string, icon: string, actionType: ?string, commandName: ?string, commandAttributes: ?array}
     */
    public function getSuggestionsForTiptap(): array
    {
        return collect($this->getSuggestions())
            ->map(fn (RichieAction $suggestion): array => [
                'name' => $suggestion->getName() ?? 'group',
                'label' => $suggestion->getLabel(),
                'icon' => Blade::render("@svg('{$suggestion->getIcon()}', 'w-5 h-5')"),
                'actionType' => in_array($suggestion->getCommandName(), [null, '', '0'], true) ? null : 'alpine',
                'commandName' => $suggestion->getCommandName(),
                'commandAttributes' => $suggestion->getCommandAttributes(),
            ])->toArray();
    }

    /**
     * @return array<RichieAction>
     */
    public function getDefaultSuggestions(): array
    {
        return [
            Actions\Media::make('suggestionMedia'),
            Actions\Embed::make('suggestionEmbed'),
            Actions\BulletList::make('suggestionBulletList'),
            Actions\OrderedList::make('suggestionOrderedList'),
            Actions\Blockquote::make('suggestionBlockquote'),
            Actions\HorizontalRule::make('suggestionHorizontalRule'),
            Actions\CodeBlock::make('suggestionCodeBlock'),
            Actions\Details::make('suggestionDetails'),
            Actions\Grid::make('suggestionGrid'),
            Actions\Table::make('suggestionTable'),
        ];
    }
}
