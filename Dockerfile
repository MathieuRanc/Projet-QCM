FROM mysql:debian

ENV MYSQL_ROOT_PASSWORD=root

COPY ./database_qcm.sql /docker-entrypoint-initdb.d/


ADD ./api/ /api/
WORKDIR /api/public

RUN apt update -y \
&& apt upgrade -y \
&& apt install php -y  \
&& apt install composer -y \ 
&& sed -i '4i php -S 0.0.0.0:8000&' /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000


