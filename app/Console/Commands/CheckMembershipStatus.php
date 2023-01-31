<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckMembershipStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command to check the status of the membership of the users if it is expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Cron Job Check Membership mulai " . now());

        $users = User::where('user_type', '<>', 'admin')->get();
        foreach ($users as $key => $user) {
            if (date('Y-m-d') > $user->membership_till) {
                $user->user_type = "user";
                $user->membership_since = null;
                $user->membership_till = null;
                $user->max_wallets = 0;
                $user->max_journals = 0;
                $user->trades_quantity_per_month = 0;
                if ($user->remaining_trades = -1) {
                    $user->remaining_trades = 0;
                }

                if ($user->hasPermissionTo('portfolio-tambah-binance')) {
                    $user->revokePermissionTo('portfolio-tambah-binance');
                }

                if ($user->hasPermissionTo('assets-transactions-notifikasi')) {
                    $user->revokePermissionTo('assets-transactions-notifikasi');
                }
                $user->save();

                if (count($user->membership) > 0) {
                    DB::table('membership_user')->where('user_id', $user->id)->where('membership_id', $user->membership[0]->id)->where('status', 1)->update(['status' => 0]);
                }
            }
        }

        return Command::SUCCESS;
    }
}
