<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\AssetTransaction;
use App\Models\AssetWallet;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::findOrFail(Auth::id());
        return view('users.wallets.list', compact('data'));
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
     * @param  \App\Http\Requests\StoreWalletRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWalletRequest $request)
    {
        if (Auth::user()->max_wallets == 0 or Auth::user()->wallets->count() < Auth::user()->max_wallets) {
            $wallet = Auth::user()->wallets()->create($request->all());
            if ($wallet) {
                return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
            } else {
                return redirect()->back()->withError('Dompet gagal ditambahkan');
            }
        } else if (Auth::user()->max_wallets == -1) {
            $wallet = Auth::user()->wallets()->create($request->all());
            if ($wallet) {
                return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
            } else {
                return redirect()->back()->withError('Dompet gagal ditambahkan');
            }
        } else {
            return redirect()->back()->withError('Jumlah dompet yang dimiliki pengguna sudah mencapai batasnya');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        // $client = new CoinGeckoClient();
        // // dd($wallet->assets);
        print_r("<pre>");
        if ($wallet->binance_api_key != null) {
            $wallet->assets()->sync([]);

            Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
            $url = "/api/v3/account";
            $params = [];
            $type = "GET";
            $response = Binance::call(true, "SPOT", $url, $params, $type);

            if($response != null){
                foreach ($response['balances'] as $balance) {
                    $asset = Asset::where('symbol', $balance['asset'])->first();
                    if ($asset != null) {
                        if ($wallet->assets()->find($asset->id) == null) {
                            $add = $wallet->assets()->attach($asset->id);
                        }
                    }
                }
            }


            foreach ($wallet->assets as $asset) {
                $url = "/api/v3/allOrders";
                $params = [
                    'symbol' => $asset->binance_symbol,
                ];
                $type = "GET";
                $response = Binance::call(true, "SPOT", $url, $params, $type);
                if ($response != null) {
                    if (array_key_exists('code', $response) != 1) {
                        // print_r($response);
                        foreach ($response as $k_order => $order) {
                            // print_r($order);
                            if ($order['status'] == 'NEW') {

                            } else if ($order['status'] == 'FILLED') {
                                $fetch_asset = Asset::where('binance_symbol', $order['symbol'])->first();
                                if ($fetch_asset != null) {
                                    $fetch_asset_wallet = AssetWallet::where('asset_id', $fetch_asset->id)->first();
                                    if ($fetch_asset_wallet != null) {
                                        $url_trades = "/api/v3/myTrades";
                                        $params_trades = [
                                            'symbol' => $asset->binance_symbol,
                                            'orderId' => $order['orderId']
                                        ];
                                        $type_trades = "GET";
                                        $trade_response = Binance::call(true, "SPOT", $url_trades, $params_trades, $type_trades);

                                        $type = '';
                                        if($order['side'] == 'BUY'){
                                            $type = 0;
                                        }
                                        else if($order['side'] == 'SELL'){
                                            $type = 1;
                                        }
                                        try {
                                            AssetTransaction::create([
                                                'asset_wallet_id' => $fetch_asset_wallet->id,
                                                'trade_id' => $trade_response[0]['id'],
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $trade_response[0]['price'],
                                                'amount' => $trade_response[0]['qty'],
                                                'fee' => $trade_response[0]['commision'],
                                                'total' => ($trade_response[0]['price'] * $trade_response[0]['qty']) + $trade_response[0]['commision'],
                                                'status' => 1,
                                                'integrated' => 1,
                                                'time' => $trade_response[0]['time']
                                            ]);
                                        } catch (\Throwable $th) {
                                            print_r($th);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            dd('tes');
        }

        return view('users.wallets.show', compact('wallet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWalletRequest  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $success = $wallet->update($request->all());
        if ($success) {
            return redirect()->back()->withSuccess('Dompet berhasil diubah');
        } else {
            return redirect()->back()->withError('Dompet gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $delete = $wallet->delete();
        if ($delete) {
            return redirect()->back()->withSuccess('Dompet berhasil dinonaktifkan');
        } else {
            return redirect()->back()->withError('Dompet gagal dinonaktifkan');
        }
    }

    public function restore(Wallet $wallet)
    {
        $restore = $wallet->restore();
        if ($restore) {
            return redirect()->back()->withSuccess('Dompet berhasil diaktifkan');
        } else {
            return redirect()->back()->withError('Dompet gagal diaktifkan');
        }
    }
}
