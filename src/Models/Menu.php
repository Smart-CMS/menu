<?php

namespace SmartCms\Menu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use SmartCms\Menu\MenuRegistry;

/**
 * Class Menu
 *
 * @property int $id The unique identifier for the model.
 * @property string $name The name of the menu.
 * @property array|null $items The menu items configuration.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 */
class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('menu.menus_table_name');
    }

    /**
     * Get menu item title
     */
    public function getItemName(array $item): string
    {
        if (app('lang')->frontLanguages()->count() > 1) {
            return $item[current_lang()] ?? $item['title'] ?? 'Untitled';
        }

        return $item['title'] ?? 'Untitled';
    }

    public function links(): Attribute
    {
        return Attribute::make(
            get: function (): Collection {
                return collect($this->items)->map(function ($item) {
                    return (object) [
                        'title' => $this->getItemName($item),
                        'url' => app(MenuRegistry::class)->getLinkByType($item),
                    ];
                });
            }
        );
    }
}
