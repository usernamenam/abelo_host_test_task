## Запуск

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php bin/init_db.php
docker compose exec php php bin/seed.php
```

Приложение доступно на <http://localhost>

## Стили

SCSS находится в `public/assets/scss/`, собранный CSS — в `public/assets/css/app.css` и уже включён в проект. Пересборка через Sass CLI

## Использование ИИ

ИИ использовался для помощи в разработке фронтенда, для помощи в разработке дизайна проекта. 
Также: как справочник, для помощи в разработке некоторых частей src/Framework, для помощи в написании SQL запросов