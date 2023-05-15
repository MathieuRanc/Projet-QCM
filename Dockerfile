FROM mysql:debian

ENV MYSQL_ROOT_PASSWORD=root

COPY ./database_qcm.sql /docker-entrypoint-initdb.d/


ADD ./api/ /api/
WORKDIR /api/public

RUN apt update -y \
&& apt upgrade -y \
&& apt install wget -y\
&&apt -y install lsb-release apt-transport-https ca-certificates \
&& wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
&& echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list \
&& apt update -y\
&& apt upgrade -y\
&& apt install php -y  \
&& apt install composer -y \ 
&& sed -i '4i php -S 0.0.0.0:8000&' /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000


