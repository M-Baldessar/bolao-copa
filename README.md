# Bolão Copa 2026

Sistema de bolão para a Copa do Mundo 2026, desenvolvido em Laravel 12. Permite que usuários façam palpites nos jogos, acompanhem a classificação dos grupos e disputem pontos em grupos privados.

## Acesso

**Produção:** https://bolao.matheusbaldessar.tech

## Funcionalidades

- Cadastro e autenticação de usuários
- Palpites nos jogos da fase de grupos e eliminatórias
- Palpite no campeão e vice-campeão
- Grupos privados de bolão com ranking entre participantes
- Classificação dos grupos atualizada automaticamente via API
- Sincronização automática de resultados após cada partida
- Painel administrativo para gerenciar jogos e resultados
- Recuperação de senha por e-mail
- Suporte a tema claro e escuro

## Tecnologias

- **Backend:** PHP 8.4, Laravel 12
- **Frontend:** Blade, Tailwind CSS 4, Vite
- **Banco de dados:** MySQL
- **API de dados:** football-data.org
- **Servidor:** VPS Hostinger com CloudPanel + Nginx

## Instalação local

### Pré-requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL ou MySQL

### Passo a passo

```bash
# 1. Clonar o repositório
git clone https://github.com/M-Baldessar/bolao-copa.git
cd bolao-copa

# 2. Instalar dependências
composer install
npm install

# 3. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Configurar o banco no .env
# DB_CONNECTION=pgsql (ou mysql)
# DB_DATABASE=bolao_copa
# DB_USERNAME=seu_usuario
# DB_PASSWORD=sua_senha

# 5. Rodar migrations e seeders
php artisan migrate
php artisan db:seed --class=GroupSeeder
php artisan db:seed --class=TeamSeeder

# 6. Importar partidas da API
php artisan matches:sync-schedule

# 7. Compilar assets e iniciar servidor
npm run dev
php artisan serve
```

## Variáveis de ambiente

| Variável | Descrição |
|---|---|
| `APP_URL` | URL da aplicação |
| `DB_CONNECTION` | Driver do banco (`mysql` ou `pgsql`) |
| `FOOTBALL_DATA_TOKEN` | Token da API football-data.org |
| `DEPLOY_SECRET` | Senha do webhook de deploy automático |
| `MAIL_*` | Configurações SMTP para envio de e-mails |

## Comandos Artisan

| Comando | Descrição |
|---|---|
| `php artisan matches:sync-schedule` | Importa as partidas da Copa via API |
| `php artisan matches:sync-results` | Sincroniza os resultados das partidas finalizadas |
| `php artisan matches:sync-standings` | Sincroniza a classificação dos grupos |
| `php artisan db:seed --class=TeamSeeder` | Popula os times e grupos |

## Agendamentos (Schedule)

| Comando | Frequência |
|---|---|
| `matches:sync-results` | A cada 5 minutos |
| `matches:sync-standings` | A cada 15 minutos |

Para ativar o scheduler no servidor:
```
* * * * * php /caminho/artisan schedule:run >> /dev/null 2>&1
```

## Deploy automático

O projeto está configurado com webhook do GitHub. A cada `git push` para a branch `main`, o servidor executa automaticamente:

1. `git pull origin main`
2. `composer install`
3. `npm run build`
4. `php artisan migrate --force`
5. Limpeza de caches

O log de cada deploy fica em `storage/logs/deploy.log`.

## Sistema de pontuação

| Resultado | Pontos |
|---|---|
| Acerto exato do placar | 10 pts |
| Acerto do vencedor + um placar | 7 pts |
| Acerto apenas do vencedor | 5 pts |
| Empate previsto corretamente | 5 pts |
| Nenhum acerto | 0 pts |
