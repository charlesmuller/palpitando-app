#!/bin/bash
# =============================================================
# setup.sh - Configuração inicial do ambiente de desenvolvimento
# Uso: chmod +x setup.sh && ./setup.sh
# =============================================================

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}======================================${NC}"
echo -e "${GREEN}   PALPITANDO - Setup Inicial         ${NC}"
echo -e "${GREEN}======================================${NC}"

# 1. Copia .env se não existir
if [ ! -f .env ]; then
    echo -e "${YELLOW}→ Criando .env a partir do .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ .env criado${NC}"
else
    echo -e "${GREEN}✓ .env já existe${NC}"
fi

# 2. Sobe os containers
echo -e "${YELLOW}→ Subindo containers Docker...${NC}"
docker compose up -d --build

# 3. Aguarda MySQL ficar pronto
echo -e "${YELLOW}→ Aguardando MySQL inicializar...${NC}"
until docker compose exec mysql mysqladmin ping -h localhost --silent; do
    printf '.'
    sleep 2
done
echo ""
echo -e "${GREEN}✓ MySQL pronto${NC}"

# 4. Instala dependências PHP
echo -e "${YELLOW}→ Instalando dependências Composer...${NC}"
docker compose exec app composer install

# 5. Gera APP_KEY
echo -e "${YELLOW}→ Gerando APP_KEY...${NC}"
docker compose exec app php artisan key:generate

# 6. Instala dependências Node
echo -e "${YELLOW}→ Instalando dependências NPM...${NC}"
docker compose exec app npm install

# 7. Roda migrations
echo -e "${YELLOW}→ Rodando migrations...${NC}"
docker compose exec app php artisan migrate --force

# 8. Instala Filament
echo -e "${YELLOW}→ Configurando Filament...${NC}"
docker compose exec app php artisan filament:install --panels

# 9. Link de storage
echo -e "${YELLOW}→ Criando link de storage...${NC}"
docker compose exec app php artisan storage:link

# 10. Cria usuário admin
echo -e "${YELLOW}→ Criando usuário admin...${NC}"
docker compose exec app php artisan make:filament-user

echo ""
echo -e "${GREEN}======================================${NC}"
echo -e "${GREEN}   Setup concluído! 🎉               ${NC}"
echo -e "${GREEN}======================================${NC}"
echo ""
echo -e "Aplicação:   ${GREEN}http://localhost${NC}"
echo -e "Admin:       ${GREEN}http://localhost/admin${NC}"
echo -e "PHPMyAdmin:  ${GREEN}http://localhost:8080${NC}"
echo ""
echo -e "${YELLOW}Próximos passos:${NC}"
echo -e "  1. Configure FOOTBALL_API_KEY no .env"
echo -e "  2. Rode: docker compose exec app php artisan copa:import-matches"
echo ""
