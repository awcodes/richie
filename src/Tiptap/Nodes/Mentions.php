<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Mentions extends Node
{
    public static $name = 'mentions';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [],
            'renderLabel' => fn ($node): string => '@' . $node->attrs->id,
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'span[data-type="' . self::$name . '"]',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'id' => [
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-id') ?: null,
                'renderHTML' => fn ($attributes): array => ['data-id' => $attributes->id ?? null],
            ],
        ];
    }

    public function renderText($node)
    {
        return $this->options['renderLabel']($node);
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'span',
            HTML::mergeAttributes(
                ['data-type' => self::$name],
                $this->options['HTMLAttributes'],
                $HTMLAttributes,
            ),
            0,
        ];
    }
}
