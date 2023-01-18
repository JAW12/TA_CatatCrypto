<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function add(Journal $journal){
        return view('users.journals.trades.index', compact('journal'));
    }

    public function show(Journal $journal, Trade $trade){

    }

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
}
