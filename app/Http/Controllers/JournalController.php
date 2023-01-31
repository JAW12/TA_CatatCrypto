<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('journal')) {
            $data = User::findOrFail(Auth::id());
            return view('users.journals.list', compact('data'));
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJournalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJournalRequest $request)
    {
        if (Auth::user()->hasPermissionTo('journal-tambah')) {

            if (Auth::user()->max_journals == 0 or Auth::user()->journals->count() < Auth::user()->max_journals) {
                $wallet = Auth::user()->journals()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Jurnal berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Jurnal gagal ditambahkan');
                }
            } else if (Auth::user()->max_journals == -1) {
                $wallet = Auth::user()->journals()->create($request->all());
                if ($wallet) {
                    return redirect()->back()->withSuccess('Jurnal berhasil ditambahkan');
                } else {
                    return redirect()->back()->withError('Jurnal gagal ditambahkan');
                }
            } else {
                return redirect()->back()->withError('Jumlah jurnal yang dimiliki pengguna sudah mencapai batasnya');
            }
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function show(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-daftar')) {
            return view('users.journals.show', compact('journal'));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function edit(Journal $journal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJournalRequest  $request
     * @param  \App\Models\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-ubah')) {
            $success = $journal->update($request->all());
            if ($success) {
                return redirect()->back()->withSuccess('Jurnal berhasil diubah');
            } else {
                return redirect()->back()->withError('Jurnal gagal diubah');
            }
        } else {
            abort(403);
        }
    }

    public function destroy(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-hapus')) {
            $delete = $journal->delete();
            if ($delete) {
                return redirect()->back()->withSuccess('Jurnal berhasil dinonaktifkan');
            } else {
                return redirect()->back()->withError('Jurnal gagal dinonaktifkan');
            }
        } else {
            abort(403);
        }
    }

    public function restore(Journal $journal)
    {
        if (Auth::user()->hasPermissionTo('journal-hapus')) {
            $restore = $journal->restore();
            if ($restore) {
                return redirect()->back()->withSuccess('Jurnal berhasil diaktifkan');
            } else {
                return redirect()->back()->withError('Jurnal gagal diaktifkan');
            }
        } else {
            abort(403);
        }
    }
}
