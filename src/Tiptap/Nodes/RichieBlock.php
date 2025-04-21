<?php

namespace Awcodes\Richie\Tiptap\Nodes;

use Awcodes\Richie\Facades\Richie;
use Tiptap\Core\Node;

class RichieBlock extends Node
{
    public static $name = 'richieBlock';

    public function addAttributes(): array
    {
        return [
            'identifier' => [
                'default' => null,
            ],
            'values' => [
                'default' => [],
            ],
            'view' => [
                'default' => null,
            ],
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'richie-block',
                'getAttrs' => fn ($DOMNode): mixed => json_decode((string) $DOMNode->nodeValue, true),
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = []): array
    {
        $data = $HTMLAttributes;
        $view = null;

        if ($data) {
            foreach (Richie::getActions() as $action) {
                if ($action->getName() === $data['identifier']) {
                    $view = $action->getRenderView((array) $data['values']);
                }
            }
        }

        return [
            'content' => '<div class="richie-block">' . $view . '</div>',
        ];
    }
}
