<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function add(Journal $journal){
        return view('users.journals.trades.index', compact('journal'));
    }

    public function show(Journal $journal, Trade $trade){

    }
}
