<?php

namespace SmartCms\Menu\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SmartCms\Menu\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'items' => [],
        ];
    }
}
