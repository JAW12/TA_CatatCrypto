<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
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
        $data = User::findOrFail(Auth::id());
        return view('users.journals.list', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJournalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJournalRequest $request)
    {
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function show(Journal $journal)
    {
        return view('users.journals.show', compact('journal'));
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
        $success = $journal->update($request->all());
        if ($success) {
            return redirect()->back()->withSuccess('Jurnal berhasil diubah');
        } else {
            return redirect()->back()->withError('Jurnal gagal diubah');
        }
    }

    public function destroy(Journal $journal)
    {
        $delete = $journal->delete();
        if ($delete) {
            return redirect()->back()->withSuccess('Jurnal berhasil dinonaktifkan');
        } else {
            return redirect()->back()->withError('Jurnal gagal dinonaktifkan');
        }
    }

    public function restore(Journal $journal)
    {
        $restore = $journal->restore();
        if ($restore) {
            return redirect()->back()->withSuccess('Jurnal berhasil diaktifkan');
        } else {
            return redirect()->back()->withError('Jurnal gagal diaktifkan');
        }
    }
}
