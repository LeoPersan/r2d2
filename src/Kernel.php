<?php

namespace Leopersan\R2d2;

use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Leopersan\R2d2\Migration\MigrateMakeCommand;
use Leopersan\R2d2\Migration\MigrationCreator;

class Kernel extends ConsoleKernel
{
    protected function commands()
    {
        Artisan::starting(fn ($artisan) => 
            $artisan->add(new MigrateMakeCommand(
                new MigrationCreator($this->app['files'], __DIR__.'/Migration/stubs'),
                $this->app['composer']
            ))
        );

        $this->load(__DIR__.'/Commands');

        require __DIR__.'/routes.php';
    }
}
