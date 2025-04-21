<?php

namespace Awcodes\Richie\Tiptap\Extensions;

use Tiptap\Core\Extension;

class Ids extends Extension
{
    public static $name = 'idExtension';

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => [
                    'heading',
                    'link',
                ],
                'attributes' => [
                    'id' => [
                        'default' => null,
                        'parseHTML' => fn ($DOMNode) => $DOMNode->hasAttribute('id') ? $DOMNode->getAttribute('id') : null,
                        'renderHTML' => function ($attributes): ?array {
                            if (! property_exists($attributes, 'id')) {
                                return null;
                            }

                            return [
                                'id' => $attributes->id,
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
