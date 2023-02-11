<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Membership;
use App\Models\Strategy;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::all();
        return view('admin.membership.list', compact('transactions'));
    }

    public function transactions_pending()
    {
        $transactions = Transaction::where('status', 'pending')->get();
        $pending = 1;
        return view('admin.membership.list', compact('transactions', 'pending'));
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

            if ($transaction->membership_id > 0) {
                $membership = Membership::findOrFail($transaction->membership_id);
                $today = date("Y-m-d");
                $date = date('Y-m-d', strtotime($today . ' + ' . $membership->duration_months . ' months'));

                $user = User::find($transaction->user->id);
                $user->memberships()->attach($membership->id, ['membership_expiration' => $date, 'status' => 1]);
                $user->max_wallets = $membership->max_wallets;
                $user->max_journals = $membership->max_journals;
                $user->trades_quantity_per_month = $membership->trades_quantity_per_month;

                $user->remaining_trades = $user->trades_quantity_per_month;

                if ($membership->trades_quantity_per_month == -1) {
                    $user->remaining_trades = -1;
                }
                $user->membership_since = $today;
                $user->membership_till = $date;
                $user->membership_update = $today;
                $user->spent = $user->spent + $transaction->gross_amount;
                $user->save();

                if ($membership->enable_binance == 1) {
                    $user->givePermissionTo('portfolio-tambah-binance');
                } else {
                    $user->revokePermissionTo('portfolio-tambah-binance');
                }

                if ($membership->enable_notification == 1) {
                    $user->givePermissionTo('assets-transactions-notifikasi');
                } else {
                    $user->revokePermissionTo('assets-transactions-notifikasi');
                }
            } else {
                $user = User::find($transaction->user->id);
                $user->remaining_trades = $user->remaining_trades + 100;
                $user->spent = $user->spent + $transaction->gross_amount;
                $user->save();
            }
            $transaction->save();

            foreach ($user->transactions as $trans) {
                if ($trans->status == "pending") {
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
        return $transaction->save() ? redirect()->route('admin.transactions')->withSuccess('Berhasil menolak pembayaran') : redirect()->route('admin.transactions')->withError('Gagal menolak pembayaran');
    }

    public function libraries()
    {
        return view('admin.strategies.index');
    }

    public function libraries_search(Request $request)
    {

        $results = Strategy::select('id', 'user_id', 'category_id', 'name', 'description', 'url_picture')->with('user')->with('category');

        $search = $request->input('search');
        if ($search) {
            $results->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }
        $results = $results->orderBy('name', 'ASC')->get();

        return response()->json($results);
    }

    public function library_delete($id)
    {
        DB::beginTransaction();
        try {

            $strategy = Strategy::findOrFail($id);

            if ($strategy->url_picture != "") {
                $image_path = public_path("\\") . $strategy->url_picture;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    $strategy->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.strategies')->withSuccess('Berhasil menghapus pustaka tersebut');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('admin.strategies')->withError('Gagal menghapus pustaka tersebut');
        }
    }

    public function library_show($id)
    {
        $strategy = Strategy::findOrFail($id);

        return view('admin.strategies.show', compact('strategy'));
    }

    public function library_edit($id)
    {
        $strategy = Strategy::findOrFail($id);

        $categories = Category::all();
        return view('admin.strategies.edit', compact('strategy', 'categories'));
    }

    public function library_update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'url_picture' => 'file|image'
        ]);

        DB::beginTransaction();
        try {
            $strategy = Strategy::findOrFail($id);

            $strategy->name = $request->get('name');
            $strategy->category_id = $request->get('category_id');
            $strategy->description = $request->get('description');

            $url_picture = $request->file('url_picture');
            if ($url_picture) {
                if ($strategy->url_picture != "") {
                    $image_path = public_path("\\") . $strategy->url_picture;
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }

                $fileName = Auth::id() . '-' . $strategy->name . '-' . time() . '.' . $url_picture->extension();
                $destinationPath = 'images';
                $url_picture->storeAs('strategies', $fileName, 'public');
                $strategy->url_picture = "storage/strategies/" . $fileName;
            }

            $strategy->save();
            DB::commit();

            return redirect()->route('admin.library.detail', ['id' => $strategy->id])->withSuccess('Berhasil mengubah pustaka');
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            return redirect()->route('admin.library.detail', ['id' => $strategy->id])->withError('Gagal mengubah pustaka');
        }
    }

    public function library_addPage()
    {
        $categories = Category::all();
        return view('admin.strategies.add', compact('categories'));
    }

    public function library_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'url_picture' => 'required|file|image'
        ]);

        DB::beginTransaction();
        try {
            //code...
            $strategy = new Strategy();
            $strategy->name = $request->get('name');
            $strategy->user_id = Auth::id();
            $strategy->category_id = $request->get('category_id');
            $strategy->description = $request->get('description');

            $url_picture = $request->file('url_picture');
            if ($url_picture) {
                $fileName = Auth::id() . '-' . $strategy->name . '-' . time() . '.' . $url_picture->extension();
                $destinationPath = 'images';
                $url_picture->storeAs('strategies', $fileName, 'public');
                $strategy->url_picture = "storage/strategies/" . $fileName;
            }

            $strategy->save();
            DB::commit();

            return redirect()->route('admin.library')->withSuccess('Berhasil menambahkan pustaka');
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            return redirect()->route('admin.library')->withError('Gagal menambahkan pustaka');
        }
    }

    public function users()
    {
        $users = User::where('user_type', '<>', 'admin')->withTrashed()->get();
        return view('admin.users.list', compact('users'));
    }

    public function user_ban($id)
    {
        $user = User::findOrFail($id);
        return $user->delete() ? redirect()->route('admin.users')->withSuccess('Berhasil ban pengguna ini') : redirect()->route('admin.users')->withError('Gagal ban pengguna ini');
    }

    public function user_restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return $user->restore() ? redirect()->route('admin.users')->withSuccess('Berhasil kembalikan pengguna ini') : redirect()->route('admin.users')->withError('Gagal kembalikan pengguna ini');
    }

    public function user_show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
}
