<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::all();
        return view('admin.membership.list', compact('transactions'));
    }

    public function transaction_detail($id_order)
    {
        $transaction = Transaction::where('id_order', $id_order)->first();
        return view('admin.membership.show', compact('transaction'));
    }

    public function transaction_accept($id_order)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::where('id_order', $id_order)->first();
            $transaction->status = "settlement";

            $membership = Membership::findOrFail($transaction->membership_id);
            $today = date("Y-m-d");
            $date = date('Y-m-d', strtotime($today. ' + ' . $membership->duration_months . ' months'));

            $user = User::find($transaction->user->id);
            $user->memberships()->attach($membership->id, ['membership_expiration' => $date, 'status' => 1]);
            $user->max_wallets = $membership->max_wallets;
            $user->max_journals = $membership->max_journals;
            $user->trades_quantity_per_month = $membership->trades_quantity_per_month;

            if($user->remaining_trades < $user->trades_quantity_per_month){
                $user->remaining_trades = $user->remaining_trades + $user->trades_quantity_per_month;
            }
            else if($membership->trades_quantity_per_month == -1){
                $user->remaining_trades = -1;
            }
            $user->membership_since = $today;
            $user->membership_till = $date;
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

            $transaction->save();

            foreach($user->transactions as $trans){
                if($trans->status == "pending"){
                    $trans->status = "cancel";
                    $trans->save();
                }
            }
            DB::commit();
            return redirect()->route('admin.transactions')->withSuccess('Berhasil menerima pembayaran');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return redirect()->route('admin.transactions')->withError('Gagal menerima pembayaran');
        }
    }

    public function transaction_deny($id_order)
    {
        $transaction = Transaction::where('id_order', $id_order)->first();
        $transaction->status = "deny";
        return $transaction->save() ? redirect()->route('admin.transactions')->withSuccess('Berhasil menerima pembayaran') : redirect()->route('admin.transactions')->withError('Gagal menerima pembayaran');
    }
}
