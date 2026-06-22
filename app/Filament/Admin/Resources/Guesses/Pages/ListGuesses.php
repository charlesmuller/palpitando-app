<?php

namespace App\Filament\Admin\Resources\Guesses\Pages;

use App\Filament\Admin\Resources\Guesses\GuessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuesses extends ListRecords
{
    protected static string $resource = GuessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
