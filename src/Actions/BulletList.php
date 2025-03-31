<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;
use Tiptap\Nodes\BulletList as BulletListExtension;

class BulletList extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.bullet_list'))
            ->icon(icon: 'richie-list-unordered')
            ->iconButton()
            ->command(name: 'toggleBulletList')
            ->active(name: 'bulletList')
            ->converterExtensions(new BulletListExtension);
    }
}
