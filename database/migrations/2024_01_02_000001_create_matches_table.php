<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ------------------------------------------------------------
// Estrutura baseada na API football-data.org v4
// Endpoint: GET /v4/competitions/{id}/matches
// Os campos refletem exatamente o que a API retorna
// para facilitar o import/sync automático
// ------------------------------------------------------------

return new class extends Migration
{
    public function up(): void
    {
        // Seleções/Países participantes
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique(); // ID da football-data.org
            $table->string('name');            // "Brazil"
            $table->string('short_name', 10)->nullable(); // "BRA"
            $table->string('tla', 5)->nullable();         // código 3 letras: "BRA"
            $table->string('crest_url')->nullable();      // URL do escudo
            $table->timestamps();
        });

        // Grupos da fase de grupos
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);        // "GROUP_A", "GROUP_B" ...
            $table->string('label', 5);        // "A", "B" ...
            $table->timestamps();
        });

        // Jogos
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique(); // ID da football-data.org
            $table->string('competition_id');               // ID da competição na API
            $table->string('season');                       // "2026"

            // Times
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');

            // Fase e grupo
            $table->string('stage');           // "GROUP_STAGE", "ROUND_OF_16", "QUARTER_FINALS", "SEMI_FINALS", "FINAL"
            $table->foreignId('group_id')->nullable()->constrained('groups'); // null para mata-mata

            // Data e status
            $table->dateTime('match_date');    // Data/hora UTC do jogo
            $table->enum('status', [
                'SCHEDULED',   // Agendado
                'LIVE',        // Ao vivo
                'IN_PLAY',     // Em andamento
                'PAUSED',      // Intervalo
                'FINISHED',    // Finalizado
                'POSTPONED',   // Adiado
                'CANCELLED',   // Cancelado
            ])->default('SCHEDULED');

            // Placar (null enquanto não jogou)
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();

            // Pênaltis (para mata-mata)
            $table->unsignedTinyInteger('home_penalties')->nullable();
            $table->unsignedTinyInteger('away_penalties')->nullable();

            // Vencedor
            $table->enum('winner', ['HOME_TEAM', 'AWAY_TEAM', 'DRAW'])->nullable();

            // Estádio (opcional, vem da API)
            $table->string('venue')->nullable();

            // Rodada (fase de grupos: 1,2,3 / mata-mata: null)
            $table->unsignedTinyInteger('matchday')->nullable();

            $table->timestamps();

            // Índices para queries frequentes
            $table->index('match_date');
            $table->index('status');
            $table->index('stage');
            $table->index(['home_team_id', 'away_team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('teams');
    }
};
