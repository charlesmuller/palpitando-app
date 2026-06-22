<?php

namespace App\Filament\Admin\Resources\GameMatches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GameMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_id')
                    ->required()
                    ->numeric(),
                TextInput::make('competition_id')
                    ->required(),
                TextInput::make('season')
                    ->required(),
                Select::make('home_team_id')
                    ->relationship('homeTeam', 'name')
                    ->required(),
                Select::make('away_team_id')
                    ->relationship('awayTeam', 'name')
                    ->required(),
                TextInput::make('stage')
                    ->required(),
                Select::make('group_id')
                    ->relationship('group', 'name'),
                DateTimePicker::make('match_date')
                    ->required(),
                Select::make('status')
                    ->options([
            'SCHEDULED' => 'S c h e d u l e d',
            'LIVE' => 'L i v e',
            'IN_PLAY' => 'I n  p l a y',
            'PAUSED' => 'P a u s e d',
            'FINISHED' => 'F i n i s h e d',
            'POSTPONED' => 'P o s t p o n e d',
            'CANCELLED' => 'C a n c e l l e d',
        ])
                    ->default('SCHEDULED')
                    ->required(),
                TextInput::make('home_score')
                    ->numeric(),
                TextInput::make('away_score')
                    ->numeric(),
                TextInput::make('home_penalties')
                    ->numeric(),
                TextInput::make('away_penalties')
                    ->numeric(),
                Select::make('winner')
                    ->options(['HOME_TEAM' => 'H o m e  t e a m', 'AWAY_TEAM' => 'A w a y  t e a m', 'DRAW' => 'D r a w']),
                TextInput::make('venue'),
                TextInput::make('matchday')
                    ->numeric(),
            ]);
    }
}
