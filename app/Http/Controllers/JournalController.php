<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasPermissionTo('journal')) {
            $data = User::findOrFail(Auth::id());
            return view('users.journals.list', compact('data'));
        } else {
            abort(403);
        }
    }

    public function store(StoreJournalRequest $request)
    {
        if (Auth::user()->hasPermissionTo('journal-tambah')) {

            if (Auth::user()->max_journals == 0 or Auth::user()->journals->count() < Auth::user()->max_journals) {
                $wallet = Auth::user()->journals()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Jurnal berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Jurnal gagal ditambahkan');
                }
            } else if (Auth::user()->max_journals == -1) {
                $wallet = Auth::user()->journals()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Jurnal berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Jurnal gagal ditambahkan');
                }
            } else {
                return redirect()->back()->withError('Jumlah jurnal yang dimiliki pengguna sudah mencapai batasnya');
            }
        } else {
            abort(403);
        }
    }

    public function show(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            return view('users.journals.show', compact('journal'));
        } else {
            abort(403);
        }
    }

    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-ubah')) {
            $success = $journal->update($request->all());
            if ($success) {
                return redirect()->back()->withSuccess('Jurnal berhasil diubah');
            } else {
                return redirect()->back()->withError('Jurnal gagal diubah');
            }
        } else {
            abort(403);
        }
    }

    public function destroy(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-hapus')) {
            $delete = $journal->delete();
            if ($delete) {
                return redirect()->back()->withSuccess('Jurnal berhasil dinonaktifkan');
            } else {
                return redirect()->back()->withError('Jurnal gagal dinonaktifkan');
            }
        } else {
            abort(403);
        }
    }

    public function restore(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-hapus')) {
            $restore = $journal->restore();
            if ($restore) {
                return redirect()->back()->withSuccess('Jurnal berhasil diaktifkan');
            } else {
                return redirect()->back()->withError('Jurnal gagal diaktifkan');
            }
        } else {
            abort(403);
        }
    }

    public function metric()
    {
        if (Auth::user()->hasPermissionTo('journal')) {
            $user = User::findOrFail(Auth::id());
            $data = [];
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $maxWin = 0;
            $maxLoss = 0;
            $countFinishTrades = 0;

            $journals = $user->journals;

            $pnlPerDay = [];
            $winratePerDay = [];
            $mergeTrades = collect();

            foreach ($journals as $journal) {
                $mergeTrades = $mergeTrades->merge($journal->close_trades);

                $trades = $journal->close_trades;
                if($trades->count() > 0){
                    foreach ($trades as $trade) {
                        $countFinishTrades++;
                        $totalMargin += $trade->margin;
                        $averageRR += $trade->real_rr;
                        if ($trade->type == 0) {
                            $countShort++;
                            $pnlShort += $trade->nett_pnl;
                        } elseif ($trade->type == 1) {
                            $countLong++;
                            $pnlLong += $trade->nett_pnl;
                        }
                    }

                    $pnlPerDayForJournal = $trades
                        ->where('status', 2) // Hanya trades yang sudah selesai
                        ->groupBy(function ($trade) {
                            return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                        })
                        ->map(function ($tradesPerDay) {
                            return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                        })->toArray();

                    $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal);
                    // Gabungkan array PnL per hari untuk setiap jurnal
                }
            }

            if($mergeTrades->count() > 0){
                $winLossPerDay = $mergeTrades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }
                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $mergeTrades->count();

                $totalDurationInSeconds = $mergeTrades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $mergeTrades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $mergeTrades->max('pnl');
                $maxLoss = $mergeTrades->min('pnl');
                $averageRR /= $countFinishTrades;
            }

            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;

            $dailyProfitData = [];
            $cumulativeProfitData = [];
            $profitSum = 0;

            foreach($journals as $journal) {
                foreach($journal->close_trades as $trade) {
                    $profitSum += $trade->nett_pnl;
                    $closeTime = Carbon::parse($trade->close_time);
                    $date = $closeTime->format('Y-m-d');
                    if (!array_key_exists($date, $dailyProfitData)) {
                        $dailyProfitData[$date] = 0;
                    }
                    $dailyProfitData[$date] += $trade->nett_pnl;
                    if (!array_key_exists($date, $cumulativeProfitData)) {
                        $cumulativeProfitData[$date] = $profitSum;
                    }
                    else {
                        $cumulativeProfitData[$date] += $trade->nett_pnl;
                    }
                }
            }

            $dates = array_keys($dailyProfitData);
            // dd($dates);
            $categories = [];
            foreach ($dates as $date) {
                $closeTime = Carbon::parse($date);
                $formatted = $closeTime->format('Y-m-d');
                $categories[] = $formatted;
                // array_unshift($categories, $formatted);
            }

            // konversi $dailyProfitData ke dalam format array
            $dailyProfitArray = [];
            foreach ($dailyProfitData as $date => $profit) {
                $dailyProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($dailyProfitArray, [$date, $profit]);
            }

            // konversi $cumulativeProfitData ke dalam format array
            $cumulativeProfitArray = [];
            foreach ($cumulativeProfitData as $date => $profit) {
                $cumulativeProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($cumulativeProfitArray, [$date, $profit]);
            }
            // sort($dates);

            return view('users.journals.metric', compact('user', 'data', 'categories', 'dailyProfitArray', 'cumulativeProfitArray'));
        } else {
            abort(403);
        }
    }

    public function metric_print()
    {
        if (Auth::user()->hasPermissionTo('journal')) {
            $user = User::findOrFail(Auth::id());
            $data = [];
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $maxWin = 0;
            $maxLoss = 0;
            $countFinishTrades = 0;

            $journals = $user->journals;

            $pnlPerDay = [];
            $winratePerDay = [];
            $mergeTrades = collect();

            foreach ($journals as $journal) {
                $mergeTrades = $mergeTrades->merge($journal->close_trades);

                $trades = $journal->close_trades;
                if($trades->count() > 0){
                    foreach ($trades as $trade) {
                        $countFinishTrades++;
                        $totalMargin += $trade->margin;
                        $averageRR += $trade->real_rr;
                        if ($trade->type == 0) {
                            $countShort++;
                            $pnlShort += $trade->nett_pnl;
                        } elseif ($trade->type == 1) {
                            $countLong++;
                            $pnlLong += $trade->nett_pnl;
                        }
                    }

                    $pnlPerDayForJournal = $trades
                        ->where('status', 2) // Hanya trades yang sudah selesai
                        ->groupBy(function ($trade) {
                            return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                        })
                        ->map(function ($tradesPerDay) {
                            return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                        })->toArray();

                    $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal);
                    // Gabungkan array PnL per hari untuk setiap jurnal
                }
            }

            if($mergeTrades->count() > 0){
                $winLossPerDay = $mergeTrades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }
                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $mergeTrades->count();

                $totalDurationInSeconds = $mergeTrades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $mergeTrades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $mergeTrades->max('pnl');
                $maxLoss = $mergeTrades->min('pnl');
                $averageRR /= $countFinishTrades;
            }

            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;

            $dailyProfitData = [];
            $cumulativeProfitData = [];
            $profitSum = 0;

            foreach($journals as $journal) {
                foreach($journal->close_trades as $trade) {
                    $profitSum += $trade->nett_pnl;
                    $closeTime = Carbon::parse($trade->close_time);
                    $date = $closeTime->format('Y-m-d');
                    if (!array_key_exists($date, $dailyProfitData)) {
                        $dailyProfitData[$date] = 0;
                    }
                    $dailyProfitData[$date] += $trade->nett_pnl;
                    if (!array_key_exists($date, $cumulativeProfitData)) {
                        $cumulativeProfitData[$date] = $profitSum;
                    }
                    else {
                        $cumulativeProfitData[$date] += $trade->nett_pnl;
                    }
                }
            }

            $dates = array_keys($dailyProfitData);
            // dd($dates);
            $categories = [];
            foreach ($dates as $date) {
                $closeTime = Carbon::parse($date);
                $formatted = $closeTime->format('Y-m-d');
                $categories[] = $formatted;
                // array_unshift($categories, $formatted);
            }

            // konversi $dailyProfitData ke dalam format array
            $dailyProfitArray = [];
            foreach ($dailyProfitData as $date => $profit) {
                $dailyProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($dailyProfitArray, [$date, $profit]);
            }

            // konversi $cumulativeProfitData ke dalam format array
            $cumulativeProfitArray = [];
            foreach ($cumulativeProfitData as $date => $profit) {
                $cumulativeProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($cumulativeProfitArray, [$date, $profit]);
            }
            // sort($dates);

            return view('users.journals.metric_print', compact('user', 'data', 'categories', 'dailyProfitArray', 'cumulativeProfitArray'));
        } else {
            abort(403);
        }
    }

    public function detail_metric(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            $user = User::findOrFail(Auth::id());
            $data = [];
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $maxWin = 0;
            $maxLoss = 0;

            $pnlPerDay = [];
            $winratePerDay = [];

            $trades = $journal->close_trades;

            if($trades->count() > 0){
                foreach ($trades as $trade) {
                    $totalMargin += $trade->margin;
                    $averageRR += $trade->real_rr;
                    if ($trade->type == 0) {
                        $countShort++;
                        $pnlShort += $trade->nett_pnl;
                    } elseif ($trade->type == 1) {
                        $countLong++;
                        $pnlLong += $trade->nett_pnl;
                    }
                }

                $pnlPerDayForJournal = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                    })
                    ->map(function ($tradesPerDay) {
                        return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                    })->toArray();

                $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal); // Gabungkan array PnL per hari untuk setiap jurnal

                $winLossPerDay = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }
                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $trades->count();

                $totalDurationInSeconds = $trades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $trades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $trades->max('pnl');
                $maxLoss = $trades->min('pnl');
                $averageRR /= $trades->count();
            }

            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;

            $dailyProfitData = [];
            $cumulativeProfitData = [];
            $profitSum = 0;

            foreach($journal->close_trades as $trade) {
                $profitSum += $trade->nett_pnl;
                $closeTime = Carbon::parse($trade->close_time);
                $date = $closeTime->format('Y-m-d');
                if (!array_key_exists($date, $dailyProfitData)) {
                    $dailyProfitData[$date] = 0;
                }
                $dailyProfitData[$date] += $trade->nett_pnl;
                if (!array_key_exists($date, $cumulativeProfitData)) {
                    $cumulativeProfitData[$date] = $profitSum;
                }
                else {
                    $cumulativeProfitData[$date] += $trade->nett_pnl;
                }
            }

            $dates = array_keys($dailyProfitData);
            // dd($dates);
            $categories = [];
            foreach ($dates as $date) {
                $closeTime = Carbon::parse($date);
                $formatted = $closeTime->format('Y-m-d');
                $categories[] = $formatted;
                // array_unshift($categories, $formatted);
            }

            // konversi $dailyProfitData ke dalam format array
            $dailyProfitArray = [];
            foreach ($dailyProfitData as $date => $profit) {
                $dailyProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($dailyProfitArray, [$date, $profit]);
            }

            // konversi $cumulativeProfitData ke dalam format array
            $cumulativeProfitArray = [];
            foreach ($cumulativeProfitData as $date => $profit) {
                $cumulativeProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($cumulativeProfitArray, [$date, $profit]);
            }
            // sort($dates);

            return view('users.journals.detail_metric', compact('user', 'journal', 'data', 'categories', 'dailyProfitArray', 'cumulativeProfitArray'));
        } else {
            abort(403);
        }
    }

    public function detail_metric_print(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            $user = User::findOrFail(Auth::id());
            $data = [];
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $maxWin = 0;
            $maxLoss = 0;

            $pnlPerDay = [];
            $winratePerDay = [];

            $trades = $journal->close_trades;

            if($trades->count() > 0){
                foreach ($trades as $trade) {
                    $totalMargin += $trade->margin;
                    $averageRR += $trade->real_rr;
                    if ($trade->type == 0) {
                        $countShort++;
                        $pnlShort += $trade->nett_pnl;
                    } elseif ($trade->type == 1) {
                        $countLong++;
                        $pnlLong += $trade->nett_pnl;
                    }
                }

                $pnlPerDayForJournal = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                    })
                    ->map(function ($tradesPerDay) {
                        return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                    })->toArray();

                $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal); // Gabungkan array PnL per hari untuk setiap jurnal

                $winLossPerDay = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }
                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $trades->count();

                $totalDurationInSeconds = $trades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $trades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $trades->max('pnl');
                $maxLoss = $trades->min('pnl');
                $averageRR /= $trades->count();
            }

            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;

            $dailyProfitData = [];
            $cumulativeProfitData = [];
            $profitSum = 0;

            foreach($journal->close_trades as $trade) {
                $profitSum += $trade->nett_pnl;
                $closeTime = Carbon::parse($trade->close_time);
                $date = $closeTime->format('Y-m-d');
                if (!array_key_exists($date, $dailyProfitData)) {
                    $dailyProfitData[$date] = 0;
                }
                $dailyProfitData[$date] += $trade->nett_pnl;
                if (!array_key_exists($date, $cumulativeProfitData)) {
                    $cumulativeProfitData[$date] = $profitSum;
                }
                else {
                    $cumulativeProfitData[$date] += $trade->nett_pnl;
                }
            }

            $dates = array_keys($dailyProfitData);
            // dd($dates);
            $categories = [];
            foreach ($dates as $date) {
                $closeTime = Carbon::parse($date);
                $formatted = $closeTime->format('Y-m-d');
                $categories[] = $formatted;
                // array_unshift($categories, $formatted);
            }

            // konversi $dailyProfitData ke dalam format array
            $dailyProfitArray = [];
            foreach ($dailyProfitData as $date => $profit) {
                $dailyProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($dailyProfitArray, [$date, $profit]);
            }

            // konversi $cumulativeProfitData ke dalam format array
            $cumulativeProfitArray = [];
            foreach ($cumulativeProfitData as $date => $profit) {
                $cumulativeProfitArray[] = [$date, round($profit, 2)];
                // array_unshift($cumulativeProfitArray, [$date, $profit]);
            }
            // sort($dates);

            return view('users.journals.detail_metric_print', compact('user', 'journal', 'data', 'categories', 'dailyProfitArray', 'cumulativeProfitArray'));
        } else {
            abort(403);
        }
    }

    public function history(Journal $journal, Request $request){
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            $user = User::findOrFail(Auth::id());
            $data = [];

            $winRate = 0;
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $complianceRate = 0;
            $maxWin = 0;
            $maxLoss = 0;
            $minDuration = null;
            $maxDuration = null;
            $mostAchievedTarget = null;

            $pnlPerDay = [];
            $winratePerDay = [];

            if($request->start and $request->end){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end)->endOfDay();
                $trades = $journal->close_trades->whereBetween('close_time', [$start, $end]);
            }
            else if($request->start){
                $start = Carbon::parse($request->start);
                $trades = $journal->close_trades->where('close_time', '>=', $start);
            }
            else if($request->end){
                $end = Carbon::parse($request->end);
                $trades = $journal->close_trades->where('close_time', '<=', $end);
            }
            else{
                $trades = $journal->close_trades;
            }

            if($trades->count() > 0){
                $totalWins = 0;
                $totalCompliance = 0;
                foreach ($trades as $trade) {
                    $totalMargin += $trade->margin;
                    $averageRR += $trade->real_rr;
                    if ($trade->type == 0) {
                        $countShort++;
                        $pnlShort += $trade->nett_pnl;
                    } elseif ($trade->type == 1) {
                        $countLong++;
                        $pnlLong += $trade->nett_pnl;
                    }
                    if($trade->wl == 1){
                        $totalWins++;
                    }
                    if($trade->nett_pnl < 0){
                        if($journal->risk > 0 and abs($trade->nett_pnl) <= ($journal->balances * $journal->risk) / 100){
                            $totalCompliance++;
                        }
                    }
                    else{
                        $totalCompliance++;
                    }
                }

                $winRate = $totalWins / $trades->count() * 100;
                $complianceRate = $totalCompliance / $trades->count() * 100;

                $pnlPerDayForJournal = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                    })
                    ->map(function ($tradesPerDay) {
                        return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                    })->toArray();

                $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal); // Gabungkan array PnL per hari untuk setiap jurnal

                $winLossPerDay = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }

                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $trades->count();

                $totalDurationInSeconds = $trades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $trades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $trades->max('pnl');
                $maxLoss = $trades->min('pnl');

                $averageRR /= $trades->count();

                $targets = $trades->pluck('closed_at')->unique();
                $targetCounts = $targets->map(function ($target) use ($trades) {
                    $count = $trades->filter(function ($trade) use ($target) {
                        return $trade->closed_at === $target;
                    })->count();

                    return [
                        'target' => $target,
                        'count' => $count,
                        'percentage' => $count / $trades->count() * 100,
                    ];
                });

                $sortedTargets = $targetCounts->sortByDesc('count');
                $mostAchievedTarget = $sortedTargets->first();
                // dd($mostAchievedTarget);

                $sorted_trades = $trades->sortBy(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60 + $trade->diff_hours * 60 * 60 + $trade->diff_minutes * 60 + $trade->diff_seconds;
                });

                // dd($sorted_trades);
                $minDuration = $sorted_trades->first();
                $maxDuration = $sorted_trades->last();
            }
            $data['winRate'] = $winRate;
            $data['complianceRate'] = $complianceRate;
            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;
            $data['duration']['min'] = $minDuration;
            $data['duration']['max'] = $maxDuration;
            if($request->start){
                $data['start'] = $request->start;
            }
            else{
                $data['start'] = $trades->first()->close_time;
            }
            if($request->end){
                $data['end'] = $request->end;
            }
            else{
                $data['end'] = $trades->last()->close_time;
            }
            $data['mostAchievedTarget'] = $mostAchievedTarget;

            return view('users.journals.history', compact('user', 'journal', 'data', 'trades'));
        } else {
            abort(403);
        }
    }
    public function history_print(Journal $journal, Request $request){
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            $user = User::findOrFail(Auth::id());
            $data = [];

            $winRate = 0;
            $totalMargin = 0;
            $averageRR = 0;
            $countLong = 0;
            $pnlLong = 0;
            $countShort = 0;
            $pnlShort = 0;
            $averagePNLPerDay = 0;
            $averageWRPerDay = 0;
            $averageDuration = null;
            $averagePNLPerTransaction = 0;
            $complianceRate = 0;
            $maxWin = 0;
            $maxLoss = 0;
            $minDuration = null;
            $maxDuration = null;
            $mostAchievedTarget = null;

            $pnlPerDay = [];
            $winratePerDay = [];

            if($request->start and $request->end){
                $start = Carbon::parse($request->start);
                $end = Carbon::parse($request->end)->endOfDay();
                $trades = $journal->close_trades->whereBetween('close_time', [$start, $end]);
            }
            else if($request->start){
                $start = Carbon::parse($request->start);
                $trades = $journal->close_trades->where('close_time', '>=', $start);
            }
            else if($request->end){
                $end = Carbon::parse($request->end);
                $trades = $journal->close_trades->where('close_time', '<=', $end);
            }
            else{
                $trades = $journal->close_trades;
            }

            if($trades->count() > 0){
                $totalWins = 0;
                $totalCompliance = 0;
                foreach ($trades as $trade) {
                    $totalMargin += $trade->margin;
                    $averageRR += $trade->real_rr;
                    if ($trade->type == 0) {
                        $countShort++;
                        $pnlShort += $trade->nett_pnl;
                    } elseif ($trade->type == 1) {
                        $countLong++;
                        $pnlLong += $trade->nett_pnl;
                    }
                    if($trade->wl == 1){
                        $totalWins++;
                    }
                    if($trade->nett_pnl < 0){
                        if($journal->risk > 0 and abs($trade->nett_pnl) <= ($journal->balances * $journal->risk) / 100){
                            $totalCompliance++;
                        }
                    }
                    else{
                        $totalCompliance++;
                    }
                }

                $winRate = $totalWins / $trades->count() * 100;
                $complianceRate = $totalCompliance / $trades->count() * 100;

                $pnlPerDayForJournal = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time)); // Kelompokkan berdasarkan tanggal
                    })
                    ->map(function ($tradesPerDay) {
                        return $tradesPerDay->sum('pnl'); // Hitung total PnL per hari
                    })->toArray();

                $pnlPerDay = array_merge_recursive($pnlPerDay, $pnlPerDayForJournal); // Gabungkan array PnL per hari untuk setiap jurnal

                $winLossPerDay = $trades
                    ->groupBy(function ($trade) {
                        return date('Y-m-d', strtotime($trade->close_time));
                    })
                    ->map(function ($tradesPerDay) {
                        $wins = $tradesPerDay->where('wl', 1)->count();
                        $losses = $tradesPerDay->where('wl', -1)->count();
                        return ['wins' => $wins, 'losses' => $losses];
                    })
                    ->toArray();

                $winratePerDay = [];
                foreach ($winLossPerDay as $date => $winLoss) {
                    $totalTrades = $winLoss['wins'] + $winLoss['losses'];
                    $winratePerDay[$date] = $totalTrades > 0 ? ($winLoss['wins'] / $totalTrades) * 100 : 0;
                }
                $averageWRPerDay = collect($winratePerDay)->avg();

                $totalPNL = 0;
                foreach ($pnlPerDay as $pnl) {
                    $totalPNL += $pnl;
                }

                $averagePNLPerDay = $totalPNL / count($pnlPerDay);
                $averagePNLPerTransaction = $totalPNL / $trades->count();

                $totalDurationInSeconds = $trades->sum(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60
                        + $trade->diff_hours * 60 * 60
                        + $trade->diff_minutes * 60
                        + $trade->diff_seconds;
                });

                $averageDurationInSeconds = $totalDurationInSeconds / $trades->count();

                $averageDuration = [
                    'days' => floor($averageDurationInSeconds / (24 * 60 * 60)),
                    'hours' => floor(($averageDurationInSeconds % (24 * 60 * 60)) / (60 * 60)),
                    'minutes' => floor(($averageDurationInSeconds % (60 * 60)) / 60),
                    'seconds' => floor($averageDurationInSeconds % 60),
                ];

                $maxWin = $trades->max('pnl');
                $maxLoss = $trades->min('pnl');

                $averageRR /= $trades->count();

                $targets = $trades->pluck('closed_at')->unique();
                $targetCounts = $targets->map(function ($target) use ($trades) {
                    $count = $trades->filter(function ($trade) use ($target) {
                        return $trade->closed_at === $target;
                    })->count();

                    return [
                        'target' => $target,
                        'count' => $count,
                        'percentage' => $count / $trades->count() * 100,
                    ];
                });

                $sortedTargets = $targetCounts->sortByDesc('count');
                $mostAchievedTarget = $sortedTargets->first();
                // dd($mostAchievedTarget);

                $sorted_trades = $trades->sortBy(function ($trade) {
                    return $trade->diff_days * 24 * 60 * 60 + $trade->diff_hours * 60 * 60 + $trade->diff_minutes * 60 + $trade->diff_seconds;
                });

                // dd($sorted_trades);
                $minDuration = $sorted_trades->first();
                $maxDuration = $sorted_trades->last();
            }
            $data['winRate'] = $winRate;
            $data['complianceRate'] = $complianceRate;
            $data['totalMargin'] = $totalMargin;
            $data['averageRR'] = $averageRR;
            $data['long']['count'] = $countLong;
            $data['long']['pnl'] = $pnlLong;
            $data['short']['count'] = $countShort;
            $data['short']['pnl'] = $pnlShort;
            $data['avgPNLPerDay'] = $averagePNLPerDay;
            $data['avgWRPerDay'] = $averageWRPerDay;
            $data['avgDuration'] = $averageDuration;
            $data['avgPNLPerTransaction'] = $averagePNLPerTransaction;
            $data['pnl']['max'] = $maxWin;
            $data['pnl']['min'] = $maxLoss;
            $data['duration']['min'] = $minDuration;
            $data['duration']['max'] = $maxDuration;
            if($request->start){
                $data['start'] = $request->start;
            }
            else{
                $data['start'] = $trades->first()->close_time;
            }
            if($request->end){
                $data['end'] = $request->end;
            }
            else{
                $data['end'] = $trades->last()->close_time;
            }
            $data['mostAchievedTarget'] = $mostAchievedTarget;

            return view('users.journals.history_print', compact('user', 'journal', 'data', 'trades'));
        } else {
            abort(403);
        }
    }
}
