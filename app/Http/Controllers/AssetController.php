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



        // // Binance::auth("WC7uqJLrRXlXsCTCz5WnKnZjHwY2STx7BIR1O79mZdP2C1IZzHkpBn9FYRen0yPN", "oPGTu1IIqZOZfKBYhkmSoC9wQNKIieOs8GkX46nVoL0c5x6kRAW7p71IggO5CcHK");
        // Binance::auth("PZUNzc5VCksFZBtPb4FkAbFHDdkPfjVR0bBc7hvkVNwzFFN9WJxqkisMaAWSxsZ0", "097zZtFSo8n11hivLexqjqVMOnSqFjzFf6kEgI8peimFFh8EOkL4ZcQTih6AP3Mw");
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

        $assets = Asset::all();
        foreach ($assets as $key => $asset) {
            $asset->symbol = substr($asset->binance_symbol, 0, -4);
            $asset->save();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Wallet $wallet)
    {
        return view('users.wallets.assets.list', compact('wallet'));
    }

    public function autocomplete(Request $request){
        // $data = Asset::select("name", "coin_gecko_id")
        // ->where('name', 'LIKE', '%'. $request->get('query'). '%')
        // ->get();

        $data = DB::table('assets')
        ->select(DB::raw("CONCAT(symbol,' - ', name) AS label, coin_gecko_id AS value"))
        ->where('symbol', 'LIKE', '%'. $request->get('query'). '%')
        ->orWhere('name', 'LIKE', '%'. $request->get('query'). '%')
        ->get();


        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAssetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAssetRequest  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        //
    }
}
