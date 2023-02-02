@section('title', 'Riwayat Transaksi')
@push('scripts')
    <script>
        $(function() {
            $('.btn-cancel').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan membatalkan transaksi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, batalkan!',
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Riwayat Transaksi</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()->transactions as $key => $value)
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
                                        <td>{{ date('d F Y H:i:s', strtotime($value->created_at . ' +1 day')) }}</td>
                                        @if ($value->status == 'settlement' || $value->status == 'capture')
                                            <td><span class="badge bg-success">Berhasil</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('user.transaction.detail', ['id' => Auth::id(), 'id_order' => $value->id_order]) }}"
                                                        type="button" class="btn btn-sm btn-icon btn-primary">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                                <path
                                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                        @elseif($value->status == 'pending')
                                            <td><span class="badge bg-secondary">Pending</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('user.transaction.detail', ['id' => Auth::id(), 'id_order' => $value->id_order]) }}"
                                                        type="button" class="btn btn-sm btn-icon btn-primary">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                                <path
                                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <a href="{{ route('user.transaction.cancel', ['id' => Auth::id(),'id_order' => $value->id_order]) }}" type="button" class="btn btn-sm btn-icon btn-danger btn-cancel">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-x"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                        @elseif($value->status == 'cancel')
                                            <td><span class="badge bg-danger">Batal</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('user.transaction.detail', ['id' => Auth::id(), 'id_order' => $value->id_order]) }}"
                                                        type="button" class="btn btn-sm btn-icon btn-primary">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                                <path
                                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td><span class="badge bg-danger">Gagal</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('user.transaction.detail', ['id' => Auth::id(), 'id_order' => $value->id_order]) }}"
                                                        type="button" class="btn btn-sm btn-icon btn-primary">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                                <path
                                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
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
</x-app-layout>
