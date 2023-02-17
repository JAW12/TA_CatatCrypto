<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_type' => 'required'
        ]);

        $order_id = "order-" . date('y') . date('m') . date('d') . Auth::id() . '-' . $request->membership_id . sprintf('%03d', Transaction::where('membership_id', $request->membership_id)->whereDate('created_at', date('Y-m-d'))->count());

        if ($request->payment_type != "automatic") {
            if($request->transfer_time == null){
                $request['transfer_time'] = now();
            }
            $transaction = Auth::user()->transactions()->create([
                'membership_id' => $request->membership_id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'status' => "pending",
                'gross_amount' => $request->price,
                'bank_name' => $request->bank_type,
                'payment_name' => $request->bank_name,
                'payment_type' => $request->payment_type,
                'payment_time' => $request->transfer_time,
                'id_order' => $order_id,
            ]);
            return $transaction ? redirect()->route('user.transaction.detail', ['id' => Auth::id(), 'id_order' => $transaction->id_order])->withSuccess('Transaksi berhasil') : redirect()->route('index')->withError('Transaksi gagal');
        } else {
            $transaction = Auth::user()->transactions()->create([
                'membership_id' => $request->membership_id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'status' => "pending",
                'gross_amount' => $request->price,
                'payment_type' => $request->payment_type,
                'id_order' => $order_id,
            ]);
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $order_id,
                    'gross_amount' => $request->price,
                ),
                'customer_details' => array(
                    'first_name' => $request->name,
                    'last_name' => '',
                    'email' => $request->email,
                    'phone' => $request->phone_number,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return view('users.membership.checkout', compact('snapToken', 'transaction'));
        }
    }

    public function payment_post(Request $request)
    {
        $json = json_decode($request->get('json'));
        $transaction = json_decode($request->get('transaction'));

        $transaction = Transaction::findOrFail($transaction->id);
        $transaction->status = $json->transaction_status;
        $transaction->id_transaction = $json->transaction_id;
        $transaction->id_order   = $json->order_id;
        $transaction->payment_type = $json->payment_type;
        $transaction->payment_time = $json->transaction_time;

        $transaction->save();

        if($transaction->status == "settlement" || $transaction->status == "capture"){
            if($transaction->membership_id > 0){
                $membership = Membership::findOrFail($transaction->membership_id);
                $today = date("Y-m-d");
                $date = date('Y-m-d', strtotime($today. ' + ' . $membership->duration_months . ' months'));

                Auth::user()->memberships()->attach($membership->id, ['membership_expiration' => $date, 'status' => 1]);

                $user = User::find(Auth::id());
                $user->user_type = $membership->name;
                $user->max_wallets = $membership->max_wallets;
                $user->max_journals = $membership->max_journals;
                $user->trades_quantity_per_month = $membership->trades_quantity_per_month;
                $user->remaining_trades = $user->trades_quantity_per_month;
                if($membership->trades_quantity_per_month == -1){
                    $user->remaining_trades = -1;
                }
                $user->membership_since = $today;
                $user->membership_till = $date;
                $user->membership_update = $today;
                $user->spent = $user->spent + $transaction->gross_amount;
                $user->save();

                if($membership->enable_binance == 1){
                    $user->givePermissionTo('portfolio-tambah-binance');
                }
                else{
                    $user->revokePermissionTo('portfolio-tambah-binance');
                }

                if($membership->enable_notification == 1){
                    $user->givePermissionTo('assets-transactions-notifikasi');
                }
                else{
                    $user->revokePermissionTo('assets-transactions-notifikasi');
                }
            }
            else{
                $user = User::find(Auth::id());
                $user->remaining_trades = $user->remaining_trades + 100;
                $user->spent = $user->spent + $transaction->gross_amount;
                $user->save();
            }

            foreach($user->transactions as $transaction){
                if($transaction->status == "pending"){
                    $transaction->status = "cancel";
                    $transaction->save();
                }
            }
            return redirect()->route('index')->withSuccess('Transaksi berhasil');
        }
        else{
            return redirect()->route('index')->withError('Transaksi gagal');
        }
    }

    public function list($id)
    {
        if($id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            return redirect()->route('index');
        }

        return view('users.profile.transaction');
    }

    public function detail($id, $id_order)
    {
        if($id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            return redirect()->route('index');
        }

        $transaction = Transaction::where('id_order', $id_order)->first();
        return view('users.profile.transaction_detail', compact('transaction'));
    }

    public function cancel($id, $id_order)
    {
        $transaction = Transaction::where('id_order', $id_order)->first();
        $transaction->status = "cancel";
        return $transaction->save() ? redirect()->route('user.transaction.list', ['id' => $id])->withSuccess('Berhasil membatalkan pembayaran') : redirect()->route('user.transaction.list', ['id' => $id])->withError('Gagal membatalkan pembayaran');
    }

    // public function payment_handler(Request $request)
    // {
    //     $json = json_decode($request->getContent());

    //     $signature_key = hash('sha512', $json->order_id . $json->status_code . $json->gross_amount . config('midtrans.server_key'));

    //     if($signature_key != $json->signature_key){
    //         abort(404);
    //     }

    //     $transaction = Transaction::where('order_id', $json->order_id)->first();
    //     return $transaction->update([
    //         'status' => $json->transaction_status,
    //     ]);
    // }
}
