<?php

namespace App\Filament\Admin\Resources\GameMatches\Pages;

use App\Filament\Admin\Resources\GameMatches\GameMatchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGameMatch extends EditRecord
{
    protected static string $resource = GameMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
