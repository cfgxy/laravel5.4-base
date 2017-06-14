FROM registry.cn-hangzhou.aliyuncs.com/wanjia/phprunner

RUN  pecl install redis && \
     docker-php-ext-enable redis

RUN  apk --update add sudo

RUN  apk add libxml2-dev && \
     docker-php-ext-install soap

# 开发时可激活 debug 用于单步调试 (IP 地址改成自己内网 IP)
#RUN  pecl install xdebug && \
#     docker-php-ext-enable xdebug
#RUN  echo "xdebug.remote_enable=On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN  echo "xdebug.remote_host=192.168.48.3" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY ssh_config /root/.ssh/config
RUN  chmod -R 700 /root/.ssh && \
     chmod -R 600 /root/.ssh/config
COPY ssh_known_hosts /root/.ssh/known_hosts
COPY app_check /usr/local/bin/
COPY app_init /usr/local/bin/
COPY zz-docker.conf /usr/local/etc/php-fpm.d/
RUN  echo "* * * * * /usr/local/bin/app_check" >> /etc/crontabs/root
RUN  echo "0 2 * * * /var/www/docker/app_refresh" >> /etc/crontabs/root

WORKDIR /var/www
