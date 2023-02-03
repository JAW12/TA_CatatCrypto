<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Http\Requests\StoreLibraryRequest;
use App\Http\Requests\UpdateLibraryRequest;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $favorit = 0;
        return view('users.strategies.index', compact('favorit'));
    }

    public function favorite(){
        $favorit = 1;
        return view('users.strategies.index', compact('favorit'));
    }

    public function search(Request $request){
        $user_id = Auth::id();

        $results = Strategy::select('id', 'category_id', 'name', 'description', 'url_picture')->with('users', function($query) use ($user_id){
            $query->where('users.id', $user_id);
        });

        $favorit = $request->input('favorit');
        if($favorit == "1"){
            $results->whereHas('users', function($query) use ($user_id){
                $query->where('users.id', $user_id);
            });
        }

        $search = $request->input('search');
        if($search){
            $results->where(function($query) use ($search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }
        $results = $results->orderBy('name', 'ASC')->get();

        return response()->json($results);
    }

    public function like($id){
        $user = Auth::user();

        $strategy = Strategy::findOrFail($id);

        $user->favorite()->attach($strategy);

        return redirect()->back()->withSuccess('Berhasil ditambahkan ke pustaka favorit');
    }

    public function unlike($id){
        $user = Auth::user();

        $strategy = Strategy::findOrFail($id);

        $user->favorite()->detach($strategy);

        return redirect()->back()->withSuccess('Berhasil dihapus dari pustaka favorit');
    }

}
