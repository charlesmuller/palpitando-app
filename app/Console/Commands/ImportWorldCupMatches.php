<?php

namespace App\Console\Commands;

use App\Services\FootballApiService;
use Illuminate\Console\Command;

class ImportWorldCupMatches extends Command
{
    protected $signature   = 'copa:import-matches {--sync : Sincroniza apenas jogos ao vivo/recentes}';
    protected $description = 'Importa os jogos da Copa do Mundo da API football-data.org';

    public function handle(FootballApiService $service): int
    {
        if ($this->option('sync')) {
            $this->info('Sincronizando jogos ao vivo...');
            $service->syncLiveMatches();
            $this->info('Sincronização concluída!');
            return Command::SUCCESS;
        }

        $this->info('Importando todos os jogos da Copa do Mundo...');

        try {
            $result = $service->importWorldCupMatches();

            $this->table(
                ['Importados', 'Atualizados', 'Total'],
                [[$result['imported'], $result['updated'], $result['total']]]
            );

            $this->info('✅ Importação concluída com sucesso!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
