<?php

namespace App\Http\Controllers;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\AssetTransaction;
use App\Models\AssetWallet;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetTransactionController extends Controller
{
    public function add_update(Wallet $wallet, Asset $asset, Request $request)
    {
        // dd($request);
        $request['price'] = str_replace(',', '', $request['price']);
        $request['amount'] = str_replace(',', '', $request['amount']);
        $request['fee'] = str_replace(',', '', $request['fee']);
        $request['total'] = str_replace(',', '', $request['total']);

        if($request->type == 0 or $request->type == 1){
            $request->validate([
                'price' => 'required|min:0|not_in:0',
                'amount' => 'required|min:0|not_in:0',
                'fee' => 'required|min:0',
                'total' => 'required|min:0|not_in:0',
            ]);
        }
        else{
            $request->validate([
                'amount' => 'required|min:0|not_in:0',
                'fee' => 'required|min:0',
            ]);
            $request['price'] = 0;
            $request['total'] = 0;
        }

        if($request->get('transaction_id') == null){
            $asset_wallet = AssetWallet::where('wallet_id', $wallet->id)->where('asset_id', $asset->id)->first();
            if ($wallet->binance_api_key == null) {
                $request['asset_wallet_id'] = $asset_wallet->id;
                $request['status'] = 1;
                $request['integrated'] = 0;
                if ($request['time'] == null) {
                    $request['time'] = now();
                }
                $asset_transaction = AssetTransaction::create($request->except(['transaction_id']));
                if ($asset_transaction) {
                    return redirect()->back()->withSuccess('Transaksi berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Transaksi gagal ditambahkan');
                }
            }
            else{
                $request['asset_wallet_id'] = $asset_wallet->id;
                $request['status'] = 0;
                $request['integrated'] = 1;
                if ($request['time'] == null) {
                    $request['time'] = now();
                }
                DB::beginTransaction();
                try{
                    $asset_transaction = AssetTransaction::create($request->except(['transaction_id']));
                    Binance::auth($wallet->binance_api_key, $wallet->binance_secret_key);
                    $url = "/api/v3/order";
                    $params = [
                        'symbol' => $asset->binance_symbol,
                        'type' => 'LIMIT',
                        'timeInForce' => 'GTC',
                        'quantity' => $asset_transaction->amount,
                        'price' => (float)$asset_transaction->price,
                    ];
                    if($asset_transaction->type == 0){
                        $params['side'] = 'BUY';
                    }
                    else if($asset_transaction->type == 1){
                        $params['side'] = 'SELL';
                    }
                    $type = "POST";
                    $response = Binance::call(true, "SPOT", $url, $params, $type);
                    // print_r($response);

                    // $orderId = $response['orderId'];
                    // print_r($orderId);

                    $url = "/api/v3/order";
                    $params = [
                        'symbol' => $asset->binance_symbol,
                        'orderId' => $response['orderId'],
                    ];
                    $type = 'GET';
                    $response = Binance::call(true, "SPOT", $url, $params, $type);
                    if($response['status'] == 'FILLED'){
                        $url = "/api/v3/myTrades";
                        $params = [
                            'symbol' => $asset->binance_symbol,
                            'orderId' => $response['orderId'],
                        ];
                        $type = 'GET';
                        $response = Binance::call(true, "SPOT", $url, $params, $type);

                        if(count($response) > 0){
                            $asset_transaction->update([
                                'trade_id' => $response['id'],
                                'time' => $response['time'],
                                'status' => 1,
                                'fee' => $response['commission'],
                            ]);
                        }
                    }

                    DB::commit();
                } catch(Exception $ex){
                    DB::rollBack();
                }
            }
        }
        else{
            $asset_transaction = AssetTransaction::findOrFail($request->get('transaction_id'));
            $update = $asset_transaction->update($request->except(['transaction_id']));
            if ($update) {
                return redirect()->back()->withSuccess('Transaksi berhasil diubah');
            } else {
                return redirect()->back()->withError('Transaksi gagal diubah');
            }
        }



        // dd($asset_wallet);
    }

    public function destroy(Wallet $wallet, Asset $asset, AssetTransaction $asset_transaction){
        $delete = $asset_transaction->delete();
        if ($asset_transaction) {
            return redirect()->back()->withSuccess('Transaksi berhasil dihapus');
        } else {
            return redirect()->back()->withError('Transaksi gagal dihapus');
        }
    }
}
