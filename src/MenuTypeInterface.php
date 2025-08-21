<?php

namespace SmartCms\Menu;

use Filament\Forms\Components\Field;

interface MenuTypeInterface
{
    public function getType(): string;

    public function getLabel(): string;

    public function getSchema(): Field;

    public function getLinkFromItem(mixed $item): string | array;
}
