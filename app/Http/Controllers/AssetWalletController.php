<?php

namespace App\Http\Controllers;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\AssetTransaction;
use App\Models\AssetWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        if ($asset_wallet == null) {
            return abort(404);
        } else {
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
                        $found = false;
                        foreach ($account_info['balances'] as $balance) {
                            if ($balance['asset'] == $asset->symbol) {
                                $found = true;
                                $asset_wallet->update([
                                    'amount' => $balance['free'] + $balance['locked']
                                ]);
                            }
                        }
                        if (!$found) {
                            $asset_wallet->update([
                                'amount' => 0
                            ]);
                        }
                    } else if (array_key_exists("msg", $account_info)) {
                        if (str_contains($account_info['msg'], 'API-key')) {
                            return response()->json(['error' => 'Invalid'], 404);
                        }
                    }
                }
            } else {
                $url = "/sapi/v3/asset/getUserAsset";
                $params = [];
                $type = "POST";
                $user_assets = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
                if ($user_assets != null) {
                    if (array_key_exists("msg", $user_assets)) {
                        if (str_contains($user_assets['msg'], 'API-key')) {
                            return response()->json(['error' => 'Invalid'], 404);
                        }
                    } else {
                        $found = false;
                        foreach ($user_assets as $user_asset) {
                            if ($user_asset['asset'] == $asset->symbol) {
                                $found = true;
                                $asset_wallet->update([
                                    'amount' => $user_asset['free'] + $user_asset['locked']
                                ]);
                            }
                        }
                        if (!$found) {
                            $asset_wallet->update([
                                'amount' => 0
                            ]);
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
                                    $asset_transaction = AssetTransaction::where('order_id', strval($order['orderId']))->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => strval($order['orderId']),
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
                                            'order_id' => strval($order['orderId']),
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
                                    // throw $th;
                                }
                                // $order['time'] = date('d-m-Y H:i:s', $order['time'] / 1000);
                                // print_r("NEW " . $order['time'] . " : " . $order['side'] . ' ' . $order['origQty'] . ' x ' . $order['price'] . ' = ' . $order['origQty'] * $order['price'] . '<br><br>');
                            }
                        } else if ($order['status'] == 'FILLED') {
                            $url_trades = "/api/v3/myTrades";
                            $params_trades = [
                                'symbol' => $asset->binance_symbol,
                                'orderId' => strval($order['orderId'])
                            ];
                            $type_trades = "GET";
                            $myTrades = Binance::call($wallet->demo, "SPOT", $url_trades, $params_trades, $type_trades);
                            if($myTrades != null){
                                $myTrades = $myTrades[0];
                            }
                            // print_r($myTrades);
                            // print_r("<br>");
                            if ($asset_wallet != null) {
                                try {
                                    $asset_transaction = AssetTransaction::where('order_id', strval($order['orderId']))->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'trade_id' => $myTrades['id'],
                                            'order_id' => strval($order['orderId']),
                                            'type' => $type,
                                            'price' => $myTrades['price'],
                                            'amount' => $negative * $myTrades['qty'],
                                            'fee' => $myTrades['commission'],
                                            'total' => $myTrades['price'] * $negative * $myTrades['qty'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $myTrades['time'] / 1000)
                                        ]);
                                    } else if ($asset_transaction->status == 0) {
                                        $asset_transaction->update([
                                            'trade_id' => $myTrades['id'],
                                            'order_id' => strval($order['orderId']),
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
                                    // throw $th;
                                }
                            }
                            // $myTrades['time'] = date('d-m-Y H:i:s', $myTrades['time'] / 1000);
                            // print_r("FILLED " . $myTrades['time'] . " : " . $order['side'] . ' ' . $myTrades['qty'] . ' x ' . $myTrades['price'] . ' = ' . $myTrades['qty'] * $myTrades['price'] . '<br><br>');
                        }
                    }
                }
            }

            // print_r("<pre>");
            $url = "/sapi/v1/capital/deposit/hisrec";
            $params = [
                'coin' => $asset->symbol,
            ];
            $type = "GET";
            $deposit_history = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($deposit_history != null) {
                if (array_key_exists('code', $deposit_history) != 1) {
                    foreach ($deposit_history as $deposit) {
                        $type = 3;

                        $negative = -1;
                        if ($type == 3) {
                            $negative = 1;
                        }
                        if ($asset_wallet != null) {
                            $asset_transaction = AssetTransaction::where('order_id', $deposit['id'])->first();
                            if ($asset_transaction == null) {
                                $asset_wallet->transactions()->create([
                                    'order_id' => $deposit['id'],
                                    'type' => $type,
                                    'amount' => $negative * $deposit['amount'],
                                    'status' => 1,
                                    'integrated' => 1,
                                    'time' => date('Y-m-d H:i:s', $order['insertTime'] / 1000)
                                ]);
                            } else {
                                $asset_transaction->update([
                                    'order_id' => $deposit['id'],
                                    'type' => $type,
                                    'amount' => $negative * $deposit['amount'],
                                    'status' => 1,
                                    'integrated' => 1,
                                    'time' => date('Y-m-d H:i:s', $order['insertTime'] / 1000)
                                ]);
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/capital/withdraw/history";
            $params = [
                'coin' => $asset->symbol,
            ];
            $type = "GET";
            $withdraw_history = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($withdraw_history != null) {
                if (array_key_exists('code', $withdraw_history) != 1) {
                    foreach ($withdraw_history as $withdraw) {
                        $type = 2;

                        $negative = -1;
                        if ($type == 3) {
                            $negative = 1;
                        }
                        if ($asset_wallet != null) {
                            $asset_transaction = AssetTransaction::where('order_id', $withdraw['id'])->first();
                            if ($asset_transaction == null) {
                                $asset_wallet->transactions()->create([
                                    'order_id' => $withdraw['id'],
                                    'type' => $type,
                                    'amount' => $negative * $withdraw['amount'],
                                    'fee' => $withdraw['transactionFee'],
                                    'status' => 1,
                                    'integrated' => 1,
                                    'time' => date('Y-m-d H:i:s', $withdraw['applyTime'])
                                ]);
                            } else {
                                $asset_transaction->update([
                                    'order_id' => $withdraw['id'],
                                    'type' => $type,
                                    'amount' => $negative * $withdraw['amount'],
                                    'fee' => $withdraw['transactionFee'],
                                    'status' => 1,
                                    'integrated' => 1,
                                    'time' => date('Y-m-d H:i:s', $withdraw['applyTime'])
                                ]);
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'MAIN_UMFUTURE',
            ];
            $type = "GET";
            $transfer_out_usd_future = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_out_usd_future != null) {
                if (array_key_exists('code', $transfer_out_usd_future) != 1) {
                    if ($transfer_out_usd_future['total'] > 0) {
                        foreach ($transfer_out_usd_future['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 2;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'MAIN_CMFUTURE',
            ];
            $type = "GET";
            $transfer_out_coin_future = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_out_coin_future != null) {
                if (array_key_exists('code', $transfer_out_coin_future) != 1) {
                    if ($transfer_out_coin_future['total'] > 0) {
                        foreach ($transfer_out_coin_future['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 2;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'MAIN_MARGIN',
            ];
            $type = "GET";
            $transfer_out_margin = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_out_margin != null) {
                if (array_key_exists('code', $transfer_out_margin) != 1) {
                    if ($transfer_out_margin['total'] > 0) {
                        foreach ($transfer_out_margin['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 2;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'MAIN_FUNDING',
            ];
            $type = "GET";
            $transfer_out_funding = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_out_funding != null) {
                if (array_key_exists('code', $transfer_out_funding) != 1) {
                    if ($transfer_out_funding['total'] > 0) {
                        foreach ($transfer_out_funding['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 2;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'UMFUTURE_MAIN',
            ];
            $type = "GET";
            $transfer_in_usd_future = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_in_usd_future != null) {
                if (array_key_exists('code', $transfer_in_usd_future) != 1) {
                    if ($transfer_in_usd_future['total'] > 0) {
                        foreach ($transfer_in_usd_future['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 3;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'CMFUTURE_MAIN',
            ];
            $type = "GET";
            $transfer_in_coin_future = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_in_coin_future != null) {
                if (array_key_exists('code', $transfer_in_coin_future) != 1) {
                    if ($transfer_in_coin_future['total'] > 0) {
                        foreach ($transfer_in_coin_future['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 3;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'MARGIN_MAIN',
            ];
            $type = "GET";
            $transfer_in_margin = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_in_margin != null) {
                if (array_key_exists('code', $transfer_in_margin) != 1) {
                    if ($transfer_in_margin['total'] > 0) {
                        foreach ($transfer_in_margin['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 3;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $url = "/sapi/v1/asset/transfer";
            $params = [
                'type' => 'FUNDING_MAIN',
            ];
            $type = "GET";
            $transfer_in_funding = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
            if ($transfer_in_funding != null) {
                if (array_key_exists('code', $transfer_in_funding) != 1) {
                    if ($transfer_in_funding['total'] > 0) {
                        foreach ($transfer_in_funding['rows'] as $transfer) {
                            if ($transfer['asset'] == $asset->symbol) {
                                $type = 3;

                                $negative = -1;
                                if ($type == 3) {
                                    $negative = 1;
                                }
                                if ($asset_wallet != null) {
                                    $asset_transaction = AssetTransaction::where('order_id', $transfer['tranId'])->first();
                                    if ($asset_transaction == null) {
                                        $asset_wallet->transactions()->create([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    } else {
                                        $asset_transaction->update([
                                            'order_id' => $transfer['tranId'],
                                            'type' => $type,
                                            'amount' => $negative * $transfer['amount'],
                                            'status' => 1,
                                            'integrated' => 1,
                                            'time' => date('Y-m-d H:i:s', $transfer['timestamp'] / 1000)
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Hitung Average Price
            $sumAmount = $asset_wallet->amount;
            $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('amount');
            $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('total');

            if ($sumTotalBought > 0 and $sumAmountBought > 0) {
                $average_price = $sumTotalBought / $sumAmountBought;
            } else {
                $average_price = $asset->current_price;
            }
            // $asset_wallet->update([
            //     'average_price' => $average_price,
            // ]);

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
            $asset_wallet->update([
                'average_price' => $average_price,
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
            $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('amount');
            // print_r($sumAmountBought);
            $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('total');
            if ($sumTotalBought > 0 and $sumAmountBought > 0) {
                $average_price = $sumTotalBought / $sumAmountBought;
            } else {
                $average_price = $asset->current_price;
            }
            // $asset_wallet->update([
            //     'average_price' => $average_price,
            //     'amount' => $sumAmount,
            // ]);

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
            $asset_wallet->update([
                'amount' => $sumAmount,
                'average_price' => $average_price,
                'total' => $totalAverage,
                'pnl' => $pnl,
                'pnl_percentage' => $pnlPercentage,
            ]);

            $totalAsset = $wallet->assets->sum('pivot.total');
            $totalPNL = $wallet->assets->sum('pivot.pnl');

            if ($wallet->deleted_at == null) {
                $wallet->update([
                    'pnl' => $totalPNL,
                    'amount_of_assets' => $totalAsset,
                ]);
            }

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

    public function report(Wallet $wallet, Asset $asset)
    {
        $asset_wallet = AssetWallet::where('asset_id', $asset->id)->where('wallet_id', $wallet->id)->first();
        if ($asset_wallet == null) {
            return abort(404);
        } else {
            $groupAssetTransactions = AssetTransaction::select(DB::raw('DATE(asset_transactions.time) as transaction_date'), DB::raw('SUM(asset_transactions.amount) as total_quantity'), DB::raw('SUM(asset_transactions.total) as total_value'))
                ->join('asset_wallet', 'asset_transactions.asset_wallet_id', '=', 'asset_wallet.id')
                ->join('assets', 'asset_wallet.asset_id', '=', 'assets.id')
                ->where('asset_wallet.id', '=', $asset_wallet->id)
                ->where('asset_transactions.status', '=', 1)
                ->groupBy('transaction_date')
                ->orderBy('transaction_date', 'desc')
                ->get();


            // dd($groupAssetTransactions);
            $totalQuantity = (float)$asset_wallet->amount;
            $totalValue = (float)$asset_wallet->total;
            // $totalQuantity = 0;
            // $totalValue = 0;
            $quantityData = [];
            $valueData = [];
            foreach ($groupAssetTransactions as $transaction) {
                // $totalValue += (float)$transaction->total_value;
                // $totalQuantity += (float)$transaction->total_quantity;
                array_unshift($quantityData, [
                    'x' => $transaction->transaction_date,
                    'y' => $totalQuantity < 0 ? 0 : $totalQuantity
                ]);
                array_unshift($valueData, [
                    'x' => $transaction->transaction_date,
                    'y' => $totalValue < 0 ? 0 : $totalValue
                ]);
                // $valueData[] = [
                //     'x' => $transaction->transaction_date,
                //     'y' => $totalValue < 0 ? 0 : $totalValue
                // ];
                // $quantityData[] = [
                //     'x' => $transaction->transaction_date,
                //     'y' => $totalQuantity < 0 ? 0 : $totalQuantity
                // ];
                $totalQuantity -= (float)$transaction->total_quantity;
                if ($totalQuantity > 0) {
                    $totalValue -= (float)$transaction->total_value;
                } else {
                    $totalValue = 0;
                }
            }

            return view('users.wallets.assets.report', compact('wallet', 'asset', 'asset_wallet', 'quantityData', 'valueData', 'groupAssetTransactions'));
        }
    }

    public function report_print(Wallet $wallet, Asset $asset)
    {
        $asset_wallet = AssetWallet::where('asset_id', $asset->id)->where('wallet_id', $wallet->id)->first();
        if ($asset_wallet == null) {
            return abort(404);
        } else {
            $groupAssetTransactions = AssetTransaction::select(DB::raw('DATE(asset_transactions.time) as transaction_date'), DB::raw('SUM(asset_transactions.amount) as total_quantity'), DB::raw('SUM(asset_transactions.total) as total_value'))
                ->join('asset_wallet', 'asset_transactions.asset_wallet_id', '=', 'asset_wallet.id')
                ->join('assets', 'asset_wallet.asset_id', '=', 'assets.id')
                ->where('asset_wallet.id', '=', $asset_wallet->id)
                ->where('asset_transactions.status', '=', 1)
                ->groupBy('transaction_date')
                ->orderBy('transaction_date', 'desc')
                ->get();


            $totalQuantity = (float)$asset_wallet->amount;
            $totalValue = (float)$asset_wallet->total;
            // $totalQuantity = 0;
            // $totalValue = 0;
            $quantityData = [];
            $valueData = [];
            foreach ($groupAssetTransactions as $transaction) {
                // $totalValue += (float)$transaction->total_value;
                // $totalQuantity += (float)$transaction->total_quantity;
                array_unshift($quantityData, [
                    'x' => $transaction->transaction_date,
                    'y' => $totalQuantity < 0 ? 0 : $totalQuantity
                ]);
                array_unshift($valueData, [
                    'x' => $transaction->transaction_date,
                    'y' => $totalValue < 0 ? 0 : $totalValue
                ]);
                // $valueData[] = [
                //     'x' => $transaction->transaction_date,
                //     'y' => $totalValue < 0 ? 0 : $totalValue
                // ];
                // $quantityData[] = [
                //     'x' => $transaction->transaction_date,
                //     'y' => $totalQuantity < 0 ? 0 : $totalQuantity
                // ];
                $totalQuantity -= (float)$transaction->total_quantity;
                if ($totalQuantity > 0) {
                    $totalValue -= (float)$transaction->total_value;
                } else {
                    $totalValue = 0;
                }
            }

            return view('users.wallets.assets.report_print', compact('wallet', 'asset', 'asset_wallet', 'quantityData', 'valueData', 'groupAssetTransactions'));
        }
    }
}
