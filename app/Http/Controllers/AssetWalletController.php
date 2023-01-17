<?php

namespace App\Http\Controllers;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\AssetTransaction;
use App\Models\AssetWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AssetWalletController extends Controller
{

    public function add(Wallet $wallet, Request $request)
    {
        $asset = Asset::where('coin_gecko_id', $request->id)->first();
        if ($wallet->assets()->find($asset->id) == null) {
            $add = $wallet->assets()->attach($asset->id);
        } else {
            return redirect()->route('user.wallet.detail', $wallet)->withError('Aset sudah dimiliki dompet tersebut');
        }
        if ($wallet->assets()->find($asset->id)) {
            return redirect()->route('user.wallet.detail', $wallet)->withSuccess('Aset berhasil ditambahkan');
        } else {
            return redirect()->route('user.wallet.detail', $wallet)->withError('Aset gagal ditambahkan');
        }
    }

    public function show(Wallet $wallet, Asset $asset)
    {
        $asset_wallet = AssetWallet::where('asset_id', $asset->id)->where('wallet_id', $wallet->id)->first();
        if($asset_wallet == null){
            return abort(404);
        }
        else{
            return view('users.wallets.assets.show', compact('wallet', 'asset', 'asset_wallet'));
        }
    }


    public function info(Wallet $wallet, Asset $asset)
    {
        return view('users.wallets.assets.info', compact('wallet', 'asset'));
    }

    public function load(Wallet $wallet, Asset $asset)
    {
        if ($wallet->binance_api_key != null) {
            // print_r("<pre>");
            Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
            $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();

            if ($wallet->demo == true) {
                $url = "/api/v3/account";
                $params = [];
                $type = "GET";
                $account_info = Binance::call($wallet->demo, "SPOT", $url, $params, $type);

                if ($account_info != null) {
                    if (array_key_exists("balances", $account_info)) {
                        foreach ($account_info['balances'] as $balance) {
                            if ($balance['asset'] == $asset->symbol) {
                                $asset_wallet->update([
                                    'amount' => $balance['free'] + $balance['locked']
                                ]);
                            }
                        }
                    } else if (array_key_exists("msg", $account_info)) {
                        if (str_contains($account_info['msg'], 'API-key')) {
                            return response()->json(['error' => 'Invalid'], 404);
                        }
                    }
                }
            }
            else{
                $url = "/sapi/v3/asset/getUserAsset";
                $params = [];
                $type = "POST";
                $user_assets = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
                if ($user_assets != null) {
                    if (array_key_exists("msg", $user_assets)) {
                        if (str_contains($user_assets['msg'], 'API-key')) {
                            return response()->json(['error' => 'Invalid'], 404);
                        }
                    }
                    else{
                        foreach ($user_assets as $user_asset) {
                            if ($user_asset['asset'] == $asset->symbol) {
                                $asset_wallet->update([
                                    'amount' => $user_asset['free'] + $user_asset['locked']
                                ]);
                            }
                        }
                    }
                }
            }

            $url = "/api/v3/allOrders";
            $params = [
                'symbol' => $asset->binance_symbol,
            ];
            $type = "GET";
            $all_orders = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($all_orders != null) {
                if (array_key_exists('code', $all_orders) != 1) {
                    foreach ($all_orders as $k_order => $order) {
                        // print_r($order);
                        // print_r('<br>');
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
                                    //throw $th;
                                }
                                $order['time'] = date('d-m-Y H:i:s', $order['time'] / 1000);
                                // print_r("NEW " . $order['time'] . " : " . $order['side'] . ' ' . $order['origQty'] . ' x ' . $order['price'] . ' = ' . $order['origQty'] * $order['price'] . '<br><br>');
                            }
                        } else if ($order['status'] == 'FILLED') {
                            $url_trades = "/api/v3/myTrades";
                            $params_trades = [
                                'symbol' => $asset->binance_symbol,
                                'orderId' => $order['orderId']
                            ];
                            $type_trades = "GET";
                            $myTrades = Binance::call($wallet->demo, "SPOT", $url_trades, $params_trades, $type_trades)[0];
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
                            $myTrades['time'] = date('d-m-Y H:i:s', $myTrades['time'] / 1000);
                            // print_r("FILLED " . $myTrades['time'] . " : " . $order['side'] . ' ' . $myTrades['qty'] . ' x ' . $myTrades['price'] . ' = ' . $myTrades['qty'] * $myTrades['price'] . '<br><br>');
                        }
                    }
                }
            }

            // Hitung Average Price
            $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('amount');
            $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('total');

            if ($sumTotalBought > 0 and $sumAmountBought > 0) {
                $average_price = $sumTotalBought / $sumAmountBought;
            } else {
                $average_price = $asset->current_price;
            }
            $asset_wallet->update([
                'average_price' => $average_price,
            ]);

            // Hitung PNL
            $difference = $asset->current_price - $asset_wallet->average_price;
            $pnl = $difference * $asset_wallet->amount;
            $totalAverage = $asset_wallet->average_price * $asset_wallet->amount;
            $totalCurrent = $asset->current_price * $asset_wallet->amount;
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
            $asset_wallet->update([
                'total' => $totalAverage,
                'pnl' => $pnl,
                'pnl_percentage' => $pnlPercentage,
            ]);

            $data = $asset_wallet;
            $data['transactions'] = $asset_wallet->transactions()->orderBy('time', 'desc')->get();
            return response()->json($data);
        } else {
            $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();

            // Hitung Average Price
            $sumAmount = $asset_wallet->transactions()->where('status', 1)->sum('amount');
            $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('amount');
            // print_r($sumAmountBought);
            $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0 or type = 4')->sum('total');
            if ($sumTotalBought > 0 and $sumAmountBought > 0) {
                $average_price = $sumTotalBought / $sumAmountBought;
            } else {
                $average_price = $asset->current_price;
            }
            $asset_wallet->update([
                'average_price' => $average_price,
                'amount' => $sumAmount,
            ]);

            // Hitung PNL
            $difference = $asset->current_price - $asset_wallet->average_price;
            $pnl = $difference * $asset_wallet->amount;
            $totalAverage = $asset_wallet->average_price * $asset_wallet->amount;
            $totalCurrent = $asset->current_price * $asset_wallet->amount;
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
            $asset_wallet->update([
                'total' => $totalAverage,
                'pnl' => $pnl,
                'pnl_percentage' => $pnlPercentage,
            ]);

            $data = $asset_wallet;
            $data['transactions'] = $asset_wallet->transactions()->orderBy('time', 'desc')->get();
            return response()->json($data);
        }
    }

    public function destroy(Wallet $wallet, Asset $asset)
    {
        foreach ($wallet->assets as $ass) {
            $ass_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $ass->id)->first();
            if ($ass_wallet != null) {
                foreach ($ass_wallet->transactions as $trans) {
                    if ($trans->type == 0 or $trans->type == 1) {
                        $wallet->balance = $wallet->balance + $trans->total;
                        $wallet->save();
                    }
                }
            }
        }
        $wallet->assets()->detach($asset);
        return redirect()->route('user.wallet.detail', $wallet)->withSuccess('Aset berhasil dihapus');
    }
}
