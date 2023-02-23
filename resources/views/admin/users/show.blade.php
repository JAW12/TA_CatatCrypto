@section('title', 'Detail Pengguna')
@push('scripts')
    <script>
        $(function() {
            $('.btn-ban').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan melakukan banned untuk pengguna ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, banned!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = $(this).attr('href');
                    }
                })
            });

            $('.btn-restore').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan mengembalikan pengguna ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, kembalikan!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = $(this).attr('href');
                    }
                })
            });
        });
    </script>
@endpush
<x-app-layout :options="['loading', 'datatable']">
    <x-back-button>{{ route('admin.users') }}</x-back-button>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="header-title col-sm-12 col-md-9">
                            <h4 class="card-title">Detail Pengguna {{ $user->full_name }}
                                @if ($user->deleted_at == null)
                                    <span class="badge rounded-pill bg-primary">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Banned</span>
                                @endif
                            </h4>
                        </div>
                        <div class="col-sm-12 col-md-3 mt-3 mt-md-0">
                            @if ($user->deleted_at == null)
                                <a href="{{ route('admin.user.ban', ['id_user' => $user->id]) }}" type="button"
                                    class="btn btn-danger btn-ban w-100">
                                    Ban Pengguna Ini
                                </a>
                            @else
                                <a href="{{ route('admin.user.restore', ['id_user' => $user->id]) }}" type="button"
                                    class="btn btn-success btn-restore w-100">
                                    Kembalikan Pengguna Ini
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-md-flex justify-content-md-between">
                        <div class="flex-fill ">
                            <h5>Tentang</h5>
                            <div class="mt-2" id="txtNama">
                                <h6 class="mb-1">Nama:</h6>
                                <p>{{ $user->first_name . ' ' . $user->last_name }}</p>
                            </div>
                            <div class="mt-2">
                                <h6 class="mb-1">Mulai bergabung:</h6>
                                <p>{{ $user->created_at->formatLocalized('%d %B %Y') }}</p>
                            </div>
                            <div class="mt-2" id="txtJK">
                                <h6 class="mb-1">Jenis Kelamin:</h6>
                                <p>
                                    @if ($user->gender == 'm')
                                        Pria
                                    @elseif($user->gender == 'f')
                                        Wanita
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="mt-2" id="txtEmail">
                                <h6 class="mb-1">Alamat Email:</h6>
                                <p>{{ $user->email }}
                                    @if ($user->email_verified_at != '')
                                        <span class="badge rounded-pill bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge rounded-pill bg-primary">Belum Verifikasi</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mt-2" id="txtTglLahir">
                                <h6 class="mb-1">Tanggal Lahir:</h6>
                                <p>{{ $user->birthdate == null ? '-' : date('d F Y', strtotime($user->birthdate)) }}
                                </p>
                            </div>
                            <div class="mt-2" id="txtTelp">
                                <h6 class="mb-1">No. Telp:</h6>
                                <p>{{ $user->phone_number }}</p>
                            </div>
                        </div>
                        <hr class="hr-vertial">
                        <div class="flex-fill ">
                            <h5>Detail Membership</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Jenis Membership</th>
                                            <td><span
                                                    class="text-capitalize mt-1">{{ ucwords(count($user->membership) == 0 ? ($user->user_type == 'user' ? '-' : $user->user_type) : $user->membership->first()->name) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Aktif dari</th>
                                            <td>{{ $user->membership_since == null ? '-' : Carbon\Carbon::parse($user->membership_since)->formatLocalized('%d %B %Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Aktif sampai</th>
                                            <td>{{ $user->membership_till == null ? '-' : Carbon\Carbon::parse($user->membership_till)->formatLocalized('%d %B %Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Dompet tersedia</th>
                                            @if ($user->max_wallets >= 0)
                                                <td>{{ $user->wallets->count() }} dari {{ $user->max_wallets }}, sisa
                                                    @if ($user->max_wallets - $user->wallets->count() >= 0)
                                                        {{ $user->max_wallets - $user->wallets->count() }} Dompet
                                                    @else
                                                        0 Dompet
                                                    @endif
                                                </td>
                                            @else
                                                <td>∞ Dompet</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Jurnal tersedia</th>
                                            @if ($user->max_journals >= 0)
                                                <td>{{ $user->journals->count() }} dari {{ $user->max_journals }},
                                                    sisa
                                                    @if ($user->max_journals - $user->journals->count() >= 0)
                                                        {{ $user->max_journals - $user->journals->count() }} Jurnal
                                                    @else
                                                        0 Jurnal
                                                    @endif
                                                </td>
                                            @else
                                                <td>∞ Jurnal</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Catatan per bulan</th>
                                            @if ($user->trades_quantity_per_month >= 0)
                                                <td>{{ $user->trades_quantity_per_month }} Catatan</td>
                                            @else
                                                <td>∞ Catatan</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Catatan tersedia</th>
                                            @if ($user->remaining_trades >= 0)
                                                <td>
                                                    {{ $user->remaining_trades }} Catatan
                                                </td>
                                            @else
                                                <td>∞ Catatan</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div>
                        <h5>Riwayat Transaksi</h5>
                        <div class="table-responsive mb-3">
                            <table id="transactions-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Kode Order</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Waktu Pemesanan</th>
                                        <th>Batas Pembayaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->transactions as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->id_order }}</td>
                                            @if ($value->membership_id == null)
                                                <td>Penambahan 100 Catatan Trading</td>
                                            @else
                                                <td>{{ ucwords($value->membership->name) }}</td>
                                            @endif
                                            <td>Rp {{ number_format($value->gross_amount, 2) }}</td>
                                            @if ($value->payment_type == 'credit_card')
                                                <td>Credit Card (Midtrans)</td>
                                            @else
                                                <td>{{ $value->payment_type }}</td>
                                            @endif
                                            <td>{{ date_format(date_create($value->created_at), 'd F Y H:i:s') }}</td>
                                            <td>@if($value->payment_type != "credit_card"){{ date('d F Y H:i:s', strtotime($value->created_at . ' +1 day')) }}@else - @endif
                                            </td>
                                            @if ($value->status == 'settlement' || $value->status == 'capture')
                                                <td><span class="badge bg-success">Berhasil</span></td>
                                            @elseif($value->status == 'pending')
                                                <td><span class="badge bg-secondary">Pending</span></td>
                                            @elseif($value->status == 'cancel')
                                                <td><span class="badge bg-danger">Batal</span></td>
                                            @else
                                                <td><span class="badge bg-danger">Gagal</span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-md-end">Total Pembelian: Rp
                            {{ number_format($user->spent, 0) }} </div>
                    </div>
                    <hr>
                    <div>
                        <h5>Riwayat Membership</h5>
                        <div class="table-responsive">
                            <table id="memberships-list-table" class="table table-striped table-hover" role="grid"
                                data-toggle="data-table">
                                <thead>
                                    <tr class="light">
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Berlaku Sejak</th>
                                        <th>Berlaku Sampai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->memberships as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords($value->name) }}</td>
                                            <td>Rp {{ number_format($value->price, 2) }}</td>
                                            <td>{{ date_format($value->pivot->created_at, 'd F Y') }}</td>
                                            <td>{{ date_format(date_create($value->pivot->membership_expiration), 'd F Y') }}
                                            </td>
                                            @if ($value->pivot->status == 1)
                                                <td><span class="badge bg-success">Aktif</span></td>
                                            @elseif ($value->pivot->status == 0)
                                                <td><span class="badge bg-secondary">Nonaktif</span></td>
                                            @elseif ($value->pivot->status == -1)
                                                <td><span class="badge bg-danger">Berakhir</span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
