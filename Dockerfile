FROM registry-vpc.cn-hangzhou.aliyuncs.com/mztb0511/nginx-php:7.1

WORKDIR /var/www/html

COPY . /var/www/html/

RUN rm -rf /var/www/html/.git


VOLUME /var/www/html/storage

# 生命容器运行时提供的服务端口
EXPOSE 80

# 以前台方式运行nginx
CMD nohup /usr/local/sbin/php-fpm -D && service nginx start