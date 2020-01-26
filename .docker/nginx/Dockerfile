FROM nginx:1.15.3-alpine

RUN rm /etc/nginx/conf.d/default.conf

COPY .docker/nginx/nginx.conf.tmpl /etc/nginx/conf.d/default.conf

