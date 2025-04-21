<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Nodes\Image as BaseImage;

class Image extends BaseImage
{
    public function addAttributes(): array
    {
        return [
            'src' => [
                'default' => null,
            ],
            'alt' => [
                'default' => null,
            ],
            'title' => [
                'default' => null,
            ],
            'width' => [
                'default' => null,
            ],
            'height' => [
                'default' => null,
            ],
            'lazy' => [
                'default' => false,
                'parseHTML' => fn ($DOMNode): bool => $DOMNode->hasAttribute('loading') && $DOMNode->getAttribute('loading') === 'lazy',
                'renderHTML' => fn ($attributes): ?array => $attributes->lazy
                    ? ['loading' => 'lazy']
                    : null,
            ],
        ];
    }
}
