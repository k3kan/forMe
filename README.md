# Получение погоды по координатам или названию города
Есть веб-морда для получения погоды по координатам местности, также реализован телеграм бот для получения погоды. Можно добавить город в рассылку, которая будет рассылаться каждый день в 12:00 по Москве.

# DEV

* docker compose up -d
* Если не контейнеры не запустились, поочереди перезапускаем контейнеры nginx, php.
  
# PROD
   Создаем сертификаты на сервере. Можно самописные
   ## Создание самописного сертификата для телеграмм.
   ### Генерием сертификаты
    * openssl req -newkey rsa:2048 -sha256 -nodes -x509 -days 365 \
    -keyout YOURPRIVATE.key \
    -out YOURPUBLIC.crt \
    -subj "/C=RU/ST=town/L=town/O=Example Inc/CN=ip_server"
   ###  Конвертим в .pem формат
    * openssl x509 -in YOURPUBLIC.crt -out YOURPUBLIC.pem -outform PEM
   ###  Отправляем телеграмм боту
    * curl -F "url=https:/ip_server/webhook" -F "certificate=@YOURPUBLIC.pem" "https://api.telegram.org/tg_token/setwebhook"
   ### Проверяем, корректно ли установился
    * https://api.telegram.org/tg_token/getWebhookInfo
   ### Сертификаты оставляем в корне проекта
## Запускаем отдельные конфиги с названием prod.

## В Dockerfile nginx раскоменчиваем строки, чтобы подтягивались сертификаты в контейнер.
