<?php

namespace App\Filament\Admin\Resources\Guesses\Pages;

use App\Filament\Admin\Resources\Guesses\GuessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuess extends CreateRecord
{
    protected static string $resource = GuessResource::class;
}
