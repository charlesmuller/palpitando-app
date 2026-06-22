<?php

namespace App\Filament\Admin\Resources\Guesses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GuessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('match_id')
                    ->relationship('match', 'id')
                    ->required(),
                Select::make('pool_id')
                    ->relationship('pool', 'name')
                    ->required(),
                TextInput::make('home_score_guess')
                    ->required()
                    ->numeric(),
                TextInput::make('away_score_guess')
                    ->required()
                    ->numeric(),
                TextInput::make('points_earned')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_calculated')
                    ->required(),
            ]);
    }
}
