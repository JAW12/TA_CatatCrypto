@push('scripts')
    <script>
        $("#payment_type").change(function (e) {
            e.preventDefault();
            let value = $("#payment_type").val();
            if(value != 'automatic'){
                $("#bank_type").fadeIn();
                $("#bank_name").fadeIn();
                $("#transfer_time").fadeIn();
            }
            else{
                $("#bank_type").fadeOut();
                $("#bank_name").fadeOut();
                $("#transfer_time").fadeOut();
            }
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    <x-back-button>{{ route('user.membership') }}</x-back-button>
    <form action="{{route('user.membership.checkout')}}" method="post" class="form-row">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-lg-8 order-last order-md-first">
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
                            <input type="text" name="name" id="name" class="form-control" value="{{Auth::user()->first_name}} {{Auth::user()->last_name}}">
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="form-label">No. Telp</label>
                            <input type="tel" name="phone_number" id="phone_number" class="form-control" value="{{Auth::user()->phone_number}}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{Auth::user()->email}}">
                        </div>
                        <h6>Informasi Pembayaran</h6>
                        <hr>
                        <div class="form-group">
                            <label for="payment_type" class="form-label">Metode Pembayaran</label>
                            <select name="payment_type" id="payment_type" class="form-control">
                                <option disabled selected>Pilih salah satu metode pembayaran</option>
                                <optgroup label="Bank Transfer Manual">
                                    <option value="Manual Transfer Bank BCA">BCA</option>
                                    <option value="Manual Transfer Bank BNI">BNI</option>
                                    <option value="Manual Transfer Bank BRI">BRI</option>
                                    <option value="Manual Transfer Bank Mandiri">Mandiri</option>
                                    <option value="Manual Transfer Bank Jago">Jago</option>
                                </optgroup>
                                <optgroup label="E-Wallet Transfer Manual">
                                    <option value="Manual Transfer E-Wallet GoPay">GoPay</option>
                                    <option value="Manual Transfer E-Wallet Dana">Dana</option>
                                    <option value="Manual Transfer E-Wallet OVO">OVO</option>
                                </optgroup>
                                <optgroup label="Pembayaran Otomatis">
                                    <option value="automatic">Midtrans</option>
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
                        <input type="hidden" name="membership_id" value="{{$membership->id}}">
                        <div class="d-flex justify-content-sm-start justify-content-lg-end">
                            <button class="btn btn-primary" name="price" value="{{$price}}">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 order-first order-md-last">
                <div class="card">
                    <div class="card-header">
                        <div class="header-title">
                            <h4 class="card-title">Informasi Pembayaran</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted">Rp</h6>
                        <h1 class="mb-3">{{ number_format($price, 2) }}</h1>
                        <h6 class="text-muted">atas nama</h6>
                        <h5>Jem Angkasa Wijaya</h5>
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
                            <img src="{{asset('images/payments/bca.png')}}" alt="bca" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / BNI</p>
                            <img src="{{asset('images/payments/bni.png')}}" alt="bni" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / BRI</p>
                            <img src="{{asset('images/payments/bri.png')}}" alt="bri" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / Mandiri</p>
                            <img src="{{asset('images/payments/mandiri.png')}}" alt="mandiri" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / Jago</p>
                            <img src="{{asset('images/payments/jago.png')}}" alt="jago" style="height: 15px">
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
                            <img src="{{asset('images/payments/gopay.png')}}" alt="gopay" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / Dana</p>
                            <img src="{{asset('images/payments/dana.webp')}}" alt="dana" style="height: 15px">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p>xxxxxxxxxx / OVO</p>
                            <img src="{{asset('images/payments/ovo.png')}}" alt="ovo" style="height: 15px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
