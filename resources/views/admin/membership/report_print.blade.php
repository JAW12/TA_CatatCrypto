@section('report-title')
    Laporan Pendapatan
@endsection
@push('scripts')
    <script>
        $(function() {
            var beforePrint = function() {
            };


            var afterPrint = function() {
            };

            if (window.matchMedia) {
                var mediaQueryList = window.matchMedia('print');
                mediaQueryList.addListener(function(mql) {
                    if (mql.matches) {
                        beforePrint();
                    } else {
                        afterPrint();
                    }
                });
            }

            window.onbeforeprint = beforePrint;
            window.onafterprint = afterPrint;

            setTimeout(function() {
                window.print();
            }, 1000);

        });
    </script>
@endpush
@push('styles')
    <style>
        @media print {
            .table-responsive {
                overflow: visible;
            }

            table {
                overflow: visible;
            }

            #transactions-list-table th, #transactions-list-table td{
                padding: 8px !important;
            }

            #transactions-list-table{
                font-size: 0.5em;
            }

            html,
            body {
                height: 99%;
            }
        }
    </style>
@endpush
<x-print-layout>
    <div class="my-3 d-flex justify-content-end text-dark">
        <small>Tanggal: @if($data['start'] != null and $data['end'] != null)
            {{ date('d F Y', strtotime($data['start'])) }} - {{ date('d F Y', strtotime($data['end'])) }}
            @elseif($data['start'] != null)
            {{ date('d F Y', strtotime($data['start'])) }} - saat ini
            @elseif($data['end'] != null)
            awal - {{ date('d F Y', strtotime($data['end'])) }}
            @else

            @endif</small>
    </div>
    <div class="mb-4">
        <div class="h5 text-muted mb-2"><strong>Ringkasan Membership</strong></div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Paket Basic:</strong></td>
                        <td><strong>{{number_format($data['basic']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['basic']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Paket Home:</strong></td>
                        <td><strong>{{number_format($data['home']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['home']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Paket Professional:</strong></td>
                        <td><strong>{{number_format($data['professional']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['professional']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Paket Business:</strong></td>
                        <td><strong>{{number_format($data['business']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['business']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Tambahan Catatan:</strong></td>
                        <td><strong>{{number_format($data['additional']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['additional']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%"></div>
        </div>
    </div>
    <div class="mb-4">
        <div class="h5 text-muted mb-2"><strong>Ringkasan Metode Pembayaran</strong></div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Transfer Bank Manual:</strong></td>
                        <td><strong>{{number_format($data['manual_bank']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['manual_bank']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Transfer E-Wallet Manual:</strong></td>
                        <td><strong>{{number_format($data['manual_ewallet']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['manual_ewallet']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Credit Card (Midtrans):</strong></td>
                        <td><strong>{{number_format($data['credit_card']['count'], 0)}} <span class="text-muted small ms-1">(Rp {{number_format($data['credit_card']['income'], 0)}})</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%"></div>
        </div>
    </div>
    <div class="mb-4">
        <div class="h5 text-muted mb-2"><strong>Daftar Transaksi</strong></div>
        <hr>
        <div class="table-responsive mb-3">
            <table id="transactions-list-table" class="table table-striped table-hover"
                role="grid" data-toggle="data-table">
                <thead>
                    <tr class="light">
                        <th>#</th>
                        <th>Kode Order</th>
                        <th>Nama Pengguna</th>
                        <th>Pembayaran Untuk</th>
                        <th>Jumlah Pembayaran</th>
                        <th>Jenis Pembayaran</th>
                        <th>Waktu Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->id_order }}</td>
                            <td>{{ $transaction->user->full_name }}</td>
                            @if ($transaction->membership_id != null)
                                <td>Paket {{ ucwords($transaction->membership->name) }}</td>
                            @else
                                <td>Penambahan 100 Catatan Trading</td>
                            @endif
                            <td>Rp {{ number_format($transaction->gross_amount, 2) }}</td>
                            @if ($transaction->payment_type == 'credit_card')
                                <td>Credit Card (Midtrans)</td>
                            @else
                                <td>{{ $transaction->payment_type }}</td>
                            @endif
                            <td>{{ date_format(date_create($transaction->payment_time), 'd F Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="7">Data tidak tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 50%">
                <table class="text-dark">
                    <tr>
                        <td><strong>Jumlah Transaksi:</strong></td>
                        <td><strong>{{number_format($data['total']['count'], 0)}}</span></strong></td>
                    </tr>
                </table>
            </div>
            <div style="width: 50%">
                <table class="text-dark ms-auto">
                    <tr>
                        <td><strong>Total Pendapatan:</strong></td>
                        <td><strong>Rp {{number_format($data['total']['income'], 0)}}</span></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</x-print-layout>
