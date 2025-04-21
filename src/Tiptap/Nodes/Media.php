<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Tiptap\Nodes\Image as BaseImage;

class Media extends BaseImage
{
    public static $name = 'media';

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
            'alignment' => [
                'default' => false,
                'renderHTML' => function ($attributes): array {
                    $style = match ($attributes->alignment) {
                        'center' => 'margin-inline: auto;',
                        'end' => 'margin-inline-start: auto;',
                        default => null,
                    };

                    return [
                        'style' => $style,
                    ];
                },
            ],
            'srcset' => [
                'default' => null,
            ],
            'sizes' => [
                'default' => null,
            ],
            'media' => [
                'default' => null,
                'parseHTML' => fn ($DOMNode) => $DOMNode->getAttribute('data-media-id') ?: null,
                'renderHTML' => function ($attributes): ?array {
                    if (! property_exists($attributes, 'media')) {
                        return null;
                    }

                    return ['data-media-id' => $attributes->media];
                },
            ],
        ];
    }
}
