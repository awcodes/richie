<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class GridColumn extends Node
{
    public static $name = 'gridColumn';

    public function addOptions(): array
    {
        return [
            'HTMLAttributes' => [
                'class' => 'richie-grid-column',
            ],
        ];
    }

    public function addAttributes(): array
    {
        return [
            'data-col-span' => [
                'default' => '1',
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-col-span'),
                'renderHTML' => function ($attributes): array {
                    $attributes = (array) $attributes;

                    return [
                        'data-col-span' => $attributes['data-col-span'],
                        'style' => 'grid-column: span ' . $attributes['data-col-span'] . ';',
                    ];
                },
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => fn ($DOMNode): bool => str_contains((string) $DOMNode->getAttribute('class'), 'richie-grid-column'),
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
