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
use Exception;

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

    public function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        return view('users.wallets.show', compact('wallet'));
    }

    public function load(Wallet $wallet)
    {
        if ($wallet->binance_api_key != null) {
            Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
            $url = "/api/v3/account";
            $params = [];
            $type = "GET";
            $account_info = Binance::call(true, "SPOT", $url, $params, $type);

            // print_r("<pre>");
            $assets = [];
            if ($account_info != null) {
                foreach ($account_info['balances'] as $balance) {
                    $asset = Asset::where('symbol', $balance['asset'])->first();
                    if ($asset != null) {
                        $assets[$asset->id] = ['amount' => $balance['free'] + $balance['locked']];
                    }
                }
            }
            $wallet->assets()->sync($assets);

            foreach ($wallet->assets as $asset) {
                $url = "/api/v3/allOrders";
                $params = [
                    'symbol' => $asset->binance_symbol,
                ];
                $type = "GET";
                $all_orders = Binance::call(true, "SPOT", $url, $params, $type);
                if ($all_orders != null) {
                    if (array_key_exists('code', $all_orders) != 1) {
                        // print_r($all_orders);
                        foreach ($all_orders as $k_order => $order) {
                            $type = '';
                            if ($order['side'] == 'BUY') {
                                $type = 0;
                            } else if ($order['side'] == 'SELL') {
                                $type = 1;
                            }
                            if ($order['status'] == 'NEW') {
                                $asset_wallet = AssetWallet::where('asset_id', $asset->id)->first();
                                if ($asset_wallet != null) {
                                    try {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $order['orderId'],
                                            'type' => $type,
                                            'price' => $order['price'],
                                            'amount' => $order['origQty'],
                                            'total' => $order['price'] * $order['origQty'],
                                            'status' => 0,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $order['time'] / 1000)
                                        ]);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                }
                            } else if ($order['status'] == 'FILLED') {
                                $url_trades = "/api/v3/myTrades";
                                $params_trades = [
                                    'symbol' => $asset->binance_symbol,
                                    'orderId' => $order['orderId']
                                ];
                                $type_trades = "GET";
                                $myTrades = Binance::call(true, "SPOT", $url_trades, $params_trades, $type_trades)[0];
                                // print_r($myTrades);
                                $asset_wallet = AssetWallet::where('asset_id', $asset->id)->first();
                                if ($asset_wallet != null) {
                                    try {
                                        $asset_wallet->transactions()->create(
                                            [
                                                'trade_id' => $myTrades['id'],
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $myTrades['price'],
                                                'amount' => $myTrades['qty'],
                                                'fee' => $myTrades['commission'],
                                                'total' => $myTrades['price'] * $myTrades['qty'],
                                                'status' => 1,
                                                'integrated' => 1,
                                                'time' => date('Y-m-d H:i:s', $myTrades['time'] / 1000)
                                            ]
                                        );
                                    } catch (\Throwable $th) {
                                        throw $th;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $data = $wallet;
        $data['assets'] = $wallet->assets;
        return response()->json($data);
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
