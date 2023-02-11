<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use App\Libraries\Binance;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function init()
    {
        // $client = new CoinGeckoClient();

        // $assets = Asset::orderBy('id', 'asc')->get();
        // $counter = 0;
        // $last = "";
        // $last_id = "";
        // $bool = false;
        // foreach ($assets as $key => $asset) {
        //     if ($asset['id'] == '409') {
        //         $bool = true;
        //     }
        //     if ($bool == true) {
        //         if ($counter < 15) {
        //             $counter++;
        //             try {
        //                 $data = $client->coins()->getCoin($asset['coin_gecko_id']);
        //                 $asset->update([
        //                     'price_change_percentage_1h' => $data['market_data']['price_change_percentage_1h_in_currency']['usd'],
        //                     'price_change_percentage_24h' => $data['market_data']['price_change_percentage_24h'],
        //                     'price_change_percentage_7d' => $data['market_data']['price_change_percentage_7d'],
        //                 ]);
        //                 print_r($counter);
        //                 print_r("<br>");
        //                 $last = $asset['coin_gecko_id'];
        //                 $last_id = $asset['id'];
        //             } catch (\Throwable $th) {
        //             }
        //         }
        //     }
        // }
        // print_r("<br>");
        // print_r($last);
        // print_r("<br>");
        // print_r($last_id);
        // print_r("<br>");
        // print_r(date('Y-m-d H:i:s'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Wallet $wallet)
    {
        if ($wallet->deleted_at != '') {
            return redirect()->back()->withError('Anda tidak punya akses ke halaman ini');
        } else if ($wallet->demo == true) {
            return redirect()->back()->withError('Dompet ini bersifat demo, sehingga tidak bisa menambahkan aset selain yang sudah tertera');
        }
        return view('users.wallets.assets.list', compact('wallet'));
    }

    public function autocomplete(Wallet $wallet, Request $request)
    {
        $data = [];
        if ($wallet->binance_api_key == null) {
            $data = DB::table('assets')
                ->select(DB::raw("CONCAT(symbol,' - ', name) AS label, coin_gecko_id AS value"))
                ->where('symbol', 'LIKE', '%' . $request->get('query') . '%')
                ->orWhere('name', 'LIKE', '%' . $request->get('query') . '%')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $data = DB::table('assets')
                ->select(DB::raw("CONCAT(symbol,' - ', name) AS label, coin_gecko_id AS value"))
                ->whereRaw("binance_symbol <> '' AND (symbol LIKE '%" . $request->get('query') . "%' OR name LIKE '%" . $request->get('query') . "%')")
                ->orderBy('name', 'asc')
                ->get();
        }


        return response()->json($data);
    }

    public function load(Request $request)
    {

        $exchanges_available = ['binance', 'bingx', 'bitget', 'bitfinex', 'bitflyer', 'bithumb', 'bitkub', 'bitmex', 'bitrue', 'btse', 'bitso', 'bitstamp', 'bittrex', 'bybit_spot', 'cex', 'coinex', 'currency', 'delta_spot', 'deribit', 'dydx', 'exmo', 'gate', 'gemini', 'honeyswap', 'honeyswap_polygon', 'huobi', 'korbit', 'kraken', 'kucoin', 'mercado', 'mxc', 'okcoin', 'okex', 'pangolin', 'pancakeswap_ethereum', 'pancakeswap_new', 'phemex', 'poloniex', 'spookyswap', 'sushiswap, ', 'therocktrading', 'traderjoe', 'uniswap_v2', 'uniswap_v3', 'uniswap_v3_arbitrum', 'uniswap_v3_polygon_pos', 'upbit', 'whitebit', 'wootrade'];

        $client = new CoinGeckoClient();

        $asset = Asset::where('coin_gecko_id', $request->get('query'))->first();
        if ($asset) {
            DB::beginTransaction();
            try {
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
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }

            return response()->json($asset);
        } else {
            DB::beginTransaction();
            try {
                $data = $client->search()->getSearchResult(["query" => $request->get('query')]);
                if (count($data['coins']) > 0) {
                    $coin = $client->coins()->getCoin($data['coins'][0]['id']);
                    $tickers = $coin['tickers'];
                    $exchange_found = null;
                    $targets_found = null;
                    // dd($tickers);
                    foreach ($tickers as $key => $ticker) {
                        foreach ($exchanges_available as $exc) {
                            if ($ticker['market']['identifier'] == $exc and ($ticker['target'] == 'USDT' or $ticker['target'] == 'BUSD')) {
                                if ($exchange_found == null) {
                                    $exchange_found = strtoupper($exc);
                                    $targets_found = $ticker['target'];
                                }
                            }
                        }
                    }

                    if ($exchange_found == "BYBIT_SPOT") {
                        $exchange_found = "BYBIT";
                    }
                    if ($exchange_found == "CEX") {
                        $exchange_found = "CEXIO";
                    }
                    if ($exchange_found == "CURRENCY") {
                        $exchange_found = "CURRENCYCOM";
                    }
                    if ($exchange_found == "DELTA_SPOT") {
                        $exchange_found = "DELTA";
                    }
                    if ($exchange_found == "GATE") {
                        $exchange_found = "GATEIO";
                    }
                    if ($exchange_found == "HONEYSWAP_POLYGON") {
                        $exchange_found = "HONEYSWAPPOLYGON";
                    }
                    if ($exchange_found == "MXC") {
                        $exchange_found = "MEXC";
                    }
                    if ($exchange_found == "PANCAKESWAP_ETHEREUM") {
                        $exchange_found = "PANCAKESWAP";
                    }
                    if ($exchange_found == "PANCAKESWAP_NEW") {
                        $exchange_found = "PANCAKESWAP";
                    }
                    if ($exchange_found == "UNISWAP_V2") {
                        $exchange_found = "UNISWAP";
                    }
                    if ($exchange_found == "UNISWAP_V3") {
                        $exchange_found = "UNISWAP3ETH";
                    }
                    if ($exchange_found == "UNISWAP_V3_ARBITRUM") {
                        $exchange_found = "UNISWAP3ARBITRUM";
                    }
                    if ($exchange_found == "UNISWAP_V3_POLYGON_POS") {
                        $exchange_found = "UNISWAP3POLYGON";
                    }
                    if ($exchange_found == "WOOTRADE") {
                        $exchange_found = "WOO";
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


                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
        return response()->json($asset);
    }

    public function list()
    {
        $watchlist = 0;
        return view('users.coins.index', compact('watchlist'));
    }

    public function list_watchlist()
    {
        $watchlist = 1;
        return view('users.coins.index', compact('watchlist'));
    }

    public function list_load(Request $request)
    {
        $page = $request->input('page', 1);
        $user_id = $request->input('user_id');
        $results = Asset::select('id', 'coin_gecko_id', 'market_cap_rank', 'name', 'symbol', 'thumb', 'current_price', 'price_change_percentage_1h', 'price_change_percentage_24h', 'price_change_percentage_7d', 'market_cap')->with('users', function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        });

        $watchlist = $request->input('watchlist');
        if ($watchlist == "1") {
            $results->whereHas('users', function ($query) use ($user_id) {
                $query->where('users.id', $user_id);
            });
        }

        $searchTerm = $request->input('search.value');

        if ($searchTerm) {
            $results->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('symbol', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $results = $results->orderBy(DB::raw('ISNULL(market_cap_rank), market_cap_rank'), 'ASC')->paginate(10, ['*'], 'page', $page);

        $client = new CoinGeckoClient();
        foreach ($results->items() as $key => $item) {
            DB::beginTransaction();
            try {
                $data = $client->coins()->getCoin($item->coin_gecko_id);

                $item->update([
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
                    'price_change_percentage_1h' => $data['market_data']['price_change_percentage_1h_in_currency']['usd'],
                    'price_change_percentage_24h' => $data['market_data']['price_change_percentage_24h'],
                    'price_change_percentage_7d' => $data['market_data']['price_change_percentage_7d'],
                    'thumb' => $data['image']['small'],
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

        $data = [
            'data' => $results->items(),
            'recordsTotal' => $results->total(),
            'recordsFiltered' => $results->total()
        ];
        return response()->json($data);
    }

    public function favorit($id)
    {
        $user = Auth::user();

        $asset = Asset::findOrFail($id);

        $user->watchlist()->attach($asset);

        return redirect()->back()->withSuccess('Berhasil ditambahkan ke watchlist');
    }

    public function unfavorit($id)
    {
        $user = Auth::user();

        $asset = Asset::findOrFail($id);

        $user->watchlist()->detach($asset);

        return redirect()->back()->withSuccess('Berhasil dihapus dari watchlist');
    }

    public function info($id)
    {
        $user_id = Auth::id();
        $asset = Asset::select('*')->with('users', function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        })->where('id', $id)->first();
        return view('users.coins.show', compact('asset'));
    }
}
