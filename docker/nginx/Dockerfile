FROM nginx:latest

COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

COPY ./public /app/public
# when create serts in production for https
#COPY ./YOURPUBLIC.pem /etc/ssl/certs/YOURPUBLIC.pem

#COPY ./YOURPRIVATE.key /etc/ssl/private/YOURPRIVATE.key
