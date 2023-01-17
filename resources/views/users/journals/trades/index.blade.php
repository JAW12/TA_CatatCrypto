<x-app-layout :options="['loading']">
    <x-back-button>{{ route('user.journal.detail', ['journal' => $journal->id]) }}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Tambah Catatan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="row g-0">
                            @csrf
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Informasi Koin</strong></h6>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-6 form-group row gx-3 align-items-center">
                                    <div class="col-3">
                                        <label for="asset" class="text-dark">Koin</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" name="asset" id="asset" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 form-group row gx-3 align-items-center">
                                    <div class="col-2">
                                        <label for="type" class="text-dark">Tipe</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="type" id="type" class="form-control">
                                            <option value="1">LONG</option>
                                            <option value="0">SHORT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-5 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="lev" class="text-dark">Leverage</label></div>
                                    <div class="col-8"><input type="number" name="lev" id="lev"
                                            class="form-control"></div>
                                </div>
                                <div class="col-sm-12 col-md-7 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="open_price" class="text-dark">Harga Entri</label>
                                    </div>
                                    <div class="col-8"><input type="number" name="open_price" id="open_price"
                                            class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 form-group row align-items-center">
                                    <div class="col-sm-12 col-md-5 row gx-2 align-items-center">
                                        <div class="col-4">
                                            <label for="open_quantity" class="text-dark">Jumlah</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="number" name="open_quantity" id="open_quantity"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-7">
                                        <span id="openQuantityInline" class="form-text">
                                            Disarankan 2000 sesuai risk 2%
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-5 form-group row gx-3 align-items-center">
                                    <div class="col-12 text-dark">Margin Awal: <span>$</span></div>
                                </div>
                                <div class="col-sm-12 col-md-7 form-group row gx-3 align-items-center">
                                    <div class="col-4"><label for="open_time" class="text-dark">Waktu Entri</label>
                                    </div>
                                    <div class="col-8"><input type="datetime-local" name="open_time" id="open_time"
                                            class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 text-dark">
                                    <div class="ps-1 mb-3">
                                        Harga Entri Rata-Rata: <span id="average_price">$</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0">
                                <div class="col-sm-12 col-md-12 text-dark">
                                    <div class="ps-2 mb-3">
                                        Sisa Jumlah: <span id="quantity_remaining"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Informasi TP & SL</strong></h6>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0 pe-3">
                                <div
                                    class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between text-dark mb-3">
                                    <span>Harga TP</span>
                                    <button type="button" class="btn btn-sm btn-primary">+ Tambah Harga TP</button>
                                </div>
                                <div id="tp_container" class="col-12">
                                    <div class="row gx-2 gy-2 gy-md-0 align-items-center mb-2">
                                        <div class="col-sm-12 col-md-2">
                                            TP 1
                                            <a href="" class="text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                    height="18" fill="currentColor" class="bi bi-trash-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                </svg></a>
                                        </div>
                                        <div class="col-sm-12 col-md-3"><input type="number" name="" id=""
                                                class="form-control"></div>
                                        <div class="col-sm-12 col-md-7">akan mendapatkan keuntungan <span class="text-success">$12 (100%)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 row g-0 ps-0 ps-md-2 pe-3 pe-md-4 mb-4">
                                <div
                                    class="col-sm-12 col-md-12 d-flex align-items-center justify-content-between text-dark mb-3">
                                    <span>Harga SL</span>
                                    <button type="button" class="btn btn-sm btn-primary">+ Tambah Harga SL</button>
                                </div>
                                <div id="sl_container" class="col-12">
                                    <div class="row gx-2 gy-2 gy-md-0 align-items-center mb-2">
                                        <div class="col-sm-12 col-md-2">
                                            SL 1
                                            <a href="" class="text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                    height="18" fill="currentColor" class="bi bi-trash-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                </svg></a>
                                        </div>
                                        <div class="col-sm-12 col-md-3"><input type="number" name="" id=""
                                                class="form-control"></div>
                                        <div class="col-sm-12 col-md-7">akan mendapatkan kerugian <span class="text-danger">$3 (100%)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-dark">
                                Risk Ratio Terdekat: <span>1</span>
                            </div>
                            <div class="col-12 mb-3">
                                <hr />
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted"><strong>Analisa dan Strategi yang Digunakan</strong></h6>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-sm-12 col-md-3 form-group row gx-0 align-items-center">
                                    <div class="col-4"><label for="timeframe" class="text-dark">Timeframe</label></div>
                                    <div class="col-8"><select name="timeframe" id="timeframe" class="form-control"></select></div>
                                </div>
                                <div class="col-sm-12 col-md-9 form-group row gx-2 align-items-center">
                                    <div class="col-2 text-center"><label for="strategy" class="text-dark">Strategi Entry</label></div>
                                    <div class="col-10"><select name="strategy" id="strategy" class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-12 form-group row gx-0 align-items-center pe-0 pe-md-2">
                                    <div class="col-1"><label for="pattern" class="text-dark">Pattern</label></div>
                                    <div class="col-11"><select name="pattern" id="pattern" class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row g-0">
                                <div class="col-12 form-group row gx-0 align-items-center pe-0 pe-md-2">
                                    <div class="col-1"><label for="indicator" class="text-dark">Indikator</label></div>
                                    <div class="col-11"><select name="indicator" id="indicator" class="form-control"></select></div>
                                </div>
                            </div>
                            <div class="col-12 row g-0" id="screenshot">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
