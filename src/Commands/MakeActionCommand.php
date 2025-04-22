<?php

namespace Awcodes\Richie\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class MakeActionCommand extends Command
{
    use CanManipulateFiles;

    public $signature = 'make:richie-action {name?} {--F|force}';

    public $description = 'Scaffold a new Richie action.';

    public function handle(): int
    {
        $action = (string) Str::of(
            $this->argument('name') ??
            text(
                label: 'What is the action name?',
                placeholder: 'CustomRichieAction',
                required: true,
            )
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        $namespace = config('richie.generator.namespace');
        $viewsPath = config('richie.generator.views_path');

        $className = (string) Str::of($action)->afterLast('\\');
        $actionNamespace = Str::of($action)->contains('\\')
            ? (string) Str::of($action)->beforeLast('\\')
            : '';

        $fullNamespace = $actionNamespace
            ? "{$namespace}\\{$actionNamespace}\\{$className}"
            : "{$namespace}\\{$className}";

        $actionLabel = (string) Str::of($className)
            ->afterLast('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();

        $actionName = Str::of($action)
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $view = Str::of($action)
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $classPath = app_path(
            (string) Str::of($action)
                ->prepend($namespace . '\\')
                ->replace('\\', '/')
                ->replace('//', '/')
                ->replace('App', '')
                ->append('.php')
        );

        $viewPath = resource_path(
            (string) Str::of($view)
                ->prepend($viewsPath . '/')
                ->prepend('views/')
                ->replace('.', '/')
                ->replace('//', '/')
                ->append('.blade.php')
        );

        $files = [$classPath, $viewPath];

        if (! $this->option('force') && $this->checkForCollision($files)) {
            return static::INVALID;
        }

        File::ensureDirectoryExists(dirname($classPath));
        File::ensureDirectoryExists(dirname($viewPath));

        $this->copyStubToApp('action-class', $classPath, [
            'namespace' => $actionNamespace
                ? $namespace . '\\' . $actionNamespace
                : $namespace,
            'class_name' => $className,
            'action_label' => $actionLabel,
            'path' => $viewsPath . '.' . $view,
        ]);

        $this->copyStubToApp('action-view', $viewPath);

        $this->components->info("Richie action [{$fullNamespace}] created successfully.");

        return self::SUCCESS;
    }
}
