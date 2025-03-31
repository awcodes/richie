<?php

namespace Awcodes\Richie\Actions;

use Awcodes\Richie\RichieEditor;
use Exception;
use Illuminate\Support\Str;

class EditMedia extends Media
{
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn () => trans('richie::richie.media.edit'))
            ->active(null)
            ->fillForm(function (RichieEditor $component, array $arguments) {
                $source = isset($arguments['src']) && $arguments['src'] !== ''
                    ? $component->getUploader()->getDirectory() . Str::of($arguments['src'])
                        ->after($component->getUploader()->getDirectory())
                    : null;

                $arguments['src'] = $source;

                return $arguments;
            });
    }
}
