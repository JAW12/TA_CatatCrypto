<x-app-layout :options="['loading']">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Daftar Transaksi</h4>
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
                                    <th>Nama Pengguna</th>
                                    <th>Paket Membership</th>
                                    <th>Jumlah Pembayaran</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Waktu Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->id_order }}</td>
                                        <td>{{ $transaction->user->full_name }}</td>
                                        @if ($transaction->membership_id != null)
                                            <td>{{ ucwords($transaction->membership->name) }}</td>
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
                                        @if ($transaction->status == 'settlement' || $transaction->status == 'capture')
                                            <td><span class="badge bg-success">Berhasil</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('admin.transaction.detail', ['id_order' => $transaction->id_order]) }}"
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
                                        @elseif($transaction->status == 'pending')
                                            <td><span class="badge bg-secondary">Pending</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('admin.transaction.detail', ['id_order' => $transaction->id_order]) }}"
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
                                                    <a href="{{ route('admin.transaction.accept', ['id_order' => $transaction->id_order]) }}" type="button" class="btn btn-sm btn-icon btn-success">
                                                        <span class="btn-inner">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-check"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <a href="{{ route('admin.transaction.deny', ['id_order' => $transaction->id_order]) }}" type="button" class="btn btn-sm btn-icon btn-danger">
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
                                        @elseif($transaction->status == 'deny')
                                            <td><span class="badge bg-danger">Ditolak</span></td>
                                            <td>
                                                <div class="flex align-items-center list-transaction-action">
                                                    <a href="{{ route('admin.transaction.detail', ['id_order' => $transaction->id_order]) }}"
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
                                                    <a href="{{ route('admin.transaction.detail', ['id_order' => $transaction->id_order]) }}"
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
