<?php

namespace App\Console\Commands;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\AssetUpdateJob;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command to update the information of assets obtained by users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Cron Job Update Asset mulai " . now());

        // cek apakah job nya sudah selesai
        $total = AssetUpdateJob::count();
        $updated = AssetUpdateJob::where('updated', 1)->count();
        if($total == $updated){
            info("Update Asset Sudah Selesai");
            AssetUpdateJob::truncate();
        }

        // masukkin ke table job buat update
        $assets = Asset::has('wallets')->orderBy('id')->get();
        foreach($assets as $asset){
            AssetUpdateJob::firstOrCreate([
                'asset_id' => $asset->id,
            ]);
        }

        // kerjain job update
        $client = new CoinGeckoClient();
        print_r("<pre>");
        $asset_jobs = AssetUpdateJob::where('updated', 0)->take(15)->get();
        foreach ($asset_jobs as $job) {

            DB::beginTransaction();
            try {
                $asset = Asset::find($job->asset->id);
                $data = $client->coins()->getCoin($asset->coin_gecko_id);

                $asset->update([
                    'name' => $data['name'],
                    'platforms' => json_encode($data['platforms']),
                    'links' => json_encode($data['links']),
                    'market_cap_rank' => $data['market_cap_rank'],
                    'market_cap' => $data['market_data']['market_cap']['usd'],
                    'total_volume' => $data['market_data']['total_volume']['usd'],
                    'market_cap_24h' => $data['market_data']['market_cap_change_24h'],
                    'total_supply' => $data['market_data']['total_supply'],
                    'total_supply' => $data['market_data']['total_supply'],
                    'circulating_supply' => $data['market_data']['circulating_supply'],
                    'current_price' => $data['market_data']['current_price']['usd'],
                    'thumb' => $data['image']['small'],
                ]);

                $job->updated = 1;
                $job->save();

                DB::commit();
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
            }
        }

        // ubah harga yang ada binancenya
        Binance::auth(env('BINANCE_DEMO_API_KEY'), env('BINANCE_DEMO_SECRET_KEY'));
        $binance_assets = Asset::has('wallets')->whereNotNull('binance_symbol')->orderBy('id')->get();
        foreach($binance_assets as $asset){
            DB::beginTransaction();
            try {
                $url = "/api/v3/ticker/price";
                $params = [
                    'symbol' => $asset->binance_symbol
                ];
                $type = "GET";
                $response = Binance::call(false, "SPOT", $url, $params, $type);

                $asset->current_price = $response['price'];
                $asset->save();
                DB::commit();
            } catch (\Throwable $th) {
                // throw $th;
                DB::rollBack();
            }
        }

        return Command::SUCCESS;
    }
}
