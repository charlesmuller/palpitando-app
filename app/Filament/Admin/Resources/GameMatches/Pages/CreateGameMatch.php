<?php

namespace App\Filament\Admin\Resources\GameMatches\Pages;

use App\Filament\Admin\Resources\GameMatches\GameMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGameMatch extends CreateRecord
{
    protected static string $resource = GameMatchResource::class;
}
