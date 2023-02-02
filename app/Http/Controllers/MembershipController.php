<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MembershipController extends Controller
{
    public function index(){
        return view('users.membership.index');
    }

    public function show($type){

        if($type > 0){
            $membership = Membership::find($type);

            if(count(Auth::user()->membership) > 0){
                return redirect()->back()->withError('Anda masih memiliki membership lain yang aktif saat ini');
            }

            $rand = rand(0, 999);
            $price = $membership->price + $rand;
        }
        else{
            $membership = collect();
            $membership->id = 0;
            $membership->name = "Penambahan 100 Catatan Trading";
            $rand = rand(0, 999);
            $price = 50000 + $rand;
        }
        return view('users.membership.show', compact('type', 'membership', 'price', 'rand'));
    }

    public function list($id)
    {
        if($id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            return redirect()->route('index');
        }

        return view('users.profile.membership');
    }
}
