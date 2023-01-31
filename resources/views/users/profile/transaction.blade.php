@section('title', 'Riwayat Transaksi')
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
                                    <th>Waktu Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(Auth::user()->transactions as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->id_order}}</td>
                                        @if($value->membership_id == null)
                                        <td>Penambahan 100 Catatan Trading</td>
                                        @else
                                        <td>{{ucwords($value->membership->name)}}</td>
                                        @endif
                                        <td>Rp {{number_format($value->gross_amount, 2)}}</td>
                                        @if($value->payment_type == "credit_card")
                                            <td>Credit Card (Midtrans)</td>
                                        @else
                                            <td>{{$value->payment_type}}</td>
                                        @endif
                                        <td>{{date_format(date_create($value->payment_time), 'd F Y H:i:s')}}</td>
                                        @if($value->status == 'settlement' || $value->status == 'capture')
                                            <td><span class="badge bg-success">Berhasil</span></td>
                                        @elseif($value->status == 'pending')
                                            <td><span class="badge bg-secondary">Pending</span></td>
                                        @else
                                            <td><span class="badge bg-danger">Gagal</span></td>
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
