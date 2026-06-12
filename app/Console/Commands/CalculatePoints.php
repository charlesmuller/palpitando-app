<?php

namespace App\Console\Commands;

use App\Models\Guess;
use App\Models\Match;
use App\Models\PoolMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculatePoints extends Command
{
    protected $signature   = 'copa:calculate-points {--match= : ID específico de um jogo}';
    protected $description = 'Calcula pontos dos palpites para jogos finalizados';

    public function handle(): int
    {
        $query = Match::where('status', 'FINISHED');

        if ($matchId = $this->option('match')) {
            $query->where('id', $matchId);
        }

        $matches = $query->get();

        if ($matches->isEmpty()) {
            $this->info('Nenhum jogo finalizado encontrado.');
            return Command::SUCCESS;
        }

        $totalCalculated = 0;

        foreach ($matches as $match) {
            $pendingGuesses = Guess::where('match_id', $match->id)
                                   ->where('is_calculated', false)
                                   ->get();

            if ($pendingGuesses->isEmpty()) continue;

            DB::transaction(function () use ($pendingGuesses, $match, &$totalCalculated) {
                foreach ($pendingGuesses as $guess) {
                    $points = $guess->calculatePoints();

                    $guess->update([
                        'points_earned' => $points,
                        'is_calculated' => true,
                    ]);

                    // Atualiza total de pontos no bolão
                    DB::table('pool_members')
                        ->where('pool_id', $guess->pool_id)
                        ->where('user_id', $guess->user_id)
                        ->increment('total_points', $points);

                    $totalCalculated++;
                }
            });

            $this->line("✓ Jogo #{$match->id}: {$pendingGuesses->count()} palpites calculados");
        }

        $this->info("✅ Total: {$totalCalculated} palpites calculados.");
        return Command::SUCCESS;
    }
}
