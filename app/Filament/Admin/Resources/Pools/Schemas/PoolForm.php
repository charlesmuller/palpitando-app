<?php

namespace App\Filament\Admin\Resources\Pools\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PoolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->required(),
                Toggle::make('is_public')
                    ->required(),
                TextInput::make('invite_code'),
                Select::make('status')
                    ->options(['open' => 'Open', 'closed' => 'Closed', 'finished' => 'Finished'])
                    ->default('open')
                    ->required(),
                TextInput::make('points_exact_score')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('points_winner')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('points_draw_hit')
                    ->required()
                    ->numeric()
                    ->default(7),
            ]);
    }
}
