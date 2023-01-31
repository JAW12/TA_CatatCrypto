@section('title', 'Riwayat Membership')

<x-app-layout :options="['loading', 'datatable']">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="header-title">
                        <h4 class="card-title">Riwayat Membership</h4>
                    </div>
                </div>
                <div class="card-body">
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
                                @foreach (Auth::user()->memberships as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($value->name) }}</td>
                                        <td>Rp {{ number_format($value->price, 2) }}</td>
                                        <td>{{ date_format($value->pivot->created_at, 'd F Y') }}</td>
                                        <td>{{ date_format(date_create($value->pivot->membership_expiration), 'd F Y') }}
                                        </td>
                                        @if ($value->pivot->status == 1)
                                            <td><span class="badge bg-success">Aktif</span></td>
                                        @else
                                            <td><span class="badge bg-secondary">Nonaktif</span></td>
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
