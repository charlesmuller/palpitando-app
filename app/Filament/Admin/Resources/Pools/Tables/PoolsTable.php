<?php

namespace App\Filament\Admin\Resources\Pools\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PoolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->searchable(),
                IconColumn::make('is_public')
                    ->boolean(),
                TextColumn::make('invite_code')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('points_exact_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('points_winner')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('points_draw_hit')
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
