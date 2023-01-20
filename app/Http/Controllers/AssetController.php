<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use App\Libraries\Binance;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function init()
    {
        // echo "<pre>";

        // // $coin1 = json_decode(file_get_contents(public_path() . "/data/coin1.json"), true)['tickers'];
        // // $coin2 = json_decode(file_get_contents(public_path() . "/data/coin2.json"), true)['tickers'];
        // // $coin3 = json_decode(file_get_contents(public_path() . "/data/coin3.json"), true)['tickers'];
        // // $coin4 = json_decode(file_get_contents(public_path() . "/data/coin4.json"), true)['tickers'];
        // // $coin5 = json_decode(file_get_contents(public_path() . "/data/coin5.json"), true)['tickers'];
        // // $coin6 = json_decode(file_get_contents(public_path() . "/data/coin6.json"), true)['tickers'];
        // // $coin7 = json_decode(file_get_contents(public_path() . "/data/coin7.json"), true)['tickers'];
        // // $coin8 = json_decode(file_get_contents(public_path() . "/data/coin8.json"), true)['tickers'];
        // // $coin9 = json_decode(file_get_contents(public_path() . "/data/coin9.json"), true)['tickers'];
        // // $coin10 = json_decode(file_get_contents(public_path() . "/data/coin10.json"), true)['tickers'];
        // // $coin11 = json_decode(file_get_contents(public_path() . "/data/coin11.json"), true)['tickers'];
        // // $coin12 = json_decode(file_get_contents(public_path() . "/data/coin12.json"), true)['tickers'];
        // // $coin13 = json_decode(file_get_contents(public_path() . "/data/coin13.json"), true)['tickers'];
        // // $coin14 = json_decode(file_get_contents(public_path() . "/data/coin14.json"), true)['tickers'];

        // // $coins = array_merge($coin1, $coin2, $coin3, $coin4, $coin5, $coin6, $coin7, $coin8, $coin9, $coin10, $coin11, $coin12, $coin13, $coin14);

        // // $temp = [];
        // // foreach ($coins as $key => $value){
        // //         if($value['target'] == 'USDT'){
        // //                 $temp[] = $value;
        // //             }
        // // }
        // $coingecko = json_decode(file_get_contents(public_path() . "/data/coingecko.json"), true);
        // // print_r($coingecko);
        // // print_r($temp);
        // // $json = json_encode($temp);
        // // if (file_put_contents("coingecko.json", $json))
        // //     echo "JSON file created successfully...";
        // // else
        // //     echo "Oops! Error creating json file...";

        // $url = "/sapi/v1/asset/assetDetail";
        // $params = [];
        // $type = "GET";
        // $response = Binance::call(false, "SPOT", $url, $params, $type);
        // // print_r($response);

        // $client = new CoinGeckoClient();

        // $bool = false;
        // $counter = 1;
        // foreach ($response as $key => $value){
        //     foreach($coingecko as $key2 => $coin){
        //         if($key == $coin['base']){
        //             // if($bool == true && $counter < 20){
        //             //     // print_r($coin);
        //             //     $data = $client->coins()->getCoin($coin['coin_id']);
        //             //     print_r($data);
        //             //     try {
        //             //         Asset::updateOrCreate([
        //             //             'coin_gecko_id' => $coin['coin_id'],
        //             //             'binance_symbol' => $coin['base'] . $coin['target'],
        //             //             'name' => $data['name'],
        //             //             'platforms' => json_encode($data['platforms']),
        //             //             'links' => json_encode($data['links']),
        //             //             'market_cap_rank' => $data['market_cap_rank'],
        //             //             'market_cap' => $data['market_data']['market_cap']['usd'],
        //             //             'total_volume' => $data['market_data']['total_volume']['usd'],
        //             //             'market_cap_24h' => $data['market_data']['market_cap_change_24h'],
        //             //             'total_supply' => $data['market_data']['total_supply'],
        //             //             'total_supply' => $data['market_data']['total_supply'],
        //             //             'circulating_supply' => $data['market_data']['circulating_supply'],
        //             //             'current_price' => $data['market_data']['current_price']['usd'],
        //             //             'thumb' => $data['image']['thumb'],
        //             //         ]);
        //             //     } catch (\Throwable $th) {
        //             //         //throw $th;
        //             //     }

        //             //     $counter++;
        //             // }
        //             // if($coin['base'] == 'COTI'){
        //             //     $bool = true;
        //             // }
        //             $counter++;
        //         }
        //     }
        // }
        // echo $counter;
        // // $url = "/api/v3/exchangeInfo";
        // // $symbols = ["DGBUSDT", "DIAUSDT", "ACHUSDT", "APEUSDT", "DENTUSDT", "ONTUSDT", "DODOUSDT", "NEXOUSDT"];
        // // $params = ["symbols" => json_encode($symbols)];
        // // $type = "GET";
        // // $response = Binance::call(false, "SPOT", $url, $params, $type);
        // // echo($response);
        // // print_r($response);
        // // return view('users.wallets.assets.list');

        // $assets = Asset::all();
        // foreach ($assets as $key => $asset) {
        //     $asset->symbol = substr($asset->binance_symbol, 0, -4);
        //     $asset->save();
        // }

        // $assets = Asset::orderBy('id', 'asc')->get();
        // $counter = 0;
        // $last = "";
        // $last_id = "";
        // $bool = false;
        // foreach ($assets as $key => $asset) {
        //     if($asset['id'] == '402'){
        //         $bool = true;
        //         // print_r($bool);
        //     }
        //     if($bool == true){
        //         if($counter < 15){
        //             $counter++;
        //             $data = $client->coins()->getCoin($asset['coin_gecko_id']);
        //             try {
        //                 $asset->update([
        //                     'thumb' => $data['image']['small'],
        //                 ]);
        //                 print_r($counter);
        //                 print_r("<br>");
        //             } catch (\Throwable $th) {
        //                 print_r($th);
        //             }
        //             $last = $asset['coin_gecko_id'];
        //             $last_id = $asset['id'];
        //         }
        //     }
        // }
        // print_r("<br>");
        // print_r($last);
        // print_r("<br>");
        // print_r($last_id);
        // print_r("<br>");
        // print_r(now());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Wallet $wallet)
    {
        if($wallet->deleted_at != ''){
            return redirect()->back()->withError('Anda tidak punya akses ke halaman ini');
        }
        else if($wallet->demo == true){
            return redirect()->back()->withError('Dompet ini bersifat demo, sehingga tidak bisa menambahkan aset selain yang sudah tertera');
        }
        return view('users.wallets.assets.list', compact('wallet'));
    }

    public function autocomplete(Wallet $wallet, Request $request){
        $data = [];
        if($wallet->binance_api_key == null){
            $data = DB::table('assets')
            ->select(DB::raw("CONCAT(symbol,' - ', name) AS label, coin_gecko_id AS value"))
            ->where('symbol', 'LIKE', '%'. $request->get('query'). '%')
            ->orWhere('name', 'LIKE', '%'. $request->get('query'). '%')
            ->orderBy('name', 'asc')
            ->get();
        }
        else{
            $data = DB::table('assets')
            ->select(DB::raw("CONCAT(symbol,' - ', name) AS label, coin_gecko_id AS value"))
            ->whereRaw("binance_symbol <> '' AND (symbol LIKE '%" . $request->get('query') . "%' OR name LIKE '%" . $request->get('query') . "%')")
            ->orderBy('name', 'asc')
            ->get();
        }


        return response()->json($data);
    }

    public function load(Request $request){

        $exchanges_available = ['binance', 'bingx', 'bitget', 'bitfinex', 'bitflyer', 'bithumb', 'bitkub', 'bitmex', 'bitpanda', 'bitrue', 'btse', 'bitso', 'bitstamp' , 'bittrex', 'bybit_spot', 'cex', 'gdax', 'coinex', 'currency', 'delta_spot', 'deribit', 'dydx', 'exmo', 'gate', 'gemini', 'honeyswap', 'honeyswap_polygon', 'huobi', 'korbit', 'kraken', 'kucoin', 'maiar', 'mercado', 'mxc', 'okcoin', 'okex', 'pangolin', 'pancakeswap_ethereum', 'pancakeswap_new', 'phemex', 'poloniex', 'spookyswap', 'sushiswap, ', 'therocktrading', 'traderjoe', 'uniswap_v2', 'uniswap_v3', 'uniswap_v3_arbitrum', 'uniswap_v3_polygon_pos', 'upbit', 'whitebit', 'wootrade'];

        $client = new CoinGeckoClient();

        $asset = Asset::where('coin_gecko_id', $request->get('query'))->first();
        if($asset){
            $data = $client->coins()->getCoin($request->get('query'));
            // dd($data);
            $update = $asset->update([
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

            if($update){
                return response()->json($asset);
            }
        }
        else{
            $data = $client->search()->getSearchResult(["query" => $request->get('query')]);
            if(count($data['coins']) > 0){
                $coin = $client->coins()->getCoin($data['coins'][0]['id']);
                $tickers = $coin['tickers'];
                $exchange_found = null;
                $targets_found = null;
                // dd($tickers);
                foreach ($tickers as $key => $ticker) {
                    foreach($exchanges_available as $exc){
                        if($ticker['market']['identifier'] == $exc and ($ticker['target'] == 'USDT' or $ticker['target'] == 'BUSD')){
                            if($exchange_found == null){
                                $exchange_found = $exc;
                                $targets_found = $ticker['target'];
                            }
                        }
                    }
                }

                $asset = Asset::updateOrCreate([
                    'coin_gecko_id' => $data['coins'][0]['id'],
                    'name' => $coin['name'],
                    'symbol' => strtoupper($coin['symbol']),
                    'platforms' => json_encode($coin['platforms']),
                    'exchanges' => $exchange_found,
                    'special_targets' => $targets_found,
                    'links' => json_encode($coin['links']),
                    'market_cap_rank' => $coin['market_cap_rank'],
                    'market_cap' => $coin['market_data']['market_cap']['usd'],
                    'total_volume' => $coin['market_data']['total_volume']['usd'],
                    'market_cap_24h' => $coin['market_data']['market_cap_change_24h'],
                    'total_supply' => $coin['market_data']['total_supply'],
                    'total_supply' => $coin['market_data']['total_supply'],
                    'circulating_supply' => $coin['market_data']['circulating_supply'],
                    'current_price' => $coin['market_data']['current_price']['usd'],
                    'thumb' => $coin['image']['thumb'],
                ]);


                return response()->json($asset);
                // try {

                //     print_r($asset);
                //     if($asset){
                //         return response()->json($asset);
                //     }
                // } catch (\Throwable $th) {
                //     //throw $th;
                // }
            }
        }
        return null;
    }
}
