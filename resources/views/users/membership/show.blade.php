@section('title', 'Halaman Pembayaran')
@push('scripts')
    <script>
        $("#payment_type").change(function(e) {
            e.preventDefault();
            let nomor = $('option:selected', this).attr('nomor');
            let rekening = $('option:selected', this).attr('rekening');
            let an = $('option:selected', this).attr('an');
            let value = $("#payment_type").val();
            if (value != 'credit_card') {
                $("#nomor").text(nomor);
                $("#rekening").text(rekening);
                $("#an").text(an);
                $("#payment").fadeIn();
                $("#bank_type").fadeIn();
                $("#bank_name").fadeIn();
                $("#transfer_time").fadeIn();
            } else {
                $("#nomor").text("");
                $("#rekening").text("");
                $("#an").text("");
                $("#payment").fadeOut();
                $("#bank_type").fadeOut();
                $("#bank_name").fadeOut();
                $("#transfer_time").fadeOut();
            }
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <x-back-button>{{ route('user.membership') }}</x-back-button>
        <form action="{{ route('user.membership.checkout') }}" method="post" class="form-row">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-lg-7 order-last order-md-first">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Halaman Pembayaran</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6>Informasi Pribadi</h6>
                            <hr>
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="phone_number" class="form-label">No. Telp</label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                    value="{{ Auth::user()->phone_number }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ Auth::user()->email }}">
                            </div>
                            <h6>Informasi Pembayaran</h6>
                            <hr>
                            <div class="form-group">
                                <label for="payment_type" class="form-label">Metode Pembayaran</label>
                                <select name="payment_type" id="payment_type" class="form-control">
                                    <option disabled selected>Pilih salah satu metode pembayaran</option>
                                    <optgroup label="Bank Transfer Manual">
                                        <option value="Manual Transfer Bank BCA" rekening="Bank BCA" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">BCA</option>
                                        <option value="Manual Transfer Bank BNI" rekening="Bank BNI" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">BNI</option>
                                        <option value="Manual Transfer Bank BRI" rekening="Bank BRI" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">BRI</option>
                                        <option value="Manual Transfer Bank Mandiri" rekening="Bank Mandiri" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">Mandiri</option>
                                        <option value="Manual Transfer Bank Jago" rekening="Bank Jago" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">Jago</option>
                                    </optgroup>
                                    <optgroup label="E-Wallet Transfer Manual">
                                        <option value="Manual Transfer E-Wallet GoPay" rekening="GoPay" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">GoPay</option>
                                        <option value="Manual Transfer E-Wallet Dana" rekening="Dana" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">Dana</option>
                                        <option value="Manual Transfer E-Wallet OVO" rekening="OVO" nomor="xxxxxxxxxx" an="Jem Angkasa Wijaya">OVO</option>
                                    </optgroup>
                                    <optgroup label="Pembayaran Otomatis">
                                        <option value="credit_card">Kartu Kredit</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="form-group" id="bank_type" style="display:none">
                                <label for="bank_type" class="form-label">Jenis Bank Anda</label>
                                <input type="text" name="bank_type" class="form-control" placeholder="BCA">
                            </div>
                            <div class="form-group" id="bank_name" style="display:none">
                                <label for="bank_name" class="form-label">Bank Atas Nama</label>
                                <input type="text" name="bank_name" class="form-control" placeholder="James Smith">
                            </div>
                            <div class="form-group" id="transfer_time" style="display:none">
                                <label for="transfer_time" class="form-label">Transfer Pada</label>
                                <input type="date" name="transfer_time" class="form-control">
                            </div>
                            <input type="hidden" name="membership_id" value="{{ $membership->id }}">
                            <div class="d-flex justify-content-sm-start justify-content-lg-end">
                                <button class="btn btn-primary" name="price"
                                    value="{{ $price }}">Bayar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-5 order-first order-md-last">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Informasi Pembayaran</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped-columns">
                                    <tr>
                                        @if ($type > 0)
                                        <td>
                                            Paket {{ ucwords($membership->name) }}
                                        </td>
                                        <td class="text-end">
                                            Rp {{ number_format($membership->price, 2) }}
                                        </td>
                                        @else
                                            <td>Penambahan 100 Catatan Trading</td>
                                            <td class="text-end">Rp 50,000.00</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            3 digit kode unik
                                        </td>
                                        <td class="text-end">
                                            Rp {{ number_format(str_pad($rand, 3, '0', STR_PAD_LEFT), 2)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Pembayaran</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($price, 2) }}</strong></td>
                                    </tr>
                                </table>
                                <div id="payment" style="display:none">
                                    <h6>Pembayaran dilakukan ke</h6>
                                    <h4 id="nomor" class="mb-1"></h4>
                                    <h6 class="text-muted mb-3" id="rekening"></h6>
                                    <h6>Atas Nama</h6>
                                    <h4 id="an"></h4>
                                </div>
                            </div>
                            {{-- <h6 class="text-muted">Rp</h6>
                            <h1 class="mb-3">{{ number_format($price, 2) }}</h1>
                            <h6 class="text-muted">atas nama</h6>
                            <h5>Jem Angkasa Wijaya</h5>
                            <h6 class="text-muted mt-3">untuk pembelian @if ($type > 0) paket @endif</h6>
                            @if ($type > 0)
                            <h5>{{ucwords($membership->name)}} (Rp {{ number_format($membership->price, 2)}})</h5>
                            @else
                            <h5>Penambahan 100 Catatan Trading (Rp 50,000.00)</h5>
                            @endif
                            <p class="text-muted"><small>dengan 3 digit kode unik {{str_pad($rand, 3, '0', STR_PAD_LEFT)}}</small></p> --}}
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h5 class="card-title">Bank Transfer</h5>
                            </div>
                            <small class="text-muted">Transfer manual melalui Bank Transfer</small>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / BCA</p>
                                <img src="{{ asset('images/payments/bca.png') }}" alt="bca"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / BNI</p>
                                <img src="{{ asset('images/payments/bni.png') }}" alt="bni"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / BRI</p>
                                <img src="{{ asset('images/payments/bri.png') }}" alt="bri"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / Mandiri</p>
                                <img src="{{ asset('images/payments/mandiri.png') }}" alt="mandiri"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / Jago</p>
                                <img src="{{ asset('images/payments/jago.png') }}" alt="jago"
                                    style="height: 15px">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h5 class="card-title">E-Wallet</h5>
                            </div>
                            <small class="text-muted">Transfer manual melalui E-Wallet</small>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / GoPay</p>
                                <img src="{{ asset('images/payments/gopay.png') }}" alt="gopay"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / Dana</p>
                                <img src="{{ asset('images/payments/dana.webp') }}" alt="dana"
                                    style="height: 15px">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p>xxxxxxxxxx / OVO</p>
                                <img src="{{ asset('images/payments/ovo.png') }}" alt="ovo"
                                    style="height: 15px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</x-app-layout>
