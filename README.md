# spares-store

## Установка

### Локальная среда
1. Скопировать env:
```bash
cp .env.local .env
cp api/.env.local api/.env
```
2. Скопировать docker-compose:
```bash
cp docker-compose.local.yml docker-compose.yml
```
3. Запустить контейнеры:
```bash
docker compose up -d
```
4. Установить зависимости:
```bash
docker compose exec php composer install
```
5. Выполнить миграции и сиды с рефрешем:
```bash
docker compose exec php php artisan migrate:fresh --seed
```
6. API доступен по адресу `http://localhost:8080`

## Запуск тестов
```bash
docker compose exec php php artisan test
```

## Postman
Postman коллекция доступна в папке `docs`