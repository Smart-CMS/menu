<?php

namespace SmartCms\Menu;

use Filament\Forms\Components\Field;
use SmartCms\Menu\MenuTypes\LinkMenuType;

class MenuRegistry
{
    protected array $menus = [];

    public function __construct()
    {
        $this->register(new LinkMenuType);
    }

    public function register(MenuTypeInterface $class): void
    {
        $this->menus[$class->getType()] = $class;
    }

    public function get(string $type): ?MenuTypeInterface
    {
        return $this->menus[$type] ?? null;
    }

    public function all(): array
    {
        return collect($this->menus)->mapWithKeys(fn (MenuTypeInterface $menu) => [$menu->getType() => $menu->getLabel()])->toArray();
    }

    public function getSchemaByType(string $type): ?Field
    {
        $schema = $this->get($type)?->getSchema();
        if (! $schema) {
            return null;
        }
        if (method_exists($schema, 'required')) {
            $schema->required();
        }

        return $schema->columnSpanFull();
    }

    public function getLinkByType(array $item): ?string
    {
        return $this->get($item['type'])?->getLinkFromItem($item);
    }
}
