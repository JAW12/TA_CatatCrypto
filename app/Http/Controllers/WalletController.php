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
use Illuminate\Support\Facades\DB;

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
            if($request['binance_api_key'] == null and $request['balance'] == null){
                return redirect()->back()->withError('Dompet gagal ditambahkan');
            }
            else if($request['binance_api_key'] != null){
                $request['balance'] = 0;
            }
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
        $total_assets_wallet = 0;
        $total_pnl_wallet = 0;
        if ($wallet->binance_api_key != null) {
            // print_r("<pre>");
            Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
            $url = "/api/v3/account";
            $params = [];
            $type = "GET";
            $account_info = Binance::call(true, "SPOT", $url, $params, $type);
            // print_r("Assets Balance <br>");
            $assets = [];
            if ($account_info != null) {
                foreach ($account_info['balances'] as $balance) {
                    if($balance['asset'] == 'USDT'){
                        $wallet->balance = $balance['free'] + $balance['locked'];
                    }
                    else{
                        $asset = Asset::where('symbol', $balance['asset'])->first();
                        // print_r($balance);
                        if ($asset != null) {
                            $asset_wallet = $wallet->assets()->where('asset_id', $asset->id)->first();
                            if($asset_wallet == null){
                                AssetWallet::create([
                                    'wallet_id' => $wallet->id,
                                    'asset_id' => $asset->id,
                                    'amount' => $balance['free'] + $balance['locked']
                                ]);
                            }
                            // $assets[$asset->id] = ['amount' => $balance['free'] + $balance['locked']];
                            // print_r($asset['name'] . ' : ' . $balance['free'] . ' + ' . $balance['locked'] . ' = ' .  $balance['free'] + $balance['locked'] . "<br>");
                        }
                    }
                }
            }

            // $wallet->assets()->sync($assets);

            // print_r("<br>Assets Transaction <br>");
            foreach ($wallet->assets as $asset) {
                // print_r($asset->name . "<br>");
                $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();

                $url = "/api/v3/allOrders";
                $params = [
                    'symbol' => $asset->binance_symbol,
                ];
                $type = "GET";
                $all_orders = Binance::call(true, "SPOT", $url, $params, $type);
                if ($all_orders != null) {
                    if (array_key_exists('code', $all_orders) != 1) {
                        foreach ($all_orders as $k_order => $order) {
                            $type = '';
                            if ($order['side'] == 'BUY') {
                                $type = 0;
                            } else if ($order['side'] == 'SELL') {
                                $type = 1;
                            }

                            $negative = -1;
                            if ($type == 0) {
                                $negative = 1;
                            }
                            if ($order['status'] == 'NEW') {
                                if ($asset_wallet != null) {
                                    try {
                                        $asset_transaction = AssetTransaction::where('order_id', $order['orderId'])->first();
                                        if ($asset_transaction == null) {
                                            $asset_wallet->transactions()->create([
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $order['price'],
                                                'amount' => $negative * $order['origQty'],
                                                'total' => $order['price'] * $negative * $order['origQty'],
                                                'status' => 0,
                                                'integrated' => 1,
                                                'time' => date('Y-m-d H:i:s', $order['time'] / 1000)
                                            ]);
                                        } else {
                                            $asset_transaction->update([
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $order['price'],
                                                'amount' => $negative * $order['origQty'],
                                                'total' => $order['price'] * $negative * $order['origQty'],
                                                'status' => 0,
                                                'integrated' => 1,
                                                'time' => date('Y-m-d H:i:s', $order['time'] / 1000)
                                            ]);
                                        }
                                    } catch (\Throwable $th) {
                                        throw $th;
                                    }
                                    // $order['time'] = date('d-m-Y H:i:s', $order['time'] / 1000);
                                    // print_r("NEW " . $order['time'] . " : " . $order['side'] . ' ' . $order['origQty'] . ' x ' . $order['price'] . ' = ' . $order['origQty'] * $order['price'] . '<br><br>');
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
                                // print_r("<br>");
                                if ($asset_wallet != null) {
                                    try {
                                        $asset_transaction = AssetTransaction::where('order_id', $order['orderId'])->first();
                                        if ($asset_transaction == null) {
                                            $asset_wallet->transactions()->create([
                                                'trade_id' => $myTrades['id'],
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $myTrades['price'],
                                                'amount' => $negative * $myTrades['qty'],
                                                'fee' => $myTrades['commission'],
                                                'total' => $myTrades['price'] * $negative * $myTrades['qty'],
                                                'status' => 1,
                                                'integrated' => 1,
                                                'time' => date('Y-m-d H:i:s', $myTrades['time'] / 1000)
                                            ]);
                                        } else {
                                            $asset_transaction->update([
                                                'trade_id' => $myTrades['id'],
                                                'order_id' => $order['orderId'],
                                                'type' => $type,
                                                'price' => $myTrades['price'],
                                                'amount' => $negative * $myTrades['qty'],
                                                'fee' => $myTrades['commission'],
                                                'total' => $myTrades['price'] * $negative * $myTrades['qty'],
                                                'status' => 1,
                                                'integrated' => 1,
                                                'time' => date('Y-m-d H:i:s', $myTrades['time'] / 1000)
                                            ]);
                                        }
                                    } catch (\Throwable $th) {
                                        throw $th;
                                    }
                                }
                                // $myTrades['time'] = date('d-m-Y H:i:s', $myTrades['time'] / 1000);
                                // print_r("FILLED " . $myTrades['time'] . " : " . $order['side'] . ' ' . $myTrades['qty'] . ' x ' . $myTrades['price'] . ' = ' . $myTrades['qty'] * $myTrades['price'] . '<br><br>');
                            }
                        }
                    }
                }

                // Hitung Average Price
                // dd($asset_wallet->transactions);
                $sumAmount = $asset_wallet->transactions()->where('status', 1)->where('trade_id', '<>', 913836)->sum('amount');
                $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->where('trade_id', '<>', 913836)->whereRaw('type = 0 or type = 4')->sum('amount');
                $sumTotal = $asset_wallet->transactions()->where('status', 1)->where('trade_id', '<>', 913836)->sum('total');
                $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->where('trade_id', '<>', 913836)->whereRaw('type = 0 or type = 4')->sum('total');
                if ($sumTotal > 0 and $sumAmountBought > 0) {
                    $average_price = $sumTotalBought / $sumAmountBought;
                } else {
                    $average_price = $asset->current_price;
                }

                // Hitung PNL
                $difference = $asset->current_price - $average_price;
                $pnl = $difference * $sumAmount;
                $totalAverage = $average_price * $sumAmount;
                $totalCurrent = $asset->current_price * $sumAmount;
                $pnlPercentage = 0;
                if ($totalAverage > 0) {
                    if ($totalAverage > $totalCurrent) {
                        $pnlPercentage = $totalAverage - $totalCurrent;
                        $pnlPercentage = ($pnlPercentage / $totalAverage) * 100;
                    } else if ($totalAverage < $totalCurrent) {
                        $pnlPercentage = $totalCurrent - $totalAverage;
                        $pnlPercentage = ($pnlPercentage / $totalAverage) * 100;
                    }
                }
                $asset->pivot->update([
                    'average_price' => $average_price,
                    'amount' => $sumAmount,
                    'total' => $totalAverage,
                    'pnl' => $pnl,
                    'pnl_percentage' => $pnlPercentage,
                ]);

                $total_pnl_wallet += $pnl;
                $total_assets_wallet += $totalAverage;
            }
        } else {
            foreach ($wallet->assets as $asset) {
                $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();

                // Hitung Average Price
                $sumAmount = $asset_wallet->transactions()->where('status', 1)->sum('amount');
                $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('amount');
                $sumTotal = $asset_wallet->transactions()->where('status', 1)->sum('total');
                $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('total');
                if ($sumTotal > 0 and $sumAmountBought > 0) {
                    $average_price = $sumTotalBought / $sumAmountBought;
                } else {
                    $average_price = $asset->current_price;
                }

                // Hitung PNL
                $difference = $asset->current_price - $average_price;
                $pnl = $difference * $sumAmount;
                $totalAverage = $average_price * $sumAmount;
                $totalCurrent = $asset->current_price * $sumAmount;
                $pnlPercentage = 0;
                if ($totalAverage > 0) {
                    if ($totalAverage > $totalCurrent) {
                        $pnlPercentage = $totalAverage - $totalCurrent;
                        $pnlPercentage = ($pnlPercentage / $totalAverage) * 100;
                    } else if ($totalAverage < $totalCurrent) {
                        $pnlPercentage = $totalCurrent - $totalAverage;
                        $pnlPercentage = ($pnlPercentage / $totalAverage) * 100;
                    }
                }
                $asset->pivot->update([
                    'average_price' => $average_price,
                    'amount' => $sumAmount,
                    'total' => $totalAverage,
                    'pnl' => $pnl,
                    'pnl_percentage' => $pnlPercentage,
                ]);

                $total_pnl_wallet += $pnl;
                $total_assets_wallet += $totalAverage;
            }
        }
        $wallet->update([
            'pnl' => $total_pnl_wallet,
            'amount_of_assets' => $total_assets_wallet,
        ]);

        $data = $wallet;
        $data['assets'] = $wallet->assets;
        return response()->json($data);
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
        if($wallet->binance_api_key != null){
            if($request->binance_api_key == '' or $request->binance_api_key == null){
                Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
                foreach ($wallet->assets as $asset) {
                    $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();
                    $detaches = [];
                    foreach($asset_wallet->transactions as $transaction){
                        if($transaction->status == 0 and $transaction->integrated == 1){
                            $url = "/api/v3/order";
                            $params = [
                                'symbol' => $asset->binance_symbol,
                                'orderId' => $transaction->order_id,
                            ];
                            $type = "DELETE";
                            $delete_order = Binance::call(true, "SPOT", $url, $params, $type);

                            $detaches[] = $transaction;
                        }
                    }

                    foreach ($detaches as $key => $value) {
                        // dd($value);
                        $asset_wallet->transactions()->where('id', $value->id)->delete();
                    }
                }
            }
        }

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
