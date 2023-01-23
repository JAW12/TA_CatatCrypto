<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Strategy;
use App\Models\Timeframe;
use App\Models\Trade;
use App\Models\TradeTarget;
use App\Models\TradeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


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
                        $ss[$i]->storeAs('uploads', $fileName, 'public');
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

        $timeframes = Timeframe::all();
        $entry_strategies = Strategy::where('category_id' , 1)->get();
        $fibonacci_strategies = Strategy::where('category_id' , 2)->get();
        $candlestick_strategies = Strategy::where('category_id' , 3)->get();
        $chart_strategies = Strategy::where('category_id' , 4)->get();
        $indicator_strategies = Strategy::where('category_id' , 5)->get();
        return view('users.journals.trades.show', compact('journal', 'trade', 'timeframes', 'entry_strategies', 'fibonacci_strategies', 'candlestick_strategies', 'chart_strategies', 'indicator_strategies'));
    }

    public function update(Journal $journal, Trade $trade, Request $request){
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
            'timeframe' => 'required|min:1',
            'timeframe.*' => 'required',
        ]);

        // dd($request->all());
        DB::beginTransaction();

        try {
            $trade->asset_id = $request->get('asset_id');
            $trade->type = $request->get('type');
            $trade->leverage = $request->get('leverage');
            $trade->open_price = $request->get('open_price');
            $trade->open_quantity = $request->get('open_quantity');
            $trade->open_margin = $request->get('initial_margin');
            $trade->rr_expected = $request->get('rr_expected');
            $update = $trade->save();

            $tp = $request->get('tp');
            $tp_pnl = $request->get('tp_pnl');
            $tp_roe = $request->get('tp_roe');

            $sl = $request->get('sl');
            $sl_pnl = $request->get('sl_pnl');
            $sl_roe = $request->get('sl_roe');

            $trade->targets()->delete();

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

            $trade->strategies()->detach();

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

            // foreach ($trade->timeframes as $key => $value) {
            //     if($value->pivot->picture_type == 1){
            //         $image_path = public_path("\storage\uploads\\") . $value->pivot->url_picture;
            //         if(File::exists($image_path)) {
            //             File::delete($image_path);
            //         }
            //     }
            // }

            $trade->timeframes()->wherePivot('picture_type', 0)->detach();
            $timeframe = $request->get('timeframe');
            $tv = $request->get('tv');
            $ss = $request->file('ss');
            if(count($timeframe) > 0){
                for ($i=0; $i < count($timeframe); $i++) {
                    if($tv[$i] != null){
                        $relation = $trade->timeframes()->wherePivot('timeframe_id', $timeframe[$i])->first();
                        if($relation != null){
                            $image_path = public_path("\storage\uploads\\") . $relation->pivot->url_picture;
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                            }
                        }
                        $trade->timeframes()->attach($timeframe[$i], [
                            'picture_type' => 0,
                            'url_picture' => url($tv[$i])
                        ]);
                    }
                    else if($ss != null){
                        if ($request->hasFile('ss') && array_key_exists($i, $ss)) {
                            $relation = $trade->timeframes()->wherePivot('timeframe_id', $timeframe[$i])->first();
                            if($relation != null){
                                $image_path = public_path("\storage\uploads\\") . $relation->pivot->url_picture;
                                if(File::exists($image_path)) {
                                    File::delete($image_path);
                                }
                            }
                            $trade->timeframes()->detach($timeframe[$i]);
                            $tf = Timeframe::findOrFail($timeframe[$i]);
                            $fileName = $trade->id . '-' . $tf->name . '-' . time() . '.' . $ss[$i]->extension();
                            $ss[$i]->storeAs('uploads', $fileName, 'public');
                            $trade->timeframes()->attach($timeframe[$i], [
                                'picture_type' => 1,
                                'url_picture' => $fileName
                            ]);
                        }
                    }
                }
            }

            $hidden_img = $request->get('hidden_img');
            foreach ($hidden_img as $key => $value) {
                if($value == "http://127.0.0.1:8000/storage/uploads"){
                    if($key == 0){
                        $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_1;
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                            $trade->screenshot_url_1 = null;
                            $trade->save();
                        }
                    }
                    if($key == 1){
                        $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_2;
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                            $trade->screenshot_url_2 = null;
                            $trade->save();
                        }
                    }
                    if($key == 2){
                        $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_3;
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                            $trade->screenshot_url_3 = null;
                            $trade->save();
                        }
                    }
                    if($key == 3){
                        $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_4;
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                            $trade->screenshot_url_4 = null;
                            $trade->save();
                        }
                    }
                }
            }

            $img = $request->file('img');
            if($img != null){
                foreach ($img as $key => $value) {
                    if ($request->hasFile('img', $key)){
                        if($key == 0){
                            $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_1;
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $fileName = $trade->id . '-' . '1' . '-' . time() . '.' . $value->extension();
                            $value->storeAs('uploads', $fileName, 'public');
                            $trade->screenshot_url_1 = $fileName;
                            $trade->save();
                        }
                        if($key == 1){
                            $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_2;
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $fileName = $trade->id . '-' . '2' . '-' . time() . '.' . $value->extension();
                            $value->storeAs('uploads', $fileName, 'public');
                            $trade->screenshot_url_2 = $fileName;
                            $trade->save();
                        }
                        if($key == 2){
                            $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_3;
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $fileName = $trade->id . '-' . '3' . '-' . time() . '.' . $value->extension();
                            $value->storeAs('uploads', $fileName, 'public');
                            $trade->screenshot_url_3 = $fileName;
                            $trade->save();
                        }
                        if($key == 3){
                            $image_path = public_path("\storage\uploads\\") . $trade->screenshot_url_4;
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $fileName = $trade->id . '-' . '4' . '-' . time() . '.' . $value->extension();
                            $value->storeAs('uploads', $fileName, 'public');
                            $trade->screenshot_url_4 = $fileName;
                            $trade->save();
                        }
                    }
                }
            }




            DB::commit();
            return redirect()->back()->withSuccess('Catatan berhasil diubah');

        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            return redirect()->back()->withError('Catatan gagal diubah');
        }
    }

    public function store_transaction(Journal $journal, Trade $trade, Request $request){
        $request->validate([
            'transaction_price' => 'required|numeric|gt:0',
            'transaction_quantity' => 'required|numeric|gt:0',
            'transaction_type' => 'required',
        ]);

        if($request->get('transaction_time') == null){
            $request['transaction_time'] = now();
        }

        if($request->get('transaction_type') == 1){
            $request['transaction_quantity'] = -$request['transaction_quantity'];
        }

        $trade_transaction = new TradeTransaction();
        $trade_transaction->type = $request->get('transaction_type');
        $trade_transaction->price = $request->get('transaction_price');
        $trade_transaction->quantity = $request->get('transaction_quantity');
        $trade_transaction->total = $request->get('transaction_price') * $request->get('transaction_quantity');
        $trade_transaction->fee = $request->get('transaction_fee');
        $trade_transaction->time = $request->get('transaction_time');


        if(count($trade->transactions) == 0 && $request->get('transaction_type') == 1){
            return redirect()->back()->withError('Transaksi gagal ditambahkan');
        }
        else if($request->get('transaction_type') == 1 && $request->get('transaction_quantity' > $trade->quantity_remaining)){
            return redirect()->back()->withError('Transaksi gagal ditambahkan');
        }
        else if($request->get('transaction_type') == 1){
            $direction = $trade->type;
            $close_price = $request->get('transaction_price');
            $qty = $request->get('transaction_quantity');
            $profit = 0;
            $average_price = $trade->average_price;
            if ($direction == 1) {
                if($close_price > $average_price){
                    $profit = abs(($average_price - $close_price) * abs($qty));
                }
                else{
                    $profit = ($close_price - $average_price) * abs($qty);
                }
            }
            else if ($direction == 0) {
                if($close_price < $average_price){
                    $profit = abs(($close_price - $average_price) * abs($qty));
                }
                else{
                    $profit = ($average_price - $close_price) * abs($qty);
                }
            }
            $trade_transaction->pnl = $profit;
        }

        $save = $trade->transactions()->save($trade_transaction);

        $trade = Trade::find($trade->id);
        if(count($trade->transactions) > 0){
            if(count($trade->transactions) == 1 && $request->get('transaction_type') == 0){
                $trade->open_time = $request->get('transaction_time');
                $trade->status = 1;
            }

            $trade->quantity_remaining = $trade->transactions->sum('quantity');
            $sumAmountBought = $trade->transactions()->where('type', 0)->sum('quantity');
            $sumTotalBought = $trade->transactions()->where('type', 0)->sum('total');
            $sumTotalFee = $trade->transactions()->sum('fee');
            $trade->total_fees = $sumTotalFee;

            if ($sumTotalBought > 0 and $sumAmountBought > 0) {
                $average_price = $sumTotalBought / $sumAmountBought;
                $trade->average_price = $average_price;
            }
            $trade->save();

            $margin = $trade->quantity_remaining * $trade->average_price / $trade->leverage;
            $trade->margin = $margin;

            $sumTotalPNL = $trade->transactions()->sum('pnl');
            $trade->pnl = $sumTotalPNL;
            $trade->nett_pnl = $trade->pnl - $trade->total_fees;

            if($trade->quantity_remaining == 0){
                $trade->close_time = $request->get('transaction_time');
                $trade->close_price = $request->get('transaction_price');
                $trade->status = 2;
            }
            $trade->save();
            return redirect()->back()->withSuccess('Transaksi berhasil ditambahkan');
        }
    }

    public function destroy(Journal $journal, Trade $trade){
        DB::beginTransaction();

        try {
            foreach ($trade->timeframes as $key => $value) {
                if($value->pivot->picture_type == 1){
                    $image_path = public_path("\storage\uploads\\") . $value->pivot->url_picture;
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $trade->timeframes()->detach();

            $delete = $trade->delete();
            DB::commit();
            return redirect()->back()->withSuccess('Catatan berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->withError('Catatan gagal dihapus');
        }
    }
}
