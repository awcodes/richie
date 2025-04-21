<?php

namespace Awcodes\Richie;

use Awcodes\Richie\Testing\TestsRichie;
use BladeUI\Icons\Exceptions\CannotRegisterIconSet;
use BladeUI\Icons\Factory;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Livewire\Features\SupportTesting\Testable;
use ReflectionException;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RichieServiceProvider extends PackageServiceProvider
{
    public static string $name = 'richie';

    public static string $viewNamespace = 'richie';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('awcodes/richie');
            });
    }

    /**
     * @throws BindingResolutionException
     * @throws CannotRegisterIconSet
     * @throws BindingResolutionException
     * @throws CannotRegisterIconSet
     */
    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/richie-icons.php', 'richie-icons');

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container): void {
            $config = $container->make('config')->get('richie-icons', []);

            $factory->add('richie', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });

        $this->app->singleton(RichieManager::class, fn (): \Awcodes\Richie\RichieManager => new RichieManager);
    }

    /**
     * @throws ReflectionException
     */
    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament/richie/{$file->getFilename()}"),
                ], 'richie-stubs');
            }
        }

        app(RichieManager::class)
            ->registerActionPath(
                in: __DIR__ . '/Actions',
                for: 'Awcodes\\Richie\\Actions'
            );

        Blade::directive('richie', fn ($expression): string => "<?php echo richie({$expression})->toHtml(); ?>");

        Testable::mixin(new TestsRichie);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'awcodes/richie';
    }

    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('richie', __DIR__ . '/../resources/dist/richie.js'),
            Js::make('richie-lifecycle', __DIR__ . '/../resources/dist/richie-lifecycle.js'),
        ];
    }
}
