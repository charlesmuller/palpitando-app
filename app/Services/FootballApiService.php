<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * FootballApiService
 *
 * Integra com https://www.football-data.org/
 * Plano gratuito: 10 req/min, acesso à Copa do Mundo
 *
 * Registro gratuito em: https://www.football-data.org/client/register
 */
class FootballApiService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.football_api.base_url', 'https://api.football-data.org/v4');
        $this->apiKey  = config('services.football_api.key', '');
    }

    // -----------------------------------------------------------
    // Importa todos os jogos da Copa e salva no banco
    // -----------------------------------------------------------
    public function importWorldCupMatches(): array
    {
        $competitionId = config('services.football_api.world_cup_id', '2000');

        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
        ])->get("{$this->baseUrl}/competitions/{$competitionId}/matches");

        if (!$response->successful()) {
            Log::error('FootballAPI error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \Exception("Erro ao buscar jogos: HTTP {$response->status()}");
        }

        $data    = $response->json();
        $matches = $data['matches'] ?? [];

        $imported = 0;
        $updated  = 0;

        foreach ($matches as $matchData) {
            $result = $this->upsertMatch($matchData);
            $result === 'created' ? $imported++ : $updated++;
        }

        return [
            'imported' => $imported,
            'updated'  => $updated,
            'total'    => count($matches),
        ];
    }

    // -----------------------------------------------------------
    // Atualiza apenas os jogos em andamento / recentes
    // -----------------------------------------------------------
    public function syncLiveMatches(): void
    {
        $competitionId = config('services.football_api.world_cup_id', '2000');

        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
        ])->get("{$this->baseUrl}/competitions/{$competitionId}/matches", [
            'status' => 'LIVE,IN_PLAY,PAUSED,FINISHED',
        ]);

        if (!$response->successful()) return;

        $matches = $response->json()['matches'] ?? [];

        foreach ($matches as $matchData) {
            $this->upsertMatch($matchData);
        }
    }

    // -----------------------------------------------------------
    // Salva ou atualiza um jogo no banco
    // -----------------------------------------------------------
    private function upsertMatch(array $data): string
    {
        // Garante que os times existem
        $homeTeam = $this->upsertTeam($data['homeTeam']);
        $awayTeam = $this->upsertTeam($data['awayTeam']);

        // Garante que o grupo existe (se houver)
        $groupId = null;
        if (!empty($data['group'])) {
            $group = Group::firstOrCreate(
                ['name' => $data['group']],
                ['label' => $this->extractGroupLabel($data['group'])]
            );
            $groupId = $group->id;
        }

        $score = $data['score'] ?? [];
        $full  = $score['fullTime'] ?? [];
        $penalties = $score['penalties'] ?? [];

        $attributes = [
            'api_id'          => $data['id'],
        ];

        $values = [
            'competition_id'  => $data['competition']['id'] ?? '',
            'season'          => $data['season']['startDate'] ? substr($data['season']['startDate'], 0, 4) : '2026',
            'home_team_id'    => $homeTeam->id,
            'away_team_id'    => $awayTeam->id,
            'stage'           => $data['stage'] ?? 'GROUP_STAGE',
            'group_id'        => $groupId,
            'match_date'      => $data['utcDate'],
            'status'          => $data['status'] ?? 'SCHEDULED',
            'home_score'      => $full['home'] ?? null,
            'away_score'      => $full['away'] ?? null,
            'home_penalties'  => $penalties['home'] ?? null,
            'away_penalties'  => $penalties['away'] ?? null,
            'winner'          => $score['winner'] ?? null,
            'venue'           => $data['venue'] ?? null,
            'matchday'        => $data['matchday'] ?? null,
        ];

        $exists = Match::where('api_id', $data['id'])->exists();
        Match::updateOrCreate($attributes, $values);

        return $exists ? 'updated' : 'created';
    }

    private function upsertTeam(array $data): Team
    {
        return Team::updateOrCreate(
            ['api_id' => $data['id']],
            [
                'name'       => $data['name'] ?? 'TBD',
                'short_name' => $data['shortName'] ?? null,
                'tla'        => $data['tla'] ?? null,
                'crest_url'  => $data['crest'] ?? null,
            ]
        );
    }

    private function extractGroupLabel(string $groupName): string
    {
        // "GROUP_A" → "A"
        return str_replace('GROUP_', '', $groupName);
    }
}
