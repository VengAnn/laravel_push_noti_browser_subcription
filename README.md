

# install web push in laravel
cmd => composer require minishlink/web-push
then => npm install web-push -g
then run ( for generate key) =>   web-push generate-vapid-keys [--json]
then will see private key and public key 



## useful commands in laravel

php artisan make:model PushNotification -mcr

( this will be created controller , model and migration )