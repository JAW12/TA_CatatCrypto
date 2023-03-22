@section('title', 'Daftar Membership')
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 mb-3 text-center">
                        <div class="col">
                            <div class="card mb-4 rounded-3 h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 style="height:15%">Pemula</h3>
                                        <h1 class="card-title pricing-card-title">Rp 99k
                                        </h1>
                                        <h6 class="text-muted fw-light">untuk 1 bulan</h6>
                                        <h4 class="my-0 fw-normal mt-3">Basic</h4>
                                        <ul class="list-unstyled my-3">
                                            <li>
                                                <p>Maks 1 Dompet</p>
                                            </li>
                                            <li>
                                                <p>Maks 1 Jurnal</p>
                                            </li>
                                            <li>
                                                <p>Maks 100 Catatan / bln</p>
                                            </li>
                                            <li>
                                                <p>-</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('user.membership.payments', 1) }}" type="button"
                                        class="btn btn-outline-primary @if ((count(Auth::user()->membership) > 0 && date('Y-m-d', strtotime('+3days')) < date_format(date_create(auth()->user()->membership_till), 'Y-m-d')) || count(Auth::user()->transactions->where('status', 'pending')) > 0) disabled @endif">Beli
                                        Basic</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4 rounded-3 h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 style="height:15%">Standar</h3>
                                        <h1 class="card-title pricing-card-title">Rp 299k</h1>
                                        <h6 class="text-muted fw-light">untuk 2 bulan</h6>
                                        <h4 class="my-0 fw-normal mt-3">Home</h4>
                                        <ul class="list-unstyled my-3">
                                            <li>
                                                <p>Maks 3 Dompet</p>
                                            </li>
                                            <li>
                                                <p>Maks 3 Jurnal</p>
                                            </li>
                                            <li>
                                                <p>Maks 500 Catatan / bln</p>
                                            </li>
                                            <li>
                                                <p>Integrasi akun Binance & Transaksi Real Time</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('user.membership.payments', 2) }}" type="button"
                                        class="btn btn-outline-primary @if ((count(Auth::user()->membership) > 0 && date('Y-m-d', strtotime('+3days')) < date_format(date_create(auth()->user()->membership_till), 'Y-m-d')) || count(Auth::user()->transactions->where('status', 'pending')) > 0) disabled @endif">Beli
                                        Home</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4 rounded-3 h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 style="height: 15%" class="text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                                fill="currentColor" class="bi bi-award-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z" />
                                                <path
                                                    d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                                            </svg> Terpopuler
                                        </h3>
                                        <h1 class="card-title pricing-card-title">Rp 459k</h1>
                                        <h6 class="text-muted fw-light">untuk 4 bulan</h6>
                                        <h4 class="my-0 fw-normal mt-3">Professional</h4>
                                        <ul class="list-unstyled my-3">
                                            <li>
                                                <p>Maks 3 Dompet</p>
                                            </li>
                                            <li>
                                                <p>Maks 3 Jurnal</p>
                                            </li>
                                            <li>
                                                <p>Maks 1500 Catatan / bln</p>
                                            </li>
                                            <li>
                                                <p>Integrasi akun Binance & Transaksi Real Time dengan Notifikasi</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('user.membership.payments', 3) }}" type="button"
                                        class="btn btn-outline-primary @if ((count(Auth::user()->membership) > 0 && date('Y-m-d', strtotime('+3days')) < date_format(date_create(auth()->user()->membership_till), 'Y-m-d')) || count(Auth::user()->transactions->where('status', 'pending')) > 0) disabled @endif">Beli
                                        Professional</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4 rounded-3 h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 style="height:15%">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                fill="currentColor" class="bi bi-briefcase-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z" />
                                            </svg> Bisnis
                                        </h3>
                                        <h1 class="card-title pricing-card-title">Rp 1.289k</h1>
                                        <h6 class="text-muted fw-light">untuk 6 bulan</h6>
                                        <h4 class="my-0 fw-normal mt-3">Business</h4>
                                        <ul class="list-unstyled my-3">
                                            <li>
                                                <p>∞ Dompet</p>
                                            </li>
                                            <li>
                                                <p>∞ Jurnal</p>
                                            </li>
                                            <li>
                                                <p>∞ Catatan / bln</p>
                                            </li>
                                            <li>
                                                <p>Integrasi akun Binance & Transaksi Real Time dengan Notifikasi</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('user.membership.payments', 4) }}"
                                        class="btn btn-outline-primary @if ((count(Auth::user()->membership) > 0 && date('Y-m-d', strtotime('+3days')) < date_format(date_create(auth()->user()->membership_till), 'Y-m-d')) || count(Auth::user()->transactions->where('status', 'pending')) > 0) disabled @endif">Beli
                                        Business</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-1">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-3">
                                    <h3 class="block-title">Perbandingan Fitur</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive pricing pt-2">
                                        <table id="my-table" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center prc-wrap"></th>
                                                    <th class="text-center prc-wrap">
                                                        <div class="prc-box">
                                                            <div class="h3 pt-4">Rp 99k<small
                                                                    class="h6">/bln</small>
                                                            </div> <span class="type">Basic</span>
                                                        </div>
                                                    </th>
                                                    <th class="text-center prc-wrap">
                                                        <div class="prc-box">
                                                            <div class="h3 pt-4">Rp 149,5k<small
                                                                    class="h6">/bln</small>
                                                            </div> <span class="type">Home</span>
                                                        </div>
                                                    </th>
                                                    <th class="text-center prc-wrap">
                                                        <div class="prc-box">
                                                            <div class="h3 pt-4">Rp 114,75k<small
                                                                    class="h6">/bln</small>
                                                            </div> <span class="type">Professional</span>
                                                        </div>
                                                    </th>
                                                    <th class="text-center prc-wrap">
                                                        <div class="prc-box">
                                                            <div class="h3 pt-4">Rp 214,83k<small
                                                                    class="h6">/bln</small>
                                                            </div> <span class="type">Business</span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Jumlah Dompet</th>
                                                    <td class="text-center child-cell h4">
                                                        1
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        3
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        3
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        ∞
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Jumlah Jurnal</th>
                                                    <td class="text-center child-cell h4">
                                                        1
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        3
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        3
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        ∞
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Jumlah Catatan / Bln</th>
                                                    <td class="text-center child-cell h4">
                                                        100
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        500
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        1500
                                                    </td>
                                                    <td class="text-center child-cell h4">
                                                        ∞
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Integrasi akun Binance <br>& Transaksi Real Time
                                                    </th>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Vector" d="M4 20L20 4M20 20L4 4"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Polygon 13"
                                                                d="M23 7L6.44526 17.8042C5.85082 18.1921 5.0648 17.9848 4.72059 17.3493L1 10.4798"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Polygon 13"
                                                                d="M23 7L6.44526 17.8042C5.85082 18.1921 5.0648 17.9848 4.72059 17.3493L1 10.4798"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Polygon 13"
                                                                d="M23 7L6.44526 17.8042C5.85082 18.1921 5.0648 17.9848 4.72059 17.3493L1 10.4798"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Notifikasi Transaksi</th>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Vector" d="M4 20L20 4M20 20L4 4"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Vector" d="M4 20L20 4M20 20L4 4"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Polygon 13"
                                                                d="M23 7L6.44526 17.8042C5.85082 18.1921 5.0648 17.9848 4.72059 17.3493L1 10.4798"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                    <td class="text-center child-cell">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path id="Polygon 13"
                                                                d="M23 7L6.44526 17.8042C5.85082 18.1921 5.0648 17.9848 4.72059 17.3493L1 10.4798"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" />
                                                        </svg>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
