<?php

namespace Awcodes\Richie\Tiptap\Extensions;

use Tiptap\Core\Extension;
use Tiptap\Utils\InlineStyle;

class TextAlign extends Extension
{
    public static $name = 'textAlign';

    public function addOptions(): array
    {
        return [
            'types' => [],
            'alignments' => ['start', 'center', 'end', 'justify'],
            'defaultAlignment' => 'start',
        ];
    }

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => $this->options['types'],
                'attributes' => [
                    'textAlign' => [
                        'default' => $this->options['defaultAlignment'],
                        'parseHTML' => fn ($DOMNode) => InlineStyle::getAttribute($DOMNode, 'text-align') ?? $this->options['defaultAlignment'],
                        'renderHTML' => function ($attributes): ?array {
                            if (
                                property_exists($attributes, 'style') && str_contains((string) $attributes->style, 'text-align')
                                || $attributes->textAlign === $this->options['defaultAlignment']
                            ) {
                                return null;
                            }

                            return ['style' => "text-align: {$attributes->textAlign};"];
                        },
                    ],
                ],
            ],
        ];
    }
}
