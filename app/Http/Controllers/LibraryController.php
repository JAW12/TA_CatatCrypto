<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Strategy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class LibraryController extends Controller
{
    public function index()
    {
        $favorit = 0;
        $category = "semua";
        return view('users.strategies.index', compact('favorit', 'category'));
    }

    public function favorite()
    {
        $favorit = 1;
        $category = "semua";
        return view('users.strategies.index', compact('favorit', 'category'));
    }

    public function category($category)
    {
        $favorit = 0;
        return view('users.strategies.index', compact('favorit', 'category'));
    }

    public function search(Request $request)
    {
        $user_id = Auth::id();

        $results = Strategy::select('id', 'user_id', 'category_id', 'name', 'description', 'url_picture')->with('users', function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        })->with('category');

        // $results = Strategy::select('id', 'user_id', 'category_id', 'name', 'description', 'url_picture');

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

        $category = $request->input('category');
        if ($category) {
            if($category == "strategi-entri"){
                $results->where(function ($query) use ($search) {
                    $query->where('category_id', 1);
                });
            }
            else if($category == "pola"){
                $results->where(function ($query) use ($search) {
                    $query->where('category_id', '>', 1)->where('category_id', '<', 5);
                });
            }
            else if($category == "indikator"){
                $results->where(function ($query) use ($search) {
                    $query->where('category_id', 5);
                });
            }
        }

        $results = $results->whereNull('user_id')->orWhere('user_id', 0)->orWhere('user_id', $user_id);

        $results = $results->orderBy('name', 'ASC')->get();

        return response()->json($results);
    }

    public function show($id)
    {
        $strategy = Strategy::findOrFail($id);

        return view('users.strategies.show', compact('strategy'));
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
                $url_picture->storeAs('strategies', $fileName, 'public');
                $strategy->url_picture = "storage/strategies/" . $fileName;
            }

            $strategy->save();
            DB::commit();

            return redirect()->route('user.library')->withSuccess('Berhasil menambahkan pustaka pribadi');
        } catch (\Throwable $th) {

            DB::rollback();
            // throw $th;
            return redirect()->route('user.library')->withError('Gagal menambahkan pustaka pribadi');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $strategy = Strategy::findOrFail($id);

            if($strategy->user_id != Auth::id()){
                throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            }

            if ($strategy->url_picture != "") {
                $image_path = public_path("\\") . $strategy->url_picture;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    $strategy->delete();
                }
            }


            DB::commit();
            return redirect()->route('user.library')->withSuccess('Berhasil menghapus pustaka pribadi tersebut');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('user.library')->withError('Gagal menghapus pustaka pribadi tersebut');
        }
    }

    public function edit($id){
        $strategy = Strategy::findOrFail($id);

        if($strategy->user_id != Auth::id()){
            throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $categories = Category::all();
        return view('users.strategies.edit', compact('strategy', 'categories'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'url_picture' => 'file|image'
        ]);

        DB::beginTransaction();
        try {
            $strategy = Strategy::findOrFail($id);

            if($strategy->user_id != Auth::id()){
                throw ValidationException::withMessages(['akses' => 'Anda tidak memiliki akses ke halaman ini.']);
            }

            $strategy->name = $request->get('name');
            $strategy->category_id = $request->get('category_id');
            $strategy->description = $request->get('description');

            $url_picture = $request->file('url_picture');
            if ($url_picture) {
                if ($strategy->url_picture != "") {
                    $image_path = public_path("\\") . $strategy->url_picture;
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }

                $fileName = Auth::id() . '-' . $strategy->name . '-' . time() . '.' . $url_picture->extension();
                $destinationPath = 'images';
                $url_picture->storeAs('strategies', $fileName, 'public');
                $strategy->url_picture = "storage/strategies/" . $fileName;
            }

            $strategy->save();
            DB::commit();

            return redirect()->route('user.library.detail', ['id' => $strategy->id])->withSuccess('Berhasil mengubah pustaka pribadi');
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
            return redirect()->route('user.library.detail', ['id' => $strategy->id])->withError('Gagal mengubah pustaka pribadi');
        }
    }

    public function report(){
        if (Auth::user()->hasPermissionTo('journal')) {
            $user = User::findOrFail(Auth::id());

            $data = DB::table('journals')
            ->join('trades', 'journals.id', '=', 'trades.journal_id')
            ->join('strategy_trade', 'trades.id', '=', 'strategy_trade.trade_id')
            ->join('strategies', 'strategy_trade.strategy_id', '=', 'strategies.id')
            ->join('categories', 'strategies.category_id', '=', 'categories.id')
            ->select(
                'strategies.name as strategy_name',
                'categories.name as category_name',
                'strategies.user_id as strategy_author',
                DB::raw('COUNT(trades.id) as jumlah_trades'),
                DB::raw('ROUND(SUM(CASE WHEN trades.nett_pnl > 0 THEN 1 ELSE 0 END)/COUNT(trades.id)*100, 2) as win_loss_percent'),
                DB::raw('SUM(trades.nett_pnl) as total_profit'),
                DB::raw('ROUND(AVG(trades.nett_pnl), 2) as avg_profit'),
                DB::raw('MAX(trades.nett_pnl) as max_profit'),
                DB::raw('MIN(trades.nett_pnl) as min_profit'),
                DB::raw('CAST(ROUND(AVG(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))), 0) AS INT) as avg_duration'),
                DB::raw('MIN(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))) as min_duration'),
                DB::raw('MAX(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))) as max_duration')
            )
            ->where('journals.user_id', Auth::id())
            ->where('trades.status', 2)
            ->groupBy('strategies.name', 'categories.name', 'strategies.user_id')
            ->get();

            return view('users.strategies.report', compact('user', 'data'));
        } else {
            abort(403);
        }
    }

    public function report_print(){
        if (Auth::user()->hasPermissionTo('journal')) {
            $user = User::findOrFail(Auth::id());

            $data = DB::table('journals')
            ->join('trades', 'journals.id', '=', 'trades.journal_id')
            ->join('strategy_trade', 'trades.id', '=', 'strategy_trade.trade_id')
            ->join('strategies', 'strategy_trade.strategy_id', '=', 'strategies.id')
            ->join('categories', 'strategies.category_id', '=', 'categories.id')
            ->select(
                'strategies.name as strategy_name',
                'categories.name as category_name',
                'strategies.user_id as strategy_author',
                DB::raw('COUNT(trades.id) as jumlah_trades'),
                DB::raw('ROUND(SUM(CASE WHEN trades.nett_pnl > 0 THEN 1 ELSE 0 END)/COUNT(trades.id)*100, 2) as win_loss_percent'),
                DB::raw('SUM(trades.nett_pnl) as total_profit'),
                DB::raw('ROUND(AVG(trades.nett_pnl), 2) as avg_profit'),
                DB::raw('MAX(trades.nett_pnl) as max_profit'),
                DB::raw('MIN(trades.nett_pnl) as min_profit'),
                DB::raw('CAST(ROUND(AVG(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))), 0) AS INT) as avg_duration'),
                DB::raw('MIN(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))) as min_duration'),
                DB::raw('MAX(TIME_TO_SEC(TIMEDIFF(trades.close_time, trades.open_time))) as max_duration')
            )
            ->where('journals.user_id', Auth::id())
            ->where('trades.status', 2)
            ->groupBy('strategies.name', 'categories.name', 'strategies.user_id')
            ->get();

            $dataBarTerbaik = $data->where('total_profit', '>', '0')->sortByDesc('total_profit')->take(3);
            $labelsBarTerbaik = [];
            $seriesBarTerbaik = [];

            foreach ($dataBarTerbaik as $row) {
                array_push($labelsBarTerbaik, $row->strategy_name);
                array_push($seriesBarTerbaik, (int)$row->total_profit);
            }

            $dataBarTerburuk = $data->where('total_profit', '<', '0')->sortBy('total_profit')->take(3);
            $labelsBarTerburuk = [];
            $seriesBarTerburuk = [];

            foreach ($dataBarTerburuk as $row) {
                array_push($labelsBarTerburuk, $row->strategy_name);
                array_push($seriesBarTerburuk, (int)$row->total_profit);
            }

            return view('users.strategies.report_print', compact('user', 'data', 'labelsBarTerbaik', 'seriesBarTerbaik', 'labelsBarTerburuk', 'seriesBarTerburuk'));
        } else {
            abort(403);
        }
    }
}
