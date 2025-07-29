<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use SmartCms\Menu\MenuRegistry;
use SmartCms\Support\Admin\Components\Forms\StatusField;
use SmartCms\Support\Admin\Components\Layout\Aside;
use SmartCms\Support\Admin\Components\Layout\FormGrid;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;
use SmartCms\Support\Admin\Components\Layout\RightGrid;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormGrid::make()->schema([
                    LeftGrid::make()->schema([
                        Section::make()->schema([
                            TextInput::make('name'),
                        ]),
                        Section::make('Items')->compact()->schema([
                            Repeater::make('items')
                                ->label('Menu Items')
                                ->hiddenLabel()
                                ->schema([
                                    ...app('lang')->adminLanguages()->map(function ($lang) {
                                        return Hidden::make($lang->slug);
                                    })->toArray(),
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required()
                                        ->placeholder('Menu item title')
                                        ->suffixAction(
                                            Action::make('translate')
                                                ->label(__('support::admin.translate'))
                                                ->icon('heroicon-o-language')
                                                ->schema(function (Schema $form) {
                                                    return $form->schema(
                                                        app('lang')
                                                            ->adminLanguages()
                                                            ->map(function ($lang) {
                                                                return TextInput::make($lang->slug)->label($lang->name);
                                                            })
                                                            ->toArray()
                                                    );
                                                })
                                                ->fillForm(function (Get $get, $form) {
                                                    return app('lang')->adminLanguages()->mapWithKeys(function ($lang) use ($get) {
                                                        return [$lang->slug => $get($lang->slug) ?? null];
                                                    })->toArray();
                                                })
                                                ->action(function (array $data, Set $set) {
                                                    foreach ($data as $lang => $value) {
                                                        $set($lang, $value);
                                                    }
                                                })
                                        )
                                        ->live(),
                                    Select::make('type')
                                        ->label(__('menu::admin.type'))
                                        ->options(app(MenuRegistry::class)->all())
                                        ->reactive()
                                        ->required(),
                                    Flex::make(function (Get $get) {
                                        $type = $get('type');
                                        if (! $type) {
                                            return [];
                                        }
                                        $field = app(MenuRegistry::class)->getSchemaByType($type);
                                        if (! $field) {
                                            return [];
                                        }

                                        return [$field];
                                    }),
                                    Flex::make([
                                        StatusField::make('status')->inline(false),
                                        Toggle::make('open_in_new_tab')
                                            ->label(__('menu::admin.open_in_new_tab'))
                                            ->inline(false)
                                            ->default(false),
                                    ]),
                                ])
                                ->columns(2)
                                ->defaultItems(0)
                                ->reorderableWithButtons()
                                ->collapsible(),
                        ]),
                    ]),
                    RightGrid::make()->schema([
                        Aside::make(false),
                    ])->hiddenOn('create'),
                ]),
            ]);
    }
}
