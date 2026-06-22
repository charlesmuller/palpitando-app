<?php

namespace App\Filament\Admin\Resources\GameMatches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GameMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('api_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('competition_id')
                    ->searchable(),
                TextColumn::make('season')
                    ->searchable(),
                TextColumn::make('homeTeam.name')
                    ->searchable(),
                TextColumn::make('awayTeam.name')
                    ->searchable(),
                TextColumn::make('stage')
                    ->searchable(),
                TextColumn::make('group.name')
                    ->searchable(),
                TextColumn::make('match_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('home_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('away_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('home_penalties')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('away_penalties')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('winner')
                    ->badge(),
                TextColumn::make('venue')
                    ->searchable(),
                TextColumn::make('matchday')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
