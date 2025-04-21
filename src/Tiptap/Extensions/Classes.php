<?php

namespace Awcodes\Richie\Tiptap\Extensions;

use Tiptap\Core\Extension;

class Classes extends Extension
{
    public static $name = 'classExtension';

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => [
                    'heading',
                    'paragraph',
                    'link',
                    'image',
                    'listItem',
                    'bulletList',
                    'orderedList',
                    'table',
                    'tableHeader',
                    'tableRow',
                    'tableCell',
                    'textStyle',
                    'code',
                    'codeBlock',
                ],
                'attributes' => [
                    'class' => [
                        'default' => null,
                        'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('class') ? $DOMNode->getAttribute('class') : null,
                        'renderHTML' => function ($attributes): ?array {
                            if (! property_exists($attributes, 'class')) {
                                return null;
                            }

                            return [
                                'class' => $attributes->class,
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
