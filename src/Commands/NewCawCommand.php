<?php

namespace Leopersan\R2d2\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class NewCawCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:caw {path : Nome da pasta do novo projeto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Novo projeto padrão CAW (Laravel 8.*)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->argument('path');
        $instalation = 'laravel new '.$path.' && cd '.$path.' && php ./artisan sail:install --with=mysql,mailhog';
        Process::fromShellCommandline('docker run --rm -v "$(pwd)":/opt -w /opt laravelsail/php74-composer:latest bash -c "'.$instalation.'"')->setTimeout(null)->mustRun();

        $commands = [
            'cd '.$path,
            'sudo chown -R $USER: .',
            './vendor/bin/sail up -d',
            './vendor/bin/sail composer require lucascudo/laravel-pt-br-localization --dev',
            './vendor/bin/sail artisan vendor:publish --tag=laravel-pt-br-localization',
            './vendor/bin/sail composer require jeroennoten/laravel-adminlte',
            './vendor/bin/sail artisan adminlte:install',
            './vendor/bin/sail composer require laravel/ui',
            './vendor/bin/sail artisan ui bootstrap --auth',
            './vendor/bin/sail artisan adminlte:install --force --only=auth_views',
            './vendor/bin/sail npm install',
            './vendor/bin/sail npm install --save-dev vue@^2.6.14 vue-loader@^15.8.3 vue-template-compiler',
            './vendor/bin/sail npm install --save-dev @fortawesome/fontawesome-free @popperjs/core bootstrap icheck-bootstrap jquery overlayscrollbars resolve-url-loader sass sass-loader v-money vue-the-mask',
            './vendor/bin/sail npm install --save-dev vue-select@^3.13.2 @tinymce/tinymce-vue@^3.2.8',
            'rm -rf ./app/Models/User.php ./app/database/migrations/*.php ./resources/sass ./public/vendor',
            'mv ./resources/lang/vendor/adminlte/pt-br ./pt-BR',
            'rm -rf ./resources/lang/vendor/adminlte/*',
            'mv ./pt-BR ./resources/lang/vendor/adminlte/',
            'cp -r '.__DIR__.'/../laravel_new/* ./',
            './vendor/bin/sail npm run dev',
            './vendor/bin/sail artisan migrate',
        ];
        exec(implode(' && ', $commands));

        return 0;
    }
}
