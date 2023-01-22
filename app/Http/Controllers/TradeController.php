<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Strategy;
use App\Models\Timeframe;
use App\Models\Trade;
use App\Models\TradeTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function autocomplete(Request $request){
        $data = [];
        $data = DB::table('assets')
            ->select(DB::raw("thumb as thumb, CONCAT(name,' (', symbol, ')') as label, id AS value"))
            ->where('symbol', 'LIKE', '%'. $request->get('query'). '%')
            ->orWhere('name', 'LIKE', '%'. $request->get('query'). '%')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($data);
    }

    public function add(Journal $journal){
        $timeframes = Timeframe::all();
        $entry_strategies = Strategy::where('category_id' , 1)->get();
        $fibonacci_strategies = Strategy::where('category_id' , 2)->get();
        $candlestick_strategies = Strategy::where('category_id' , 3)->get();
        $chart_strategies = Strategy::where('category_id' , 4)->get();
        $indicator_strategies = Strategy::where('category_id' , 5)->get();
        return view('users.journals.trades.add', compact('journal', 'timeframes', 'entry_strategies', 'fibonacci_strategies', 'candlestick_strategies', 'chart_strategies', 'indicator_strategies'));
    }

    public function store(Journal $journal, Request $request){
        $request->validate([
            'asset_id' => 'required',
            'type' => 'required',
            'leverage' => 'required|numeric',
            'open_price' => 'required|numeric|gt:0',
            'open_quantity' => 'required|numeric|gt:0',
            'initial_margin' => 'required|numeric|gt:0',
            'tp.*' => 'required|numeric|gt:0',
            'tp_pnl.*' => 'required|numeric|gt:0',
            'tp_roe.*' => 'required|numeric|gt:0',
            'sl.*' => 'required|numeric|gt:0',
            'sl_pnl.*' => 'required|numeric|gt:0',
            'sl_roe.*' => 'required|numeric|gt:0',
            'rr_expected' => 'required|numeric|gt:0',
        ]);

        if($request->get('open_time') == null || $request->get('open_time') == ''){
            $request['open_time'] = now();
        }
        // dd($request->all());

        DB::beginTransaction();
        try {
            $trade = $journal->trades()->create([
                'asset_id' => $request->get('asset_id'),
                'status' => 0,
                'type' => $request->get('type'),
                'leverage' => $request->get('leverage'),
                'open_price' => $request->get('open_price'),
                'open_quantity' => $request->get('open_quantity'),
                'open_margin' => $request->get('initial_margin'),
                'open_time' => $request->get('open_time'),
                'rr_expected' => $request->get('rr_expected'),
            ]);

            $tp = $request->get('tp');
            $tp_pnl = $request->get('tp_pnl');
            $tp_roe = $request->get('tp_roe');

            $sl = $request->get('sl');
            $sl_pnl = $request->get('sl_pnl');
            $sl_roe = $request->get('sl_roe');

            if(count($tp) > 0){
                for ($i=0; $i < count($tp); $i++) {
                    $trade_target = new TradeTarget();
                    $trade_target->type = 1;
                    $trade_target->price = $tp[$i];
                    $trade_target->pnl = $tp_pnl[$i];
                    $trade_target->roe = $tp_roe[$i];

                    $trade->targets()->save($trade_target);
                }
            }

            if(count($sl) > 0){
                for ($i=0; $i < count($sl); $i++) {
                    $trade_target = new TradeTarget();
                    $trade_target->type = 0;
                    $trade_target->price = $sl[$i];
                    $trade_target->pnl = $sl_pnl[$i];
                    $trade_target->roe = $sl_roe[$i];

                    $trade->targets()->save($trade_target);
                }
            }

            $timeframe = $request->get('timeframe');
            $tv = $request->get('tv');
            $ss = $request->file('ss');
            if(count($timeframe) > 0){
                for ($i=0; $i < count($timeframe); $i++) {
                    if($tv[$i] != null){
                        $trade->timeframes()->attach($timeframe[$i], [
                            'picture_type' => 0,
                            'url_picture' => url($tv[$i])
                        ]);
                    }
                    else{
                        $tf = Timeframe::find($timeframe[$i]);
                        $fileName = $trade->id . '-' . $tf->name . '-' . time() . '.' . $ss[$i]->extension();
                        $destinationPath = 'images';
                        $ss[$i]->storeAs('uploads', $fileName);
                        $trade->timeframes()->attach($timeframe[$i], [
                            'picture_type' => 1,
                            'url_picture' => $fileName
                        ]);
                    }
                }
            }

            $entry_strategy = $request->get('entry_strategy');
            if($entry_strategy != null){
                if(count($entry_strategy) > 0){
                    foreach ($entry_strategy as $key => $value) {
                        $trade->strategies()->attach($value);
                    }
                }
            }

            $fibonacci_strategy = $request->get('fibonacci_strategy');
            if($fibonacci_strategy != null){
                if(count($fibonacci_strategy) > 0){
                    foreach ($fibonacci_strategy as $key => $value) {
                        $trade->strategies()->attach($value);
                    }
                }
            }

            $candlestick_strategy = $request->get('candlestick_strategy');
            if($candlestick_strategy != null){
                if(count($candlestick_strategy) > 0){
                    foreach ($candlestick_strategy as $key => $value) {
                        $trade->strategies()->attach($value);
                    }
                }
            }

            $chart_strategy = $request->get('chart_strategy');
            if($entry_strategy != null){
                if(count($chart_strategy) > 0){
                    foreach ($chart_strategy as $key => $value) {
                        $trade->strategies()->attach($value);
                    }
                }
            }

            $indicator_strategy = $request->get('indicator_strategy');
            if($indicator_strategy != null){
                if(count($indicator_strategy) > 0){
                    foreach ($indicator_strategy as $key => $value) {
                        $trade->strategies()->attach($value);
                    }
                }
            }

            DB::commit();
            return redirect()->route('user.journal.detail', ['journal' => $journal->id])->withSuccess('Catatan berhasil ditambahkan');
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            // return redirect()->route('user.journal.detail', ['journal' => $journal->id])->withError('Transaksi gagal ditambahkan');
        }
    }

    public function show(Journal $journal, Trade $trade){

    }
}
