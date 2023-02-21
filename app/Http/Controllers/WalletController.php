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

class WalletController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('portfolio')) {
            $data = User::findOrFail(Auth::id());
            return view('users.wallets.list', compact('data'));
        } else {
            abort(403);
        }
    }

    public function store(StoreWalletRequest $request)
    {
        if (Auth::user()->hasPermissionTo('portfolio-tambah')) {
            if (Auth::user()->max_wallets > 0 and Auth::user()->wallets->count() < Auth::user()->max_wallets) {
                if ($request['binance_api_key'] == null and $request['balance'] == null) {
                    return redirect()->back()->withError('Dompet gagal ditambahkan');
                } else if ($request['binance_api_key'] != null) {
                    $request['balance'] = 0;

                    Binance::auth($request['binance_api_key'], $request['binance_secret_key']);
                    $url = "/sapi/v1/account/status";
                    $params = [];
                    $type = "GET";
                    $account_status = Binance::call(false, "SPOT", $url, $params, $type);

                    if (array_key_exists("msg", $account_status)) {
                        if (str_contains($account_status['msg'], 'Invalid')) {
                            $request['demo'] = true;
                        } else {
                            $request['demo'] = false;
                        }
                    } else {
                        $request['demo'] = false;
                    }
                }
                $wallet = Auth::user()->wallets()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Dompet gagal ditambahkan');
                }
            } else if (Auth::user()->max_wallets == -1) {
                if ($request['binance_api_key'] == null and $request['balance'] == null) {
                    return redirect()->back()->withError('Dompet gagal ditambahkan');
                } else if ($request['binance_api_key'] != null) {
                    $request['balance'] = 0;

                    Binance::auth($request['binance_api_key'], $request['binance_secret_key']);
                    $url = "/sapi/v1/account/status";
                    $params = [];
                    $type = "GET";
                    $account_status = Binance::call(false, "SPOT", $url, $params, $type);

                    if (array_key_exists("msg", $account_status)) {
                        if (str_contains($account_status['msg'], 'Invalid')) {
                            $request['demo'] = true;
                        } else {
                            $request['demo'] = false;
                        }
                    } else {
                        $request['demo'] = false;
                    }
                }
                $wallet = Auth::user()->wallets()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Dompet gagal ditambahkan');
                }
            } else {
                return redirect()->back()->withError('Jumlah dompet yang dimiliki pengguna sudah mencapai batasnya');
            }
        } else {
            abort(403);
        }
    }

    public function show(Wallet $wallet)
    {
        if (Auth::user()->hasPermissionTo('portfolio-daftar')) {
            return view('users.wallets.show', compact('wallet'));
        } else {
            abort(403);
        }
    }

    public function load(Wallet $wallet)
    {
        $total_assets_wallet = 0;
        $total_pnl_wallet = 0;
        if ($wallet->binance_api_key != null and $wallet->deleted_at == null) {
            // print_r("<pre>");
            Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);

            if ($wallet->demo == true) {
                $url = "/api/v3/account";
                $params = [];
                $type = "GET";
                $account_info = Binance::call($wallet->demo, "SPOT", $url, $params, $type);

                // print_r("Assets Balance <br>");
                if ($account_info != null) {
                    if (array_key_exists("balances", $account_info)) {
                        foreach ($account_info['balances'] as $balance) {
                            if ($balance['asset'] == 'USDT') {
                                $wallet->update([
                                    'balance' => $balance['free'] + $balance['locked']
                                ]);
                            } else {
                                $asset = Asset::where('symbol', $balance['asset'])->first();
                                // print_r($balance);
                                if ($asset != null) {
                                    $asset_wallet = $wallet->assets()->where('asset_id', $asset->id)->first();
                                    if ($asset_wallet == null) {
                                        AssetWallet::create([
                                            'wallet_id' => $wallet->id,
                                            'asset_id' => $asset->id,
                                            'amount' => $balance['free'] + $balance['locked']
                                        ]);
                                    } else {
                                        $asset_wallet->pivot->update([
                                            'amount' => $balance['free'] + $balance['locked']
                                        ]);
                                    }
                                }
                            }
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
                        foreach ($user_assets as $user_asset) {
                            if ($user_asset['asset'] == 'USDT') {
                                $wallet->update([
                                    'balance' => $user_asset['free'] + $user_asset['locked']
                                ]);
                            } else {
                                $asset = Asset::where('symbol', $user_asset['asset'])->first();
                                // print_r($balance);
                                if ($asset != null) {
                                    $asset_wallet = $wallet->assets()->where('asset_id', $asset->id)->first();
                                    if ($asset_wallet == null) {
                                        AssetWallet::create([
                                            'wallet_id' => $wallet->id,
                                            'asset_id' => $asset->id,
                                            'amount' => $user_asset['free'] + $user_asset['locked']
                                        ]);
                                    } else {
                                        $asset_wallet->pivot->update([
                                            'amount' => $user_asset['free'] + $user_asset['locked']
                                        ]);
                                    }
                                }
                            }
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
                $all_orders = Binance::call($wallet->demo, "SPOT", $url, $params, $type);
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
                                        // throw $th;
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

                // dd($withdraw_history);

                // Hitung Average Price
                // dd($asset_wallet->transactions);
                $sumAmount = $asset_wallet->amount;
                $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('amount');
                $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('total');
                if ($sumTotalBought > 0 and $sumAmountBought > 0) {
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
                    'total' => $totalAverage,
                    'pnl' => $pnl,
                    'pnl_percentage' => $pnlPercentage,
                ]);

                $total_pnl_wallet += $pnl;
                $total_assets_wallet += $totalAverage;
            }
        } else if ($wallet->deleted_at == null) {
            foreach ($wallet->assets as $asset) {
                $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();

                // Hitung Average Price
                $sumAmount = $asset_wallet->transactions()->where('status', 1)->sum('amount');
                $sumAmountBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('amount');
                $sumTotalBought = $asset_wallet->transactions()->where('status', 1)->whereRaw('type = 0')->sum('total');
                if ($sumTotalBought > 0 and $sumAmountBought > 0) {
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
                    'amount' => $sumAmount,
                    'average_price' => $average_price,
                    'total' => $totalAverage,
                    'pnl' => $pnl,
                    'pnl_percentage' => $pnlPercentage,
                ]);

                $total_pnl_wallet += $pnl;
                $total_assets_wallet += $totalAverage;
            }
        }
        if ($wallet->deleted_at == null) {
            $wallet->update([
                'pnl' => $total_pnl_wallet,
                'amount_of_assets' => $total_assets_wallet,
            ]);
        }

        $data = $wallet;
        $data['assets'] = $wallet->assets;
        return response()->json($data);
    }

    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        if (Auth::user()->hasPermissionTo('portfolio-ubah')) {
            if ($wallet->binance_api_key != null) {
                if ($request->binance_api_key == '' or $request->binance_api_key == null) {
                    foreach ($wallet->assets as $asset) {
                        $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();
                        $detaches = [];
                        foreach ($asset_wallet->transactions as $transaction) {
                            if ($transaction->status == 0 and $transaction->integrated == 1) {
                                $url = "/api/v3/order";
                                $params = [
                                    'symbol' => $asset->binance_symbol,
                                    'orderId' => $transaction->order_id,
                                ];
                                $type = "DELETE";
                                $delete_order = Binance::call($wallet->demo, "SPOT", $url, $params, $type);

                                $detaches[] = $transaction;
                            }
                        }

                        foreach ($detaches as $key => $value) {
                            // dd($value);
                            $asset_wallet->transactions()->where('id', $value->id)->delete();
                        }
                    }
                } else if ($request->binance_api_key != $wallet->binance_api_key) {
                    Binance::auth($request->binance_api_key, $request->binance_secret_key);
                    $url = "/sapi/v1/account/status";
                    $params = [];
                    $type = "GET";
                    $account_status = Binance::call(false, "SPOT", $url, $params, $type);

                    if (array_key_exists("msg", $account_status)) {
                        if (str_contains($account_status['msg'], 'Invalid')) {
                            $request['demo'] = true;
                        } else {
                            $request['demo'] = false;
                        }
                    } else {
                        $request['demo'] = false;
                    }

                    $wallet->assets()->detach();
                }
            } else {
                if ($request->binance_api_key != '') {
                    Binance::auth($request->binance_api_key, $request->binance_secret_key);
                    $url = "/sapi/v1/account/status";
                    $params = [];
                    $type = "GET";
                    $account_status = Binance::call(false, "SPOT", $url, $params, $type);

                    if (array_key_exists("msg", $account_status)) {
                        if (str_contains($account_status['msg'], 'Invalid')) {
                            $request['demo'] = true;
                        } else {
                            $request['demo'] = false;
                        }
                    } else {
                        $request['demo'] = false;
                    }

                    $wallet->assets()->detach();
                }
            }


            $success = $wallet->update($request->all());
            if ($success) {
                return redirect()->back()->withSuccess('Dompet berhasil diubah');
            } else {
                return redirect()->back()->withError('Dompet gagal diubah');
            }
        } else {
            abort(403);
        }
    }

    public function destroy(Wallet $wallet)
    {
        if (Auth::user()->hasPermissionTo('portfolio-hapus')) {
            $delete = $wallet->delete();
            if ($delete) {
                return redirect()->back()->withSuccess('Dompet berhasil dinonaktifkan');
            } else {
                return redirect()->back()->withError('Dompet gagal dinonaktifkan');
            }
        } else {
            abort(403);
        }
    }

    public function restore(Wallet $wallet)
    {
        if (Auth::user()->hasPermissionTo('portfolio-hapus')) {
            $restore = $wallet->restore();
            if ($restore) {
                return redirect()->back()->withSuccess('Dompet berhasil diaktifkan');
            } else {
                return redirect()->back()->withError('Dompet gagal diaktifkan');
            }
        } else {
            abort(403);
        }
    }

    public function demography(){
        if (Auth::user()->hasPermissionTo('portfolio')) {

            $data = User::findOrFail(Auth::id());

            $dataPie = Wallet::select('name', 'amount_of_assets')
                    ->where('user_id', $data->id)
                    ->get();

            $labelsPie = [];
            $seriesPie = [];

            foreach ($dataPie as $row) {
                array_push($labelsPie, $row->name);
                array_push($seriesPie, (int)$row->amount_of_assets);
            }

            $optionsPie = [
                'series' => $seriesPie,
                'chart' => [
                    'type' => 'pie',
                ],
                'labels' => $labelsPie,
                'responsive' => [
                    [
                        'breakpoint' => 480,
                        'options' => [
                            'legend' => [
                                'position' => 'bottom'
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'position' => 'bottom',
                ]
            ];

            $dataBarTerbaik = Wallet::select('name', 'pnl')->where('user_id', $data->id)->where('pnl', '>', '0')->orderBy('pnl', 'desc')->take(3)->get();
            $labelsBarTerbaik = [];
            $seriesBarTerbaik = [];

            foreach ($dataBarTerbaik as $row) {
                array_push($labelsBarTerbaik, $row->name);
                array_push($seriesBarTerbaik, (int)$row->pnl);
            }

            $dataBarTerburuk = Wallet::select('name', 'pnl')->where('user_id', $data->id)->where('pnl', '<', '0')->orderBy('pnl', 'asc')->take(3)->get();
            $labelsBarTerburuk = [];
            $seriesBarTerburuk = [];

            foreach ($dataBarTerburuk as $row) {
                array_push($labelsBarTerburuk, $row->name);
                array_push($seriesBarTerburuk, (int)$row->pnl);
            }

            $walletAssetTerbanyak = Wallet::select('name', 'amount_of_assets', 'pnl')->where('user_id', $data->id)->orderBy('amount_of_assets', 'desc')->get();

            return view('users.wallets.demography', compact('data', 'optionsPie', 'seriesBarTerbaik', 'labelsBarTerbaik', 'seriesBarTerburuk', 'labelsBarTerburuk', 'walletAssetTerbanyak'));
        } else {
            abort(403);
        }
    }

    public function demography_print(){
        if (Auth::user()->hasPermissionTo('portfolio')) {

            $data = User::findOrFail(Auth::id());

            $dataPie = Wallet::select('name', 'amount_of_assets')
                    ->where('user_id', $data->id)
                    ->get();

            $labelsPie = [];
            $seriesPie = [];

            foreach ($dataPie as $row) {
                array_push($labelsPie, $row->name);
                array_push($seriesPie, (int)$row->amount_of_assets);
            }

            $optionsPie = [
                'series' => $seriesPie,
                'chart' => [
                    'type' => 'pie',
                    'width' => '100%',
                    'redrawOnWindowResize' => true,
                    'redrawOnParentResize' => true,
                ],
                'labels' => $labelsPie,
                'legend' => [
                    'position' => 'right',
                ]
            ];

            $dataBarTerbaik = Wallet::select('name', 'pnl')->where('user_id', $data->id)->where('pnl', '>', '0')->orderBy('pnl', 'desc')->take(3)->get();
            $labelsBarTerbaik = [];
            $seriesBarTerbaik = [];

            foreach ($dataBarTerbaik as $row) {
                array_push($labelsBarTerbaik, $row->name);
                array_push($seriesBarTerbaik, (int)$row->pnl);
            }

            $dataBarTerburuk = Wallet::select('name', 'pnl')->where('user_id', $data->id)->where('pnl', '<', '0')->orderBy('pnl', 'asc')->take(3)->get();
            $labelsBarTerburuk = [];
            $seriesBarTerburuk = [];

            foreach ($dataBarTerburuk as $row) {
                array_push($labelsBarTerburuk, $row->name);
                array_push($seriesBarTerburuk, (int)$row->pnl);
            }

            $walletAssetTerbanyak = Wallet::select('name', 'amount_of_assets', 'pnl')->where('user_id', $data->id)->orderBy('amount_of_assets', 'desc')->get();

            return view('users.wallets.demography_print', compact('data', 'optionsPie', 'seriesBarTerbaik', 'labelsBarTerbaik', 'seriesBarTerburuk', 'labelsBarTerburuk', 'walletAssetTerbanyak'));
        } else {
            abort(403);
        }
    }

    public function detail_demography(Wallet $wallet){
        if (Auth::user()->hasPermissionTo('portfolio-daftar')) {

            $data = User::findOrFail(Auth::id());

            $assetWallets = $wallet->assets;
            $assetAllocations = collect();

            foreach ($assetWallets as $asset) {
                // Hitung jumlah alokasi aset
                $total = $asset->pivot->total;
                $pnl = $asset->pivot->pnl;
                $amount = $asset->pivot->amount;
                $average_price = 0;
                if($amount > 0 and $total > 0){
                    $average_price = $total / $amount;
                }
                // Tambahkan data aset dan jumlah ke array alokasi aset
                $assetAllocations->push([
                    'asset_name' => $asset->name,
                    'amount' => $amount,
                    'pnl' => $pnl,
                    'total' => $total,
                    'average_price' => $average_price,
                ]);
            }

            $labelsPie = [];
            $seriesPie = [];
            foreach ($assetAllocations as $row) {
                array_push($labelsPie, $row['asset_name']);
                array_push($seriesPie, (int)$row['total']);
            }

            $optionsPie = [
                'series' => $seriesPie,
                'chart' => [
                    'type' => 'pie',
                ],
                'labels' => $labelsPie,
                'responsive' => [
                    [
                        'breakpoint' => 480,
                        'options' => [
                            'legend' => [
                                'position' => 'bottom'
                            ]
                        ]
                    ]
                ],
                'legend' => [
                    'position' => 'bottom',
                ]
            ];

            $dataBarTerbaik = $assetAllocations->where('pnl', '>', '0')->sortByDesc('pnl')->take(3);
            $labelsBarTerbaik = [];
            $seriesBarTerbaik = [];

            foreach ($dataBarTerbaik as $row) {
                array_push($labelsBarTerbaik, $row['asset_name']);
                array_push($seriesBarTerbaik, (int)$row['pnl']);
            }

            $dataBarTerburuk = $assetAllocations->where('pnl', '<', '0')->sortBy('pnl')->take(3);
            $labelsBarTerburuk = [];
            $seriesBarTerburuk = [];

            foreach ($dataBarTerburuk as $row) {
                array_push($labelsBarTerburuk, $row['asset_name']);
                array_push($seriesBarTerburuk, (int)$row['pnl']);
            }

            $terbanyak = $assetAllocations->sortByDesc('total');

            return view('users.wallets.detail_demography', compact('wallet', 'data', 'optionsPie', 'seriesBarTerbaik', 'labelsBarTerbaik', 'seriesBarTerburuk', 'labelsBarTerburuk', 'terbanyak'));
        } else {
            abort(403);
        }
    }

    public function detail_demography_print(Wallet $wallet){
        if (Auth::user()->hasPermissionTo('portfolio-daftar')) {

            $data = User::findOrFail(Auth::id());

            $assetWallets = $wallet->assets;
            $assetAllocations = collect();

            foreach ($assetWallets as $asset) {
                // Hitung jumlah alokasi aset
                $total = $asset->pivot->total;
                $pnl = $asset->pivot->pnl;
                $amount = $asset->pivot->amount;
                $average_price = 0;
                if($amount > 0 and $total > 0){
                    $average_price = $total / $amount;
                }

                // Tambahkan data aset dan jumlah ke array alokasi aset
                $assetAllocations->push([
                    'asset_name' => $asset->name,
                    'amount' => $amount,
                    'pnl' => $pnl,
                    'total' => $total,
                    'average_price' => $average_price,
                ]);
            }

            $labelsPie = [];
            $seriesPie = [];
            foreach ($assetAllocations as $row) {
                array_push($labelsPie, $row['asset_name']);
                array_push($seriesPie, (int)$row['total']);
            }

            $optionsPie = [
                'series' => $seriesPie,
                'chart' => [
                    'type' => 'pie',
                    'width' => '100%',
                    'redrawOnWindowResize' => true,
                    'redrawOnParentResize' => true,
                ],
                'labels' => $labelsPie,
                'legend' => [
                    'position' => 'right',
                ]
            ];

            $dataBarTerbaik = $assetAllocations->where('pnl', '>', '0')->sortByDesc('pnl')->take(3);
            $labelsBarTerbaik = [];
            $seriesBarTerbaik = [];

            foreach ($dataBarTerbaik as $row) {
                array_push($labelsBarTerbaik, $row['asset_name']);
                array_push($seriesBarTerbaik, (int)$row['pnl']);
            }

            $dataBarTerburuk = $assetAllocations->where('pnl', '<', '0')->sortBy('pnl')->take(3);
            $labelsBarTerburuk = [];
            $seriesBarTerburuk = [];

            foreach ($dataBarTerburuk as $row) {
                array_push($labelsBarTerburuk, $row['asset_name']);
                array_push($seriesBarTerburuk, (int)$row['pnl']);
            }

            $terbanyak = $assetAllocations->sortByDesc('total');

            return view('users.wallets.detail_demography_print', compact('wallet', 'data', 'optionsPie', 'seriesBarTerbaik', 'labelsBarTerbaik', 'seriesBarTerburuk', 'labelsBarTerburuk', 'terbanyak'));
        } else {
            abort(403);
        }
    }
}
