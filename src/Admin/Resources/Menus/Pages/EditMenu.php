<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use SmartCms\Menu\Admin\Resources\Menus\MenuResource;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            SaveAndClose::make($this, ListMenus::getUrl()),
            SaveAction::make($this),
        ];
    }
}
