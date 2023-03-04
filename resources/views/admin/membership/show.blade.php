@section('title', 'Detail Transaksi Pengguna')
@push('scripts')
    <script>
        $(function() {
            $('.btn-accept').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan mengkonfirmasi transaksi ini?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, konfirmasi!',
                    cancelButtonText: 'Tidak',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = $(this).attr('href');
                    }
                })
            });

            $('.btn-deny').click(function(e) {
                e.preventDefault() // Don't post the form, unless confirmed
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Apakah anda yakin akan menolak transaksi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, konfirmasi!',
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
<x-app-layout :options="['loading']">
    <x-back-button>{{ str_contains(url()->previous(), "pending") ? route('admin.transactions.pending') : route('admin.transactions') }}</x-back-button>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Detail Transaksi
                                <small class="text-muted h6 me-2">#{{$transaction->id_order}}</small>
                                @if ($transaction->status == "pending")
                                    <span class="badge bg-secondary rounded-pill">Pending</span>
                                @elseif($transaction->status == "capture" or $transaction->status =="settlement")
                                    <span class="badge bg-success rounded-pill">Berhasil</span>
                                @elseif($transaction->status == "deny")
                                    <span class="badge bg-danger rounded-pill ms-2">Ditolak</span>
                                @elseif($transaction->status == "cancel")
                                    <span class="badge bg-danger rounded-pill ms-2">Batal</span>
                                @else
                                    <span class="badge bg-danger rounded-pill ms-2">Gagal</span>
                                @endif
                            </h4>
                            <small class="text-muted">{{ date_format(date_create($transaction->payment_time), 'd F Y H:i:s') }}</small>
                        </div>
                        <div>
                            <h3>Rp {{ number_format($transaction->gross_amount, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <h5>Informasi Pribadi</h5>
                        <hr>
                        <p>Nama Lengkap: {{$transaction->name}} </p>
                        <p>No. Telp: {{$transaction->phone_number}} </p>
                        <p>Alamat Email: {{$transaction->email}} </p>
                    </div>
                    <div class="mb-2">
                        <h5>Informasi Pembayaran</h5>
                        <hr>
                        <p>Paket yang dibeli: @if($transaction->membership_id == 0) Penambahan 100 Catatan Trading @else {{ucwords($transaction->membership->name)}} @endif</p>
                        @if($transaction->payment_type == "automatic" || $transaction->payment_type == "credit_card")
                        <p>Metode Pembayaran: Credit Card (Midtrans)</p>
                        @else
                        <p>Metode Pembayaran: {{$transaction->payment_type}}</p>
                        <p>Jenis Bank Pengguna: {{$transaction->bank_name}}</p>
                        <p>Atas Nama: {{$transaction->payment_name}}</p>
                        <p>Pembayaran akan dilakukan pada: {{ date_format(date_create($transaction->payment_time), 'd F Y H:i:s') }}</p>
                        <p>Batas pembayaran: <span class="text-danger">@if($transaction->payment_type != "credit_card"){{ date('d F Y H:i:s', strtotime($transaction->created_at . ' +1 day')) }}@else - @endif</span></p>
                        @endif
                        <p>Biaya yang dibayarkan: Rp {{ number_format($transaction->gross_amount, 2) }}</p>

                        @if($transaction->payment_type != "automatic" && $transaction->payment_type != "credit_card" && $transaction->status == "pending")
                        <div class="d-md-flex justify-content-md-between">
                            <div class="mb-2 mb-md-0">
                                <a href="{{route('admin.transaction.deny', ['id_order' => $transaction->id_order])}}" class="btn btn-block btn-danger w-100 btn-deny">
                                    Tidak Menerima Pembayaran?
                                </a>
                            </div>
                            <div>
                                <a href="{{route('admin.transaction.accept', ['id_order' => $transaction->id_order])}}" class="btn btn-block btn-success w-100 btn-accept">
                                    Konfirmasi Pembayaran
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
