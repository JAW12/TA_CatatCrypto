<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class LibraryController extends Controller
{
    public function index()
    {
        $favorit = 0;
        return view('users.strategies.index', compact('favorit'));
    }

    public function favorite()
    {
        $favorit = 1;
        return view('users.strategies.index', compact('favorit'));
    }

    public function search(Request $request)
    {
        $user_id = Auth::id();

        $results = Strategy::select('id', 'user_id', 'category_id', 'name', 'description', 'url_picture')->with('users', function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        })->with('category');

        $favorit = $request->input('favorit');
        if ($favorit == "1") {
            $results->whereHas('users', function ($query) use ($user_id) {
                $query->where('users.id', $user_id);
            });
        }

        $search = $request->input('search');
        if ($search) {
            $results->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }
        $results = $results->orderBy('name', 'ASC')->get();

        return response()->json($results);
    }

    public function like($id)
    {
        $user = Auth::user();

        $strategy = Strategy::findOrFail($id);

        $user->favorite()->attach($strategy);

        return redirect()->back()->withSuccess('Berhasil ditambahkan ke pustaka favorit');
    }

    public function unlike($id)
    {
        $user = Auth::user();

        $strategy = Strategy::findOrFail($id);

        $user->favorite()->detach($strategy);

        return redirect()->back()->withSuccess('Berhasil dihapus dari pustaka favorit');
    }

    public function addPage()
    {
        $categories = Category::all();
        return view('users.strategies.add', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'url_picture' => 'required|file|image'
        ]);

        DB::beginTransaction();
        try {
            //code...
            $strategy = new Strategy();
            $strategy->name = $request->get('name');
            $strategy->user_id = Auth::id();
            $strategy->category_id = $request->get('category_id');
            $strategy->description = $request->get('description');

            $url_picture = $request->file('url_picture');
            if ($url_picture) {
                $fileName = Auth::id() . '-' . $strategy->name . '-' . time() . '.' . $url_picture->extension();
                $destinationPath = 'images';
                $url_picture->storeAs('strategies', $fileName, 'public');
                $strategy->url_picture = "storage/strategies/" . $fileName;
            }

            $strategy->save();
            DB::commit();

            return redirect()->route('user.library')->withSuccess('Berhasil menambahkan pustaka pribadi');
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            return redirect()->route('user.library')->withError('Gagal menambahkan pustaka pribadi');
        }
    }

    public function delete($id)
    {

        DB::beginTransaction();
        try {

            $strategy = Strategy::findOrFail($id);

            if ($strategy->url_picture != "") {
                $image_path = public_path("\\") . $strategy->url_picture;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    $strategy->delete();
                }
            }


            DB::commit();
            return redirect()->back()->withSuccess('Berhasil menghapus pustaka pribadi tersebut');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->withError('Gagal menghapus pustaka pribadi tersebut');
        }
    }
}
