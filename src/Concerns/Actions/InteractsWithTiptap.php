<?php

namespace Awcodes\Richie\Concerns\Actions;

use Illuminate\Support\Js;
use JsonException;
use Tiptap\Core\Extension;
use Tiptap\Core\Mark;
use Tiptap\Core\Node;

trait InteractsWithTiptap
{
    protected ?string $active = null;

    protected ?string $commandName = null;

    protected string | array | bool | null $commandAttributes = null;

    protected ?string $jsExtension = null;

    protected Extension | Node | Mark | array | null $converterExtensions = null;

    /**
     * @throws JsonException
     */
    public function active(?string $name = null, string | array $attributes = []): static
    {
        if ($name && filled($attributes)) {
            $string = "'{$name}', " . Js::from($attributes);
        } elseif (! $name && filled($attributes)) {
            $string = Js::from($attributes);
        } elseif ($name && empty($attributes)) {
            $string = "'{$name}'";
        } else {
            $string = null;
        }

        $this->active = $string;

        return $this;
    }

    public function getActive(): ?string
    {
        return $this->active ?? null;
    }

    public function command(?string $name, string | array | bool | null $attributes = null): static
    {
        $this->commandName = $name;
        $this->commandAttributes = $attributes;

        return $this;
    }

    public function getCommandName(): ?string
    {
        return $this->evaluate($this->commandName) ?? null;
    }

    public function getCommandAttributes(): string | array | bool | null
    {
        return $this->evaluate($this->commandAttributes) ?? null;
    }

    public function hasTiptapCommands(): bool
    {
        return $this->evaluate($this->commandName) || $this->evaluate($this->commandAttributes);
    }

    public function jsExtension(string $extension): static
    {
        $this->jsExtension = $extension;

        return $this;
    }

    public function getJsExtension(): string
    {
        return $this->evaluate($this->jsExtension) ?? $this->getName();
    }

    /**
     * @param  Extension|Node|Mark|array<Extension|Node|Mark>  $extensions
     */
    public function converterExtensions(Extension | Node | Mark | array $extensions): static
    {
        $this->converterExtensions = $extensions;

        return $this;
    }

    public function getConverterExtensions(): Extension | Node | Mark | array | null
    {
        return $this->converterExtensions ?? null;
    }
}
