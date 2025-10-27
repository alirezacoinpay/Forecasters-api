<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\CategoryStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Select::make('icon')
                    ->label('Icon')
                    ->options([
                        'heroicon-o-academic-cap' => '<x-filament::icon icon="heroicon-o-academic-cap" class="w-5 h-5 text-primary-500" /> Education',
                        'heroicon-o-shopping-cart' => '<x-filament::icon icon="heroicon-o-shopping-cart" class="w-5 h-5 text-primary-500" /> Shopping',
                        'heroicon-o-film' => '<x-filament::icon icon="heroicon-o-film" class="w-5 h-5 text-primary-500" /> Movies',
                        'heroicon-o-heart' => '<x-filament::icon icon="heroicon-o-heart" class="w-5 h-5 text-primary-500" /> Health',
                        'heroicon-o-cog' => '<x-filament::icon icon="heroicon-o-cog" class="w-5 h-5 text-primary-500" /> Technology',
                    ])
                    ->allowHtml()
                    ->native(false)
                    ->required()
                ,
                Select::make('status')
                    ->options(CategoryStatus::toFormArray())
                    ->native(false)
                    ->required(),
            ]);
    }
}
