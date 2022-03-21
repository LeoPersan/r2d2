<?php

namespace Leopersan\R2d2;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require __DIR__.'/routes.php';
    }
}
