<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieAction;

class Unlink extends RichieAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.link.remove'))
            ->icon(icon: 'richie-unlink')
            ->iconButton()
            ->alpineClickHandler(fn (): string => "editor().chain().extendMarkRange('link').unsetLink().selectTextblockEnd().run()");
    }
}
