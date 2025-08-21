<?php

namespace SmartCms\Menu\MenuTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use SmartCms\Menu\MenuTypeInterface;
use SmartCms\Menu\Models\Menu;

class NestedMenuType implements MenuTypeInterface
{
    public function getType(): string
    {
        return 'nested';
    }

    public function getLabel(): string
    {
        return __('menu::admin.nested_menu');
    }

    public function getSchema(): Field
    {
        return Select::make('menu_id')
            ->label(__('menu::admin.select_menu'))
            ->options(Menu::all()->pluck('name', 'id'))
            ->searchable()
            ->preload()
            ->required();
    }

    public function getLinkFromItem(mixed $item): string|array
    {
        if (! isset($item['menu_id'])) {
            return '#';
        }

        $nestedMenu = Menu::find($item['menu_id']);
        if (! $nestedMenu) {
            return '#';
        }
        return [
            'url' => '',
            'children' => $nestedMenu->links,
        ];
    }
}
