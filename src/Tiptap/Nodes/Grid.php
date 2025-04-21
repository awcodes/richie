<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class Grid extends Node
{
    public static $name = 'grid';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'richie-grid',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'data-type' => [
                'default' => 'symmetric',
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-type'),
            ],
            'data-columns' => [
                'default' => '2',
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-columns'),
                'renderHTML' => function ($attributes): array {
                    $attributes = (array) $attributes;

                    return [
                        'data-columns' => $attributes['data-columns'],
                        'style' => 'grid-template-columns: repeat(' . $attributes['data-columns'] . ', 1fr);',
                    ];
                },
            ],
            'data-stack-at' => [
                'default' => 'md',
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-stack-at'),
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => fn ($DOMNode): bool => str_contains((string) $DOMNode->getAttribute('class'), 'richie-grid')
                    && ! str_contains((string) $DOMNode->getAttribute('class'), '-column'),
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes),
            0,
        ];
    }
}
