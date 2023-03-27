<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Journal;
use App\Models\Membership;
use App\Models\Strategy;
use App\Models\StrategyFavorite;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use DateTime;
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

            // if ($transaction->membership_id > 0) {
            //     $membership = Membership::findOrFail($transaction->membership_id);
            //     $today = date("Y-m-d");
            //     $date = date('Y-m-d', strtotime($today . ' + ' . $membership->duration_months . ' months'));

            //     $user = User::find($transaction->user->id);
            //     $user->memberships()->attach($membership->id, ['membership_expiration' => $date, 'status' => 1]);
            //     $user->max_wallets = $membership->max_wallets;
            //     $user->max_journals = $membership->max_journals;
            //     $user->trades_quantity_per_month = $membership->trades_quantity_per_month;

            //     $user->remaining_trades = $user->trades_quantity_per_month;

            //     if ($membership->trades_quantity_per_month == -1) {
            //         $user->remaining_trades = -1;
            //     }
            //     $user->membership_since = $today;
            //     $user->membership_till = $date;
            //     $user->membership_update = $today;
            //     $user->spent = $user->spent + $transaction->gross_amount;
            //     $user->save();

            //     if ($membership->enable_binance == 1) {
            //         $user->givePermissionTo('portfolio-tambah-binance');
            //     } else {
            //         $user->revokePermissionTo('portfolio-tambah-binance');
            //     }

            //     if ($membership->enable_notification == 1) {
            //         $user->givePermissionTo('assets-transactions-notifikasi');
            //     } else {
            //         $user->revokePermissionTo('assets-transactions-notifikasi');
            //     }
            // } else {
            //     $user = User::find($transaction->user->id);
            //     $user->remaining_trades = $user->remaining_trades + 100;
            //     $user->spent = $user->spent + $transaction->gross_amount;
            //     $user->save();
            // }
            if ($transaction->membership_id > 0) {
                $activeMembership = $transaction->user->membership->first();
                if ($activeMembership == null) {
                    $membership = Membership::findOrFail($transaction->membership_id);
                    $today = date("Y-m-d");
                    $date = date('Y-m-d', strtotime($today . ' + ' . $membership->duration_months . ' months'));

                    $user = User::find($transaction->user->id);
                    $user->memberships()->attach($membership->id, ['membership_start' => $today, 'membership_expiration' => $date, 'status' => 1]);
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
                    $membership = Membership::findOrFail($transaction->membership_id);
                    $start = $activeMembership->pivot->membership_expiration;
                    $date = date('Y-m-d', strtotime($start . ' + ' . $membership->duration_months . ' months'));

                    $user = User::find($transaction->user->id);
                    $user->memberships()->attach($membership->id, ['membership_expiration' => $date, 'status' => 0, 'membership_start' => $start]);
                    $user->spent = $user->spent + $transaction->gross_amount;
                    $user->save();
                }
            } else {
                $user = User::find($transaction->user->id);
                if($user->remaining_trades >= 0){
                    $user->remaining_trades = $user->remaining_trades + 100;
                }
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
            return redirect()->route('admin.library')->withSuccess('Berhasil menghapus pustaka tersebut');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('admin.library')->withError('Gagal menghapus pustaka tersebut');
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

    public function transactions_report()
    {
        $transactions = Transaction::where('status', 'settlement')->orWhere('status', 'capture')->get();
        return view('admin.membership.report', compact('transactions'));
    }

    public function transactions_report_load(Request $request)
    {
        $minDateTimeStamp = $request->minDate;
        $maxDateTimeStamp = $request->maxDate;
        // dd(date($maxDate/1000));
        if ($minDateTimeStamp and $maxDateTimeStamp) {
            $minDate = DateTime::createFromFormat("D M d Y H:i:s e+", $minDateTimeStamp);
            $maxDate = DateTime::createFromFormat("D M d Y H:i:s e+", $maxDateTimeStamp);
            $maxDate->setTime(23, 59, 59);
            $transactions = Transaction::whereBetween('payment_time', [$minDate, $maxDate])->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else if ($minDateTimeStamp) {
            $minDate = DateTime::createFromFormat("D M d Y H:i:s e+", $minDateTimeStamp);
            $transactions = Transaction::where('payment_time', '>=', $minDate->format('Y-m-d H:i:s'))->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else if ($maxDateTimeStamp) {
            $maxDate = DateTime::createFromFormat("D M d Y H:i:s e+", $maxDateTimeStamp);
            $maxDate->setTime(23, 59, 59);
            $transactions = Transaction::where('payment_time', '<=', $maxDate->format('Y-m-d H:i:s'))->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else {
            $transactions = Transaction::where('status', 'settlement')->orWhere('status', 'capture')->get();
        }

        $basic = [
            'income' => $transactions->where('membership_id', 1)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 1)->count(),
        ];
        $home = [
            'income' => $transactions->where('membership_id', 2)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 2)->count(),
        ];
        $professional = [
            'income' => $transactions->where('membership_id', 3)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 3)->count(),
        ];
        $business = [
            'income' => $transactions->where('membership_id', 4)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 4)->count(),
        ];
        $additional = [
            'income' => $transactions->where('membership_id', 0)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 0)->count(),
        ];

        $manual_bank = [
            'income' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'Bank');
            })->sum('gross_amount'),
            'count' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'Bank');
            })->count(),
        ];
        $manual_ewallet = [
            'income' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'E-Wallet');
            })->sum('gross_amount'),
            'count' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'E-Wallet');
            })->count(),
        ];
        $credit_card = [
            'income' => $transactions->where('payment_type', 'credit_card')->sum('gross_amount'),
            'count' => $transactions->where('payment_type', 'credit_card')->count(),
        ];
        $total = [
            'income' => $transactions->sum('gross_amount'),
            'count' => $transactions->count(),
        ];

        $data = [
            'total' => $total,
            'basic' => $basic,
            'home' => $home,
            'professional' => $professional,
            'business' => $business,
            'additional' => $additional,
            'manual_bank' => $manual_bank,
            'manual_ewallet' => $manual_ewallet,
            'credit_card' => $credit_card,
        ];

        return response()->json($data);
    }

    public function transactions_report_print(Request $request)
    {
        // dd(date($maxDate/1000));
        if ($request->start and $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end)->endOfDay();
            $transactions = Transaction::whereBetween('payment_time', [$start, $end])->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else if ($request->start) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end)->endOfDay();
            $transactions = Transaction::where('payment_time', '>=', $start)->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else if ($request->end) {
            $end = Carbon::parse($request->end)->endOfDay();
            $transactions = Transaction::where('payment_time', '<=', $end)->where(function ($query) {
                $query->where('status', '=', 'capture')
                ->orWhere('status', '=', 'settlement');
            })->get();
        } else {
            $transactions = Transaction::where('status', 'settlement')->orWhere('status', 'capture')->get();
        }

        $basic = [
            'income' => $transactions->where('membership_id', 1)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 1)->count(),
        ];
        $home = [
            'income' => $transactions->where('membership_id', 2)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 2)->count(),
        ];
        $professional = [
            'income' => $transactions->where('membership_id', 3)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 3)->count(),
        ];
        $business = [
            'income' => $transactions->where('membership_id', 4)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 4)->count(),
        ];
        $additional = [
            'income' => $transactions->where('membership_id', 0)->sum('gross_amount'),
            'count' => $transactions->where('membership_id', 0)->count(),
        ];

        $manual_bank = [
            'income' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'Bank');
            })->sum('gross_amount'),
            'count' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'Bank');
            })->count(),
        ];
        $manual_ewallet = [
            'income' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'E-Wallet');
            })->sum('gross_amount'),
            'count' => $transactions->filter(function ($item) {
                return str_contains($item->payment_type, 'E-Wallet');
            })->count(),
        ];
        $credit_card = [
            'income' => $transactions->where('payment_type', 'credit_card')->sum('gross_amount'),
            'count' => $transactions->where('payment_type', 'credit_card')->count(),
        ];
        $total = [
            'income' => $transactions->sum('gross_amount'),
            'count' => $transactions->count(),
        ];

        $data = [
            'total' => $total,
            'basic' => $basic,
            'home' => $home,
            'professional' => $professional,
            'business' => $business,
            'additional' => $additional,
            'manual_bank' => $manual_bank,
            'manual_ewallet' => $manual_ewallet,
            'credit_card' => $credit_card,
            'start' => $request->start,
            'end' => $request->end,
        ];

        if ($request->start) {
            $data['start'] = $request->start;
        } else {
            $data['start'] = $transactions->first()->payment_time;
        }
        if ($request->end) {
            $data['end'] = $request->end;
        } else {
            $data['end'] = $transactions->last()->payment_time;
        }


        return view('admin.membership.report_print', compact('transactions', 'data'));
    }

    public function users_demography()
    {
        $users = User::where('user_type', '!=', 'admin')->get();

        $total_users = $users->count();
        $genderDistribution = [
            'Pria' => 0,
            'Wanita' => 0,
            'Tidak Diketahui' => 0,
        ];
        $averageAge = 0;
        $ageRangeCounts = [
            '<20' => 0,
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '>50' => 0,
        ];
        // $membership_distribution = collect(
        //     [
        //         'name' => 'Basic',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Home',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Professional',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Business',
        //         'total' => 0,
        //     ],
        // );
        $membership_distribution = [
            'Basic' => 0,
            'Home' => 0,
            'Professional' => 0,
            'Business' => 0,
        ];
        $walletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $binanceWalletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $manualWalletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $journalDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $tradeDistribution = [
            '0' => 0,
            '1-100' => 0,
            '101-500' => 0,
            '501-1500' => 0,
            '>1500' => 0,
        ];
        $averageWallets = 0;
        $averageManualWallets = 0;
        $averageBinanceWallets = 0;
        $averageJournals = 0;
        $averageTrades = 0;

        if ($total_users > 0) {
            $maleCount = $users->where('gender', 'm')->count();
            $femaleCount = $users->where('gender', 'f')->count();
            $unknownCount = $users->whereNull('gender')->count();
            $totalCount = $users->count();

            $genderDistribution = [
                'Pria' => $maleCount,
                'Wanita' => $femaleCount,
                'Tidak Diketahui' => $unknownCount,
            ];


            $usersAge = $users->map(function ($user) {
                return Carbon::parse($user->birthdate)->age;
            });

            $averageAge = round($usersAge->avg());

            $ageRanges = ['<20', '20-29', '30-39', '40-49', '>50'];
            $ageRangeCounts = $usersAge->groupBy(function ($age) {
                if ($age < 20) {
                    return '<20';
                } elseif ($age < 30) {
                    return '20-29';
                } elseif ($age < 40) {
                    return '30-39';
                } elseif ($age < 50) {
                    return '40-49';
                } else {
                    return '>50';
                }
            })->map(function ($ageRange) {
                return $ageRange->count();
            });

            $ageRangeCounts = array_merge(array_fill_keys($ageRanges, 0), $ageRangeCounts->toArray());

            // $membership_distribution = DB::table('memberships')
            //     ->leftJoin('membership_user', function ($join) {
            //         $join->on('memberships.id', '=', 'membership_user.membership_id')
            //             ->where('membership_user.status', '=', 1);
            //     })
            //     ->select('memberships.name', DB::raw('coalesce(count(membership_user.membership_id), 0) as total'))
            //     ->groupBy('memberships.name')
            //     ->get();

            $memberships = DB::table('memberships')
                ->leftJoin('membership_user', function ($join) {
                    $join->on('memberships.id', '=', 'membership_user.membership_id')
                        ->where('membership_user.status', '=', 1);
                })
                ->select('memberships.name', DB::raw('coalesce(count(membership_user.membership_id), 0) as total'))
                ->groupBy('memberships.name')
                ->get();

            foreach($memberships as $membership){
                foreach($membership_distribution as $key => &$value){
                    if(strtolower($key) == $membership->name){
                        $value = intval($membership->total);
                    }
                }
            }

            $total_verified_users = $users->whereNotNull('email_verified_at')->count();

            $totalWallets = 0;
            $totalBinanceWallets = 0;
            $totalManualWallets = 0;
            $totalJournals = 0;
            $totalTrades = 0;

            foreach ($users as $user) {
                $walletCount = $user->wallets()->count();
                $manualWalletCount = $user->wallets()->whereNull('binance_api_key')->count();
                $binanceWalletCount = $user->wallets()->whereNotNull('binance_api_key')->count();
                $journalCount = $user->journals()->count();
                foreach ($user->journals as $journal) {
                    $tradeCount = $journal->trades()->count();
                    $totalTrades += $tradeCount;
                }

                $totalWallets += $walletCount;
                $totalManualWallets += $manualWalletCount;
                $totalBinanceWallets += $binanceWalletCount;
                $totalJournals += $journalCount;
            }

            $averageWallets = floor($totalWallets / count($users));
            $averageManualWallets = floor($totalManualWallets / count($users));
            $averageBinanceWallets = floor($totalBinanceWallets / count($users));
            $averageJournals = floor($totalJournals / count($users));
            $averageTrades = floor($totalTrades / count($users));

            $usersWithoutWallet = DB::table('users')
                ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
                ->where('users.user_type', '!=', 'admin')
                ->whereNull('wallets.id')
                ->count();

            $usersWith1To3Wallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6Wallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();

            // $usersWithWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 0 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWallet1To3 = $usersWithWallet1To3Result ? intval($usersWithWallet1To3Result->total_users) : 0;

            // $usersWithWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWallet4To6 = $usersWithWallet4To6Result ? intval($usersWithWallet4To6Result->total_users) : 0;

            // $usersWithWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWalletMoreThan6 = $usersWithWalletMoreThan6Result ? intval($usersWithWalletMoreThan6Result->total_users) : 0;

            $walletDistribution = [
                '0' => $usersWithoutWallet,
                '1-3' => $usersWith1To3Wallets,
                '4-6' => $usersWith4To6Wallets,
                '>6' => $usersWithWalletMoreThan6
            ];

            $usersWithoutBinanceWallet = DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('wallets')
                        ->whereRaw('wallets.user_id = users.id')
                        ->whereNotNull('wallets.binance_api_key');
                })
                ->count();

            $usersWith1To3BinanceWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6BinanceWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithBinanceWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();


            // $usersWithBinanceWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 1 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWallet1To3 = $usersWithBinanceWallet1To3Result ? $usersWithBinanceWallet1To3Result->total_users : 0;

            // $usersWithBinanceWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWallet4To6 = $usersWithBinanceWallet4To6Result ? $usersWithBinanceWallet4To6Result->total_users : 0;

            // $usersWithBinanceWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWalletMoreThan6 = $usersWithBinanceWalletMoreThan6Result ? intval($usersWithBinanceWalletMoreThan6Result->total_users) : 0;

            $binanceWalletDistribution = [
                '0' => $usersWithoutBinanceWallet,
                '1-3' => $usersWith1To3BinanceWallets,
                '4-6' => $usersWith4To6BinanceWallets,
                '>6' => $usersWithBinanceWalletMoreThan6
            ];

            $usersWithoutManualWallet = DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('wallets')
                        ->whereRaw('wallets.user_id = users.id')
                        ->whereNull('wallets.binance_api_key');
                })->count();

            $usersWith1To3ManualWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6ManualWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithManualWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();

            // $usersWithManualWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 1 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWallet1To3 = $usersWithManualWallet1To3Result ? intval($usersWithManualWallet1To3Result->total_users) : 0;

            // $usersWithManualWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWallet4To6 = $usersWithManualWallet4To6Result ? intval($usersWithManualWallet4To6Result->total_users) : 0;

            // $usersWithManualWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWalletMoreThan6 = $usersWithManualWalletMoreThan6Result ? intval($usersWithManualWalletMoreThan6Result->total_users) : 0;

            $manualWalletDistribution = [
                '0' => $usersWithoutManualWallet,
                '1-3' => $usersWith1To3ManualWallets,
                '4-6' => $usersWith4To6ManualWallets,
                '>6' => $usersWithManualWalletMoreThan6
            ];

            $journalDistribution = [
                '0' => 0,
                '1-3' => 0,
                '4-6' => 0,
                '>6' => 0,
            ];

            foreach ($users as $user) {
                $journalCount = $user->journals()->count();
                if ($journalCount == 0) {
                    $journalDistribution['0'] += 1;
                } elseif ($journalCount >= 1 && $journalCount <= 3) {
                    $journalDistribution['1-3'] += 1;
                } elseif ($journalCount >= 4 && $journalCount <= 6) {
                    $journalDistribution['4-6'] += 1;
                } elseif ($journalCount > 6) {
                    $journalDistribution['>6'] += 1;
                }
            }

            $tradeDistribution = [
                '0' => 0,
                '1-100' => 0,
                '101-500' => 0,
                '501-1500' => 0,
                '>1500' => 0,
            ];

            foreach ($users as $user) {
                $journals = $user->journals;

                $trades_count = 0;
                foreach ($journals as $journal) {
                    $trades = DB::table('trades')
                        ->where('journal_id', $journal->id)
                        ->get();

                    $trades_count += count($trades);
                }

                if ($trades_count == 0) {
                    $tradeDistribution['0']++;
                } elseif ($trades_count <= 100) {
                    $tradeDistribution['1-100']++;
                } elseif ($trades_count <= 500) {
                    $tradeDistribution['101-500']++;
                } elseif ($trades_count <= 1500) {
                    $tradeDistribution['501-1500']++;
                } else {
                    $tradeDistribution['>1500']++;
                }
            }
        }

        $data = [
            'total_users' => $total_users,
            'gender_distribution' => $genderDistribution,
            'averageAge' => $averageAge,
            'age_distribution' => $ageRangeCounts,
            'membership_distribution' => $membership_distribution,
            'wallet_distribution' => $walletDistribution,
            'binance_wallet_distribution' => $binanceWalletDistribution,
            'manual_wallet_distribution' => $manualWalletDistribution,
            'journal_distribution' => $journalDistribution,
            'trade_distribution' => $tradeDistribution,
            'averageWallets' => $averageWallets,
            'averageManualWallets' => $averageManualWallets,
            'averageBinanceWallets' => $averageBinanceWallets,
            'averageJournals' => $averageJournals,
            'averageTrades' => $averageTrades,
        ];

        return view('admin.users.demography', compact('data'));
    }

    public function users_demography_print()
    {
        $users = User::where('user_type', '!=', 'admin')->get();

        $total_users = $users->count();
        $genderDistribution = [
            'Pria' => 0,
            'Wanita' => 0,
            'Tidak Diketahui' => 0,
        ];
        $averageAge = 0;
        $ageRangeCounts = [
            '<20' => 0,
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '>50' => 0,
        ];
        // $membership_distribution = collect(
        //     [
        //         'name' => 'Basic',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Home',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Professional',
        //         'total' => 0,
        //     ],
        //     [
        //         'name' => 'Business',
        //         'total' => 0,
        //     ],
        // );
        $membership_distribution = [
            'Basic' => 0,
            'Home' => 0,
            'Professional' => 0,
            'Business' => 0,
        ];
        $walletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $binanceWalletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $manualWalletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $journalDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $tradeDistribution = [
            '0' => 0,
            '1-100' => 0,
            '101-500' => 0,
            '501-1500' => 0,
            '>1500' => 0,
        ];
        $averageWallets = 0;
        $averageManualWallets = 0;
        $averageBinanceWallets = 0;
        $averageJournals = 0;
        $averageTrades = 0;

        if ($total_users > 0) {
            $maleCount = $users->where('gender', 'm')->count();
            $femaleCount = $users->where('gender', 'f')->count();
            $unknownCount = $users->whereNull('gender')->count();
            $totalCount = $users->count();

            $genderDistribution = [
                'Pria' => $maleCount,
                'Wanita' => $femaleCount,
                'Tidak Diketahui' => $unknownCount,
            ];


            $usersAge = $users->map(function ($user) {
                return Carbon::parse($user->birthdate)->age;
            });

            $averageAge = round($usersAge->avg());

            $ageRanges = ['<20', '20-29', '30-39', '40-49', '>50'];
            $ageRangeCounts = $usersAge->groupBy(function ($age) {
                if ($age < 20) {
                    return '<20';
                } elseif ($age < 30) {
                    return '20-29';
                } elseif ($age < 40) {
                    return '30-39';
                } elseif ($age < 50) {
                    return '40-49';
                } else {
                    return '>50';
                }
            })->map(function ($ageRange) {
                return $ageRange->count();
            });

            $ageRangeCounts = array_merge(array_fill_keys($ageRanges, 0), $ageRangeCounts->toArray());

            // $membership_distribution = DB::table('memberships')
            //     ->leftJoin('membership_user', function ($join) {
            //         $join->on('memberships.id', '=', 'membership_user.membership_id')
            //             ->where('membership_user.status', '=', 1);
            //     })
            //     ->select('memberships.name', DB::raw('coalesce(count(membership_user.membership_id), 0) as total'))
            //     ->groupBy('memberships.name')
            //     ->get();

            $memberships = DB::table('memberships')
                ->leftJoin('membership_user', function ($join) {
                    $join->on('memberships.id', '=', 'membership_user.membership_id')
                        ->where('membership_user.status', '=', 1);
                })
                ->select('memberships.name', DB::raw('coalesce(count(membership_user.membership_id), 0) as total'))
                ->groupBy('memberships.name')
                ->get();

            foreach($memberships as $membership){
                foreach($membership_distribution as $key => &$value){
                    if(strtolower($key) == $membership->name){
                        $value = intval($membership->total);
                    }
                }
            }

            $total_verified_users = $users->whereNotNull('email_verified_at')->count();

            $totalWallets = 0;
            $totalBinanceWallets = 0;
            $totalManualWallets = 0;
            $totalJournals = 0;
            $totalTrades = 0;

            foreach ($users as $user) {
                $walletCount = $user->wallets()->count();
                $manualWalletCount = $user->wallets()->whereNull('binance_api_key')->count();
                $binanceWalletCount = $user->wallets()->whereNotNull('binance_api_key')->count();
                $journalCount = $user->journals()->count();
                foreach ($user->journals as $journal) {
                    $tradeCount = $journal->trades()->count();
                    $totalTrades += $tradeCount;
                }

                $totalWallets += $walletCount;
                $totalManualWallets += $manualWalletCount;
                $totalBinanceWallets += $binanceWalletCount;
                $totalJournals += $journalCount;
            }

            $averageWallets = floor($totalWallets / count($users));
            $averageManualWallets = floor($totalManualWallets / count($users));
            $averageBinanceWallets = floor($totalBinanceWallets / count($users));
            $averageJournals = floor($totalJournals / count($users));
            $averageTrades = floor($totalTrades / count($users));

            $usersWithoutWallet = DB::table('users')
                ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
                ->where('users.user_type', '!=', 'admin')
                ->whereNull('wallets.id')
                ->count();

            $usersWith1To3Wallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6Wallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();

            // $usersWithWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 0 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWallet1To3 = $usersWithWallet1To3Result ? intval($usersWithWallet1To3Result->total_users) : 0;

            // $usersWithWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWallet4To6 = $usersWithWallet4To6Result ? intval($usersWithWallet4To6Result->total_users) : 0;

            // $usersWithWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithWalletMoreThan6 = $usersWithWalletMoreThan6Result ? intval($usersWithWalletMoreThan6Result->total_users) : 0;

            $walletDistribution = [
                '0' => $usersWithoutWallet,
                '1-3' => $usersWith1To3Wallets,
                '4-6' => $usersWith4To6Wallets,
                '>6' => $usersWithWalletMoreThan6
            ];

            $usersWithoutBinanceWallet = DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('wallets')
                        ->whereRaw('wallets.user_id = users.id')
                        ->whereNotNull('wallets.binance_api_key');
                })
                ->count();

            $usersWith1To3BinanceWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6BinanceWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithBinanceWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '<>', '')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();


            // $usersWithBinanceWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 1 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWallet1To3 = $usersWithBinanceWallet1To3Result ? $usersWithBinanceWallet1To3Result->total_users : 0;

            // $usersWithBinanceWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWallet4To6 = $usersWithBinanceWallet4To6Result ? $usersWithBinanceWallet4To6Result->total_users : 0;

            // $usersWithBinanceWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '<>', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithBinanceWalletMoreThan6 = $usersWithBinanceWalletMoreThan6Result ? intval($usersWithBinanceWalletMoreThan6Result->total_users) : 0;

            $binanceWalletDistribution = [
                '0' => $usersWithoutBinanceWallet,
                '1-3' => $usersWith1To3BinanceWallets,
                '4-6' => $usersWith4To6BinanceWallets,
                '>6' => $usersWithBinanceWalletMoreThan6
            ];

            $usersWithoutManualWallet = DB::table('users')
                ->where('user_type', '!=', 'admin')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('wallets')
                        ->whereRaw('wallets.user_id = users.id')
                        ->whereNull('wallets.binance_api_key');
                })->count();

            $usersWith1To3ManualWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 1)
            ->having('wallet_count', '<=', 3)
            ->count();

            $usersWith4To6ManualWallets = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>=', 4)
            ->having('wallet_count', '<=', 6)
            ->count();

            $usersWithManualWalletMoreThan6 = DB::table('users')
            ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('users.user_type', '!=', 'admin')
            ->where('wallets.binance_api_key', '=', '')
            ->orWhereNull('wallets.binance_api_key')
            ->select('users.id', DB::raw('count(wallets.id) as wallet_count'))
            ->groupBy('users.id')
            ->having('wallet_count', '>', 6)
            ->count();

            // $usersWithManualWallet1To3Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 1 AND 3')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWallet1To3 = $usersWithManualWallet1To3Result ? intval($usersWithManualWallet1To3Result->total_users) : 0;

            // $usersWithManualWallet4To6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) BETWEEN 4 AND 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWallet4To6 = $usersWithManualWallet4To6Result ? intval($usersWithManualWallet4To6Result->total_users) : 0;

            // $usersWithManualWalletMoreThan6Result = DB::table('users')
            //     ->join('wallets', 'users.id', '=', 'wallets.user_id')
            //     ->where('users.user_type', '!=', 'admin')
            //     ->where('wallets.binance_api_key', '=', '')
            //     ->groupBy('users.id')
            //     ->havingRaw('COUNT(wallets.id) > 6')
            //     ->select(DB::raw('COUNT(DISTINCT users.id) as total_users'))
            //     ->first();

            // $usersWithManualWalletMoreThan6 = $usersWithManualWalletMoreThan6Result ? intval($usersWithManualWalletMoreThan6Result->total_users) : 0;

            $manualWalletDistribution = [
                '0' => $usersWithoutManualWallet,
                '1-3' => $usersWith1To3ManualWallets,
                '4-6' => $usersWith4To6ManualWallets,
                '>6' => $usersWithManualWalletMoreThan6
            ];

            $journalCount = DB::table('journals')
                ->select(DB::raw('user_id, count(*) as count'))
                ->groupBy('user_id')
                ->get();

            $journalDistribution = [
                '0' => 0,
                '1-3' => 0,
                '4-6' => 0,
                '>6' => 0,
            ];

            foreach ($journalCount as $count) {
                $user = User::find($count->user_id);
                if ($user->user_type != 'admin') {
                    $journal = $count->count;
                    if ($journal == 0) {
                        $journalDistribution['0'] += 1;
                    } elseif ($journal >= 1 && $journal <= 3) {
                        $journalDistribution['1-3'] += 1;
                    } elseif ($journal >= 3 && $journal <= 5) {
                        $journalDistribution['3-5'] += 1;
                    } elseif ($journal > 5) {
                        $journalDistribution['>5'] += 1;
                    }
                }
            }

            $tradeDistribution = [
                '0' => 0,
                '1-100' => 0,
                '101-500' => 0,
                '501-1500' => 0,
                '>1500' => 0,
            ];

            foreach ($users as $user) {
                $journals = $user->journals;

                $trades_count = 0;
                foreach ($journals as $journal) {
                    $trades = DB::table('trades')
                        ->where('journal_id', $journal->id)
                        ->get();

                    $trades_count += count($trades);
                }

                if ($trades_count == 0) {
                    $tradeDistribution['0']++;
                } elseif ($trades_count <= 100) {
                    $tradeDistribution['1-100']++;
                } elseif ($trades_count <= 500) {
                    $tradeDistribution['101-500']++;
                } elseif ($trades_count <= 1500) {
                    $tradeDistribution['501-1500']++;
                } else {
                    $tradeDistribution['>1500']++;
                }
            }
        }

        $data = [
            'total_users' => $total_users,
            'gender_distribution' => $genderDistribution,
            'averageAge' => $averageAge,
            'age_distribution' => $ageRangeCounts,
            'membership_distribution' => $membership_distribution,
            'wallet_distribution' => $walletDistribution,
            'binance_wallet_distribution' => $binanceWalletDistribution,
            'manual_wallet_distribution' => $manualWalletDistribution,
            'journal_distribution' => $journalDistribution,
            'trade_distribution' => $tradeDistribution,
            'averageWallets' => $averageWallets,
            'averageManualWallets' => $averageManualWallets,
            'averageBinanceWallets' => $averageBinanceWallets,
            'averageJournals' => $averageJournals,
            'averageTrades' => $averageTrades,
        ];

        return view('admin.users.demography_print', compact('data'));
    }

    public function full_report()
    {
        $users = User::where('user_type', '!=', 'admin')->get();

        $total_users = $users->count();
        $genderDistribution = [
            'Pria' => 0,
            'Wanita' => 0,
            'Tidak Diketahui' => 0,
        ];
        $ageRangeCounts = [
            '<20' => 0,
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '>50' => 0,
        ];
        $usersLast30Days = 0;
        $newUsersPerDayCumulative = null;
        $verifiedUsers = 0;
        $blockedUsers = 0;
        $walletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $walletCount = 0;
        $journalCount = 0;
        $tradeCount = 0;
        $transactions = null;
        $categories = null;
        $publicAddedStrategies = 0;
        $mostFavoriteStrategies = null;
        $mostUsedStrategies = null;

        if ($total_users > 0) {
            $maleCount = $users->where('gender', 'm')->count();
            $femaleCount = $users->where('gender', 'f')->count();
            $unknownCount = $users->whereNull('gender')->count();
            $totalCount = $users->count();

            $genderDistribution = [
                'Pria' => $maleCount,
                'Wanita' => $femaleCount,
                'Tidak Diketahui' => $unknownCount,
            ];


            $usersAge = $users->map(function ($user) {
                return Carbon::parse($user->birthdate)->age;
            });


            $ageRanges = ['<20', '20-29', '30-39', '40-49', '>50'];
            $ageRangeCounts = $usersAge->groupBy(function ($age) {
                if ($age < 20) {
                    return '<20';
                } elseif ($age < 30) {
                    return '20-29';
                } elseif ($age < 40) {
                    return '30-39';
                } elseif ($age < 50) {
                    return '40-49';
                } else {
                    return '>50';
                }
            })->map(function ($ageRange) {
                return $ageRange->count();
            });

            $ageRangeCounts = array_merge(array_fill_keys($ageRanges, 0), $ageRangeCounts->toArray());

            $usersLast30Days = User::where('user_type', '!=', 'admin')->where('created_at', '>=', Carbon::now()->subDays(30))->count();
            $newUsersPerDay = User::where('user_type', '!=', 'admin')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $runningTotal = 0;
            $newUsersPerDayCumulative = $newUsersPerDay->map(function ($item) use (&$runningTotal) {
                $runningTotal += $item->count;
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                    'cumulative_count' => $runningTotal
                ];
            });

            $verifiedUsers = $users->whereNotNull('email_verified_at')->count();

            $blockedUsers = User::where('user_type', '!=', 'admin')->whereNotNull('deleted_at')->withTrashed()->count();

            $binanceWalletCount = Wallet::whereNotNull('binance_api_key')->withTrashed()->count();
            // dd($binanceWalletCount);

            $manualWalletCount = Wallet::whereNull('binance_api_key')->withTrashed()->count();

            $walletDistribution = [
                'Binance' => $binanceWalletCount,
                'Manual' => $manualWalletCount,
            ];

            $walletCount = Wallet::withTrashed()->count();
            $journalCount = Journal::withTrashed()->count();
            $tradeCount = Trade::count();

            $transactions = DB::table('memberships')
                ->leftJoin('transactions', function ($join) {
                    $join->on('transactions.membership_id', '=', 'memberships.id')
                        ->whereIn('transactions.status', ['settlement', 'capture']);
                })
                ->select(
                    DB::raw("CONCAT('Paket ', UPPER(SUBSTRING(memberships.name, 1, 1)), SUBSTRING(memberships.name, 2)) as name"),
                    DB::raw('COUNT(transactions.id) as total_transactions'),
                    DB::raw('COALESCE(SUM(transactions.gross_amount), 0) as total_sales')
                )
                ->groupBy('name')
                ->get();

            $nonMembershipTransactions = DB::table('transactions')
                ->select(DB::raw("'Penambahan 100 Catatan Pengguna' as name"), DB::raw('COUNT(id) as total_transactions'), DB::raw('COALESCE(SUM(gross_amount), 0) as total_sales'))
                ->where('membership_id', 0)
                ->where(function ($query) {
                    $query->where('status', 'settlement')
                        ->orWhere('status', 'capture')
                        ->orWhereNull('status');
                })
                ->get();

            $transactions = $transactions->merge($nonMembershipTransactions);

            $categories = Category::select('name')
                ->withCount('strategies')
                ->get();

            $publicUser = User::where('user_type', '!=', 'admin')->get()->pluck('id');

            $publicAddedStrategies = Strategy::whereIn('user_id', $publicUser)->count();

            $adminCount = Strategy::whereHas('user', function ($query) {
                $query->where('user_type', 'admin');
            })->count();

            $systemCount = Strategy::whereNull('user_id')->count();

            $systemAddedStrategies = $adminCount + $systemCount;

            $mostFavoritedStrategies = StrategyFavorite::join('strategies', 'strategy_favorite.strategy_id', '=', 'strategies.id')
                ->select('strategies.name', DB::raw('COUNT(strategy_favorite.id) as favorite_count'))
                ->groupBy('strategies.name')
                ->orderBy('favorite_count', 'desc')
                ->limit(3)
                ->get();

            $mostUsedStrategies = Trade::join('strategy_trade', 'trades.id', '=', 'strategy_trade.trade_id')
                ->join('strategies', 'strategy_trade.strategy_id', '=', 'strategies.id')
                ->select('strategies.name', DB::raw('COUNT(trades.id) as trade_count'))
                ->groupBy('strategies.name')
                ->orderBy('trade_count', 'desc')
                ->limit(3)
                ->get();
        }

        $data = [
            'total_users' => $total_users,
            'gender_distribution' => $genderDistribution,
            'age_distribution' => $ageRangeCounts,
            'usersLast30Days' => $usersLast30Days,
            'newUsersPerDay' => $newUsersPerDayCumulative,
            'verifiedUsers' => $verifiedUsers,
            'blockedUsers' => $blockedUsers,
            'wallet_distribution' => $walletDistribution,
            'walletCount' => $walletCount,
            'journalCount' => $journalCount,
            'tradeCount' => $tradeCount,
            'transactions' => $transactions,
            'categories' => $categories,
            'publicAddedStrategies' => $publicAddedStrategies,
            'systemAddedStrategies' => $systemAddedStrategies,
            'mostFavoritedStrategies' => $mostFavoritedStrategies,
            'mostUsedStrategies' => $mostUsedStrategies,
        ];

        return view('admin.report', compact('data'));
    }

    public function full_report_print()
    {
        $users = User::where('user_type', '!=', 'admin')->get();

        $total_users = $users->count();
        $genderDistribution = [
            'Pria' => 0,
            'Wanita' => 0,
            'Tidak Diketahui' => 0,
        ];
        $ageRangeCounts = [
            '<20' => 0,
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '>50' => 0,
        ];
        $usersLast30Days = 0;
        $newUsersPerDayCumulative = null;
        $verifiedUsers = 0;
        $blockedUsers = 0;
        $walletDistribution = [
            '0' => 0,
            '1-3' => 0,
            '4-6' => 0,
            '>6' => 0
        ];
        $walletCount = 0;
        $journalCount = 0;
        $tradeCount = 0;
        $transactions = null;
        $categories = null;
        $publicAddedStrategies = 0;
        $mostFavoriteStrategies = null;
        $mostUsedStrategies = null;

        if ($total_users > 0) {
            $maleCount = $users->where('gender', 'm')->count();
            $femaleCount = $users->where('gender', 'f')->count();
            $unknownCount = $users->whereNull('gender')->count();
            $totalCount = $users->count();

            $genderDistribution = [
                'Pria' => $maleCount,
                'Wanita' => $femaleCount,
                'Tidak Diketahui' => $unknownCount,
            ];


            $usersAge = $users->map(function ($user) {
                return Carbon::parse($user->birthdate)->age;
            });


            $ageRanges = ['<20', '20-29', '30-39', '40-49', '>50'];
            $ageRangeCounts = $usersAge->groupBy(function ($age) {
                if ($age < 20) {
                    return '<20';
                } elseif ($age < 30) {
                    return '20-29';
                } elseif ($age < 40) {
                    return '30-39';
                } elseif ($age < 50) {
                    return '40-49';
                } else {
                    return '>50';
                }
            })->map(function ($ageRange) {
                return $ageRange->count();
            });

            $ageRangeCounts = array_merge(array_fill_keys($ageRanges, 0), $ageRangeCounts->toArray());

            $usersLast30Days = User::where('user_type', '!=', 'admin')->where('created_at', '>=', Carbon::now()->subDays(30))->count();
            $newUsersPerDay = User::where('user_type', '!=', 'admin')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $runningTotal = 0;
            $newUsersPerDayCumulative = $newUsersPerDay->map(function ($item) use (&$runningTotal) {
                $runningTotal += $item->count;
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                    'cumulative_count' => $runningTotal
                ];
            });

            // dd($newUsersPerDay->pluck('date')->last());

            $verifiedUsers = $users->whereNotNull('email_verified_at')->count();

            $blockedUsers = User::where('user_type', '!=', 'admin')->whereNotNull('deleted_at')->withTrashed()->count();

            $binanceWalletCount = Wallet::whereNotNull('binance_api_key')->withTrashed()->count();
            // dd($binanceWalletCount);

            $manualWalletCount = Wallet::whereNull('binance_api_key')->withTrashed()->count();

            $walletDistribution = [
                'Binance' => $binanceWalletCount,
                'Manual' => $manualWalletCount,
            ];

            $walletCount = Wallet::withTrashed()->count();
            $journalCount = Journal::withTrashed()->count();
            $tradeCount = Trade::count();

            $transactions = DB::table('memberships')
                ->leftJoin('transactions', function ($join) {
                    $join->on('transactions.membership_id', '=', 'memberships.id')
                        ->whereIn('transactions.status', ['settlement', 'capture']);
                })
                ->select(
                    DB::raw("CONCAT('Paket ', UPPER(SUBSTRING(memberships.name, 1, 1)), SUBSTRING(memberships.name, 2)) as name"),
                    DB::raw('COUNT(transactions.id) as total_transactions'),
                    DB::raw('COALESCE(SUM(transactions.gross_amount), 0) as total_sales')
                )
                ->groupBy('name')
                ->get();

            $nonMembershipTransactions = DB::table('transactions')
                ->select(DB::raw("'Penambahan 100 Catatan Pengguna' as name"), DB::raw('COUNT(id) as total_transactions'), DB::raw('COALESCE(SUM(gross_amount), 0) as total_sales'))
                ->where('membership_id', 0)
                ->where(function ($query) {
                    $query->where('status', 'settlement')
                        ->orWhere('status', 'capture')
                        ->orWhereNull('status');
                })
                ->get();

            $transactions = $transactions->merge($nonMembershipTransactions);

            $categories = Category::select('name')
                ->withCount('strategies')
                ->get();

            $publicUser = User::where('user_type', '!=', 'admin')->get()->pluck('id');

            $publicAddedStrategies = Strategy::whereIn('user_id', $publicUser)->count();

            $adminCount = Strategy::whereHas('user', function ($query) {
                $query->where('user_type', 'admin');
            })->count();

            $systemCount = Strategy::whereNull('user_id')->count();

            $systemAddedStrategies = $adminCount + $systemCount;

            $mostFavoritedStrategies = StrategyFavorite::join('strategies', 'strategy_favorite.strategy_id', '=', 'strategies.id')
                ->select('strategies.name', DB::raw('COUNT(strategy_favorite.id) as favorite_count'))
                ->groupBy('strategies.name')
                ->orderBy('favorite_count', 'desc')
                ->limit(3)
                ->get();

            $mostUsedStrategies = Trade::join('strategy_trade', 'trades.id', '=', 'strategy_trade.trade_id')
                ->join('strategies', 'strategy_trade.strategy_id', '=', 'strategies.id')
                ->select('strategies.name', DB::raw('COUNT(trades.id) as trade_count'))
                ->groupBy('strategies.name')
                ->orderBy('trade_count', 'desc')
                ->limit(3)
                ->get();
        }

        $data = [
            'total_users' => $total_users,
            'gender_distribution' => $genderDistribution,
            'age_distribution' => $ageRangeCounts,
            'usersLast30Days' => $usersLast30Days,
            'newUsersPerDay' => $newUsersPerDayCumulative,
            'verifiedUsers' => $verifiedUsers,
            'blockedUsers' => $blockedUsers,
            'wallet_distribution' => $walletDistribution,
            'walletCount' => $walletCount,
            'journalCount' => $journalCount,
            'tradeCount' => $tradeCount,
            'transactions' => $transactions,
            'categories' => $categories,
            'publicAddedStrategies' => $publicAddedStrategies,
            'systemAddedStrategies' => $systemAddedStrategies,
            'mostFavoritedStrategies' => $mostFavoritedStrategies,
            'mostUsedStrategies' => $mostUsedStrategies,
        ];

        return view('admin.report_print', compact('data'));
    }
}
