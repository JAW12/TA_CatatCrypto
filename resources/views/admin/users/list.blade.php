@section('title', 'Daftar Pengguna')
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Daftar Pengguna</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="users-list-table" class="table table-striped table-hover" role="grid"
                            data-toggle="data-table">
                            <thead>
                                <tr class="light">
                                    <th>#</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email Terverifikasi</th>
                                    <th>Mendaftar Sejak</th>
                                    <th>Membership yang Aktif</th>
                                    <th>Membership aktif sampai</th>
                                    <th>Jumlah Dompet</th>
                                    <th>Jumlah Jurnal</th>
                                    <th>Jumlah Catatan</th>
                                    <th>Jumlah Pembelian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>
                                            @if ($user->email_verified_at == null)
                                                <span class="badge rounded-pill bg-secondary">Belum Terverifikasi</span>
                                            @else
                                                <span class="badge rounded-pill bg-success">Sudah Terverifikasi</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->formatLocalized('%d %B %Y') }}</td>
                                        <td>{{ ucwords(count($user->membership) == 0 ? ($user->user_type == 'user' ? '-' : $user->user_type) : $user->membership->first()->name) }}
                                        </td>
                                        <td>{{ $user->membership_till == null ? '-' : Carbon\Carbon::parse($user->membership_till)->formatLocalized('%d %B %Y') }}
                                        </td>
                                        <td>{{ $user->wallets->count() }} Dompet</td>
                                        <td>{{ $user->journals->count() }} Jurnal</td>
                                        <td>{{ $user->journals->sum('count_of_trades') }} Jurnal</td>
                                        <td>Rp {{ number_format($user->spent, 0) }}</td>
                                        <td>
                                            @if ($user->deleted_at == null)
                                                <span class="badge rounded-pill bg-primary">Aktif</span>
                                            @else
                                                <span class="badge rounded-pill bg-danger">Banned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user.detail', ['id_user' => $user->id]) }}"
                                                type="button" class="btn btn-sm btn-icon btn-primary">
                                                <span class="btn-inner">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-eye-fill"
                                                        viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                    </svg>
                                                </span>
                                            </a>
                                            @if ($user->deleted_at == null)
                                                <a href="{{ route('admin.user.ban', ['id_user' => $user->id]) }}" type="button" class="btn btn-sm btn-icon btn-danger btn-ban">
                                                    <span class="btn-inner">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-x"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.user.restore', ['id_user' => $user->id]) }}"
                                                    type="button" class="btn btn-sm btn-icon btn-success btn-restore">
                                                    <span class="btn-inner">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-check"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
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
