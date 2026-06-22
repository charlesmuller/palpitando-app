<?php

namespace App\Filament\Admin\Resources\Guesses;

use App\Filament\Admin\Resources\Guesses\Pages\CreateGuess;
use App\Filament\Admin\Resources\Guesses\Pages\EditGuess;
use App\Filament\Admin\Resources\Guesses\Pages\ListGuesses;
use App\Filament\Admin\Resources\Guesses\Schemas\GuessForm;
use App\Filament\Admin\Resources\Guesses\Tables\GuessesTable;
use App\Models\Guess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuessResource extends Resource
{
    protected static ?string $model = Guess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GuessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuessesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuesses::route('/'),
            'create' => CreateGuess::route('/create'),
            'edit' => EditGuess::route('/{record}/edit'),
        ];
    }
}
