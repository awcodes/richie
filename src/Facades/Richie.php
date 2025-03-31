<?php

namespace Awcodes\Richie\Facades;

use Awcodes\Richie\RichieManager;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getActions()
 * @method static Action | null getAction(string $name)
 * @method static static registerActionPath(string $in, string $for)
 *
 * @see RichieManager
 */
class Richie extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RichieManager::class;
    }
}
