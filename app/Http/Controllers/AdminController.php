<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::all();
        return view('admin.membership.list', compact('transactions'));
    }

    public function transaction_detail($id_order)
    {
    }
}
