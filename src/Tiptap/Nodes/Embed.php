<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Core\Node;

class Embed extends Node
{
    public static $name = 'embed';

    public function addOptions(): array
    {
        return [
            'allowFullscreen' => true,
            'HTMLAttributes' => [
                'class' => 'richie-embed',
            ],
            'width' => 640,
            'height' => 480,
        ];
    }

    public function addAttributes(): array
    {
        return [
            'style' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('style'),
            ],
            'src' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('src'),
            ],
            'allowfullscreen' => [
                'default' => $this->options['allowFullscreen'],
                'parseHTML' => $this->options['allowFullscreen'],
            ],
            'width' => [
                'default' => $this->options['width'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('width'),
            ],
            'height' => [
                'default' => $this->options['height'],
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('height'),
            ],
            'responsive' => [
                'default' => true,
                'parseHTML' => fn ($DOMNode): bool => str_contains((string) $DOMNode->getAttribute('class'), 'responsive'),
            ],
            'data-aspect-width' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('width'),
            ],
            'data-aspect-height' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->firstChild->getAttribute('height'),
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'iframe',
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        return [
            'div',
            $this->options['HTMLAttributes'],
            [
                'iframe',
                [
                    'src' => $node->attrs->src,
                    'width' => $node->attrs->width ?? $this->options['width'],
                    'height' => $node->attrs->height ?? $this->options['height'],
                    'allowfullscreen' => $node->attrs->allowfullscreen,
                    'allow' => 'autoplay; fullscreen; picture-in-picture',
                    'style' => $node->attrs->responsive
                        ? "aspect-ratio:{$node->attrs->width}/{$node->attrs->height}; width: 100%; height: auto;"
                        : null,
                ],
            ],
        ];
    }
}
