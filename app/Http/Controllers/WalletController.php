<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
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
        if(Auth::user()->max_wallets == 0 or Auth::user()->wallets->count() < Auth::user()->max_wallets){
            $wallet = Auth::user()->wallets()->create($request->all());
            if($wallet){
                return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
            }
            else{
                return redirect()->back()->withError('Dompet gagal ditambahkan');
            }
        }
        else if(Auth::user()->max_wallets == -1){
            $wallet = Auth::user()->wallets()->create($request->all());
            if($wallet){
                return redirect()->back()->withSuccess('Dompet berhasil ditambahkan');
            }
            else{
                return redirect()->back()->withError('Dompet gagal ditambahkan');
            }
        }
        else{
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
        $client = new CoinGeckoClient();
        // dd($wallet->assets);
        foreach($wallet->assets as $asset){
            $data = $client->coins()->getCoin($asset->coin_gecko_id);
            $update = $asset->update([
                'current_price' => $data['market_data']['current_price']['usd'],
            ]);
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
        if($success){
            return redirect()->back()->withSuccess('Dompet berhasil diubah');
        }
        else{
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
        if($delete){
            return redirect()->back()->withSuccess('Dompet berhasil dinonaktifkan');
        }
        else{
            return redirect()->back()->withError('Dompet gagal dinonaktifkan');
        }
    }

    public function restore(Wallet $wallet)
    {
        $restore = $wallet->restore();
        if($restore){
            return redirect()->back()->withSuccess('Dompet berhasil diaktifkan');
        }
        else{
            return redirect()->back()->withError('Dompet gagal diaktifkan');
        }
    }
}
