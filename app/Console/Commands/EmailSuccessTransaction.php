<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmailSuccessTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command to check when pending transaction of Binance turn into success and send the email to the users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
