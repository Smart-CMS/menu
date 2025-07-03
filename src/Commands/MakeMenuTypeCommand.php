<?php

namespace SmartCms\Menu\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeMenuTypeCommand extends GeneratorCommand
{
    protected $name = 'make:menu-type';

    protected $type = 'MenuType';

    protected $description = 'Make menu type for menu builder';

    protected function getStub()
    {
        return __DIR__ . '/../../stubs/menu-type.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\MenuTypes';
    }

    protected function getNameInput()
    {
        $name = parent::getNameInput();

        if (Str::endsWith($name, 'MenuType')) {
            return $name;
        }

        return $name . 'MenuType';
    }
}
