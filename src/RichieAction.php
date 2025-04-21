<?php

namespace Awcodes\Richie;

use Exception;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Js;

class RichieAction extends Action
{
    use Concerns\Actions\InteractsWithTiptap;
    use HasExtraAlpineAttributes;

    protected ?string $renderView = null;

    protected ?string $editorView = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray');
    }

    public function renderView(string $view): static
    {
        $this->renderView = $view;

        return $this;
    }

    public function getRenderView(array $data = []): ?string
    {
        return $this->evaluate($this->renderView)
            ? view($this->renderView, $data)->toHtml()
            : null;
    }

    public function getExtraAttributes(): array
    {
        return array_merge(
            parent::getExtraAttributes(),
            in_array($this->getActive(), [null, '', '0'], true) ? [] : ['x-bind:class' => '{ \'is-active\': isActive(' . $this->getActive() . ', updatedAt)}'],
        );
    }

    public function editorView(string $view): static
    {
        $this->editorView = $view;

        return $this;
    }

    public function getEditorView(array $data = []): ?string
    {
        return $this->evaluate($this->editorView)
            ? view($this->editorView, $data)->toHtml()
            : null;
    }

    public function getLivewireClickHandler(): ?string
    {
        return null;
    }

    /**
     * @throws Exception
     */
    public function getAlpineClickHandler(): ?string
    {
        if ($this->evaluate($this->alpineClickHandler)) {
            return parent::getAlpineClickHandler();
        }

        if ($this->hasTiptapCommands()) {
            $attributes = $this->getCommandAttributes();

            $attributes = $attributes && is_array($attributes) ? Js::from($attributes) : '"' . $attributes . '"';

            $handler = 'handleCommand("' . $this->getCommandName() . '", ' . $attributes . ')';

            if ($this->shouldClose()) {
                $handler .= '; close();';
            }

            return $handler;
        }

        return '$wire.mountFormComponentAction(\'' . $this->getComponent()->getStatePath() . '\', \'' . $this->getName() . '\', { ...editor().getAttributes(\'' . lcfirst($this->getJsExtension()) . '\'), editorSelection }, ' . Js::from(['schemaComponent' => $this->getComponent()->getKey()]) . ')';
    }
}
