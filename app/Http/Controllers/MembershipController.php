<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index(){
        return view('users.membership.index');
    }

    public function show($type){
        $membership = Membership::find($type);

        if(Auth::user()->user_type != 'free' && Auth::user()->user_type != 'trial' && Auth::user()->user_type != strtolower($membership->name)){
            return redirect()->back()->withError('Anda masih memiliki membership lain yang aktif saat ini');
        }

        $price = $membership->price + rand(0, 1000);
        return view('users.membership.show', compact('membership', 'price'));
    }
}
