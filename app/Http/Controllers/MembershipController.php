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
        $membership = Membership::find($type);

        if(count(Auth::user()->membership) > 0){
            return redirect()->back()->withError('Anda masih memiliki membership lain yang aktif saat ini');
        }

        $price = $membership->price + rand(0, 1000);
        return view('users.membership.show', compact('membership', 'price'));
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
