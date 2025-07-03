<?php

namespace SmartCms\Menu;

use Livewire\Features\SupportTesting\Testable;
use SmartCms\Menu\Testing\TestsMenu;
use SmartCms\Menu\Commands\MakeMenuTypeCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MenuServiceProvider extends PackageServiceProvider
{
    public static string $name = 'menu';

    public static string $viewNamespace = 'menu';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasCommand(MakeMenuTypeCommand::class)
            ->hasMigration('create_menus_table')
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('smart-cms/menu');
            });
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MenuRegistry::class, function ($app) {
            return new MenuRegistry;
        });
    }

    public function packageBooted(): void
    {
        Testable::mixin(new TestsMenu);
    }
}
