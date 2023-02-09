<?php

namespace App\Console\Commands;

use App\Events\AssetTransactionSuccess;
use App\Libraries\Binance;
use App\Models\AssetTransaction;
use App\Models\User;
use App\Notifications\SendTransactionSuccessNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        info("Cron Job Check Transaction mulai " . now());

        $pendingTransactions = AssetTransaction::where('integrated', 1)->where('status', 0)->where('notified', 0)->get();

        foreach ($pendingTransactions as $transaction) {
            // Check transaction status
            if ($this->isSuccess($transaction)) {
                DB::beginTransaction();
                try {
                    //code...
                    $wallet = $transaction->asset_wallet->wallet;

                    Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);

                    $url = "/api/v3/myTrades";
                    $params = [
                        'symbol' => $transaction->asset_wallet->asset->binance_symbol,
                        'orderId' => $transaction->order_id,
                    ];
                    $type = 'GET';
                    $response = Binance::call($wallet->demo, "SPOT", $url, $params, $type);

                    if(count($response) > 0){
                        $transaction->update([
                            'trade_id' => $response[0]['id'],
                            'time' => date('Y-m-d H:i:s', $response[0]['time'] / 1000),
                            'status' => 1,
                            'fee' => $response[0]['commission'],
                            'notified' => 1,
                        ]);
                    }
                    DB::commit();

                    $user = User::find($transaction->asset_wallet->wallet->user->id);
                    if($user->hasPermissionTo('assets-transactions-notifikasi')){
                        $user->notify(new SendTransactionSuccessNotification($transaction));
                    }
                } catch (\Throwable $th) {
                    // throw $th;
                    DB::rollBack();
                }

            }
        }

        return Command::SUCCESS;
    }

    protected function isSuccess(AssetTransaction $transaction){
        $wallet = $transaction->asset_wallet->wallet;

        Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);

        $url = "/api/v3/order";
        $params = [
            'symbol' => $transaction->asset_wallet->asset->binance_symbol,
            'orderId' => $transaction->order_id,
        ];
        $type = 'GET';
        $response = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
        if($response != null){
            if($response['status'] == 'FILLED'){
                return true;
            }
        }
        return false;
    }
}
