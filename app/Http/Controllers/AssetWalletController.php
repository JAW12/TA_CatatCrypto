<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AssetWalletController extends Controller
{

    public function add(Wallet $wallet, Request $request){
        $asset = Asset::where('coin_gecko_id', $request->id)->first();
        if($wallet->assets()->find($asset->id) == null){
            $add = $wallet->assets()->attach($asset->id);
        }
        else{
            return redirect()->route('user.wallet.detail', $wallet)->withError('Aset sudah dimiliki dompet tersebut');
        }
        if($wallet->assets()->find($asset->id)){
            return redirect()->route('user.wallet.detail', $wallet)->withSuccess('Aset berhasil ditambahkan');
        }
        else{
            return redirect()->route('user.wallet.detail', $wallet)->withError('Aset gagal ditambahkan');
        }
    }

    public function show(Wallet $wallet, Asset $asset)
    {
        $asset_wallet = AssetWallet::where('asset_id', $asset->id)->where('wallet_id', $wallet->id)->first();
        return view('users.wallets.assets.show', compact('wallet', 'asset', 'asset_wallet'));
    }


    public function info(Wallet $wallet, Asset $asset){
        return view('users.wallets.assets.info', compact('wallet', 'asset'));
    }
}
