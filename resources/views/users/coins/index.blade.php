@section('title', 'Daftar Koin')
@push('scripts')
    <script>
        $(function() {
            var table = $("#coins-list-table").DataTable({
                "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
                    "destroy": true,
                },
                "lengthChange": false,
                pageLength: 10,
                ordering: false,
                columns: [{
                        data: 'users',
                        render: function(data, type, row) {
                            if (data.length > 0) {
                                let route = `<?php echo route('user.coins.unfavorite', ['id' => ':id']); ?>`;
                                route = route.replace(':id', row.id);
                                return `<a href="${route}" class="yellowstar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
</a>`;
                            } else {
                                let route = `<?php echo route('user.coins.favorite', ['id' => ':id']); ?>`;
                                route = route.replace(':id', row.id);
                                return `<a href="${route}" class="star">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                                    </a>`;
                            }
                        }
                    },
                    {
                        data: 'market_cap_rank',
                        title: '#'
                    },
                    {
                        data: 'name',
                        title: 'Nama',
                        render: function(data, type, row) {
                            return `<img src="${row.thumb}" alt="${data}"
                                                        class="img-thumbnail">
                                                    <span class="ms-2 me-2">${data}</span>
                                                    <span class="text-secondary"><small>${row.symbol}</small></span>`
                        }
                    },
                    {
                        data: 'current_price',
                        title: 'Harga',
                        render: function(data, type, row) {
                            const price = data;
                            const numberFormatter = Intl.NumberFormat('en-US');
                            const formatted = numberFormatter.format(price);
                            return `$${formatted}`
                        }
                    },
                    {
                        data: 'price_change_percentage_1h',
                        title: '1h %',
                        render: function(data, type, row) {
                            const percentage = data;
                            const numberFormatter = Intl.NumberFormat('en-US');
                            const formatted = numberFormatter.format(percentage);
                            if (percentage > 0) {
                                return `<span class="text-success">${formatted}%</span>`;
                            } else if (percentage < 0) {
                                return `<span class="text-danger">${formatted}%</span>`;
                            } else {
                                return `${formatted}%`;
                            }
                        }
                    },
                    {
                        data: 'price_change_percentage_24h',
                        title: '24h %',
                        render: function(data, type, row) {
                            const percentage = data;
                            const numberFormatter = Intl.NumberFormat('en-US');
                            const formatted = numberFormatter.format(percentage);
                            if (percentage > 0) {
                                return `<span class="text-success">${formatted}%</span>`;
                            } else if (percentage < 0) {
                                return `<span class="text-danger">${formatted}%</span>`;
                            } else {
                                return `${formatted}%`;
                            }
                        }
                    },
                    {
                        data: 'price_change_percentage_7d',
                        title: '7d %',
                        render: function(data, type, row) {
                            const percentage = data;
                            const numberFormatter = Intl.NumberFormat('en-US');
                            const formatted = numberFormatter.format(percentage);
                            if (percentage > 0) {
                                return `<span class="text-success">${formatted}%</span>`;
                            } else if (percentage < 0) {
                                return `<span class="text-danger">${formatted}%</span>`;
                            } else {
                                return `${formatted}%`;
                            }
                        }
                    },
                    {
                        data: 'market_cap',
                        title: 'Kapitulasi Pasar',
                        render: function(data, type, row) {
                            const price = data;
                            const numberFormatter = Intl.NumberFormat('en-US');
                            const formatted = numberFormatter.format(price);
                            return `$${formatted}`
                        }
                    },
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('coins.list') }}',
                    dataType: 'json',
                    data: function(d) {
                        d.page = d.start / d.length + 1;
                        d.user_id = '{{ Auth::id() }}';
                        d.watchlist = '{{ $watchlist }}';
                    },
                },
            });

            $('#coins-list-table').on('click', 'tbody tr', function(evt) {
                if ( $(evt.target).is("path") ) {
                    return;
                }

                // console.log('API row values : ', table.row(this).data());

                let route = `<?php echo route('user.coins.info', ['id' => ':id']); ?>`;
                route = route.replace(':id', table.row(this).data().id);
                window.location = route;
            })
        });
    </script>
@endpush
@push('styles')
<style>
    .star{
        color: grey;
    }
    .star:hover{
        filter: brightness(50%);
    }
    .yellowstar{
        color: #FFDF00;
    }
    .yellowstar:hover{
        color: #FDCC0D;
    }
</style>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header-title">
                                <h4 class="card-title">Daftar Koin @if($watchlist == 1) Watchlist @endif</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="coins-list-table" class="table table-striped table-hover" role="grid"
                                    data-toggle="data-table">
                                    <thead>
                                        <tr class="light">
                                            <th></th>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>1h %</th>
                                            <th>24h %</th>
                                            <th>7d %</th>
                                            <th>Kapitulasi Pasar</th>
                                        </tr>
                                    </thead>
                                    <tbody style="cursor: pointer; ">
                                        {{-- @foreach ($coins as $coin)
                                            <tr>
                                                @if ($coin->market_cap_rank != null)
                                                <td data-order="a">{{ $coin->market_cap_rank }}</td>
                                                @else
                                                <td data-order="zzz">{{ $coin->market_cap_rank }}</td>
                                                @endif
                                                <td>
                                                    <img src="{{ $coin->thumb }}" alt="logo_crypto"
                                                        class="img-thumbnail">
                                                    <span class="ms-2 me-2">{{ $coin->name }}</span>
                                                    <span class="text-muted"><small>{{ $coin->symbol }}</small></span>
                                                </td>
                                                <td>${{ number_format($coin->current_price, 0) }}</td>
                                                <td
                                                    class="@if ($coin->price_change_percentage_1h > 0) text-success @elseif($coin->price_change_percentage_1h < 0) text-danger @endif">
                                                    {{ number_format($coin->price_change_percentage_1h, 2) }}%</td>
                                                <td
                                                    class="@if ($coin->price_change_percentage_24h > 0) text-success @elseif($coin->price_change_percentage_24h < 0) text-danger @endif">
                                                    {{ number_format($coin->price_change_percentage_24h, 2) }}%</td>
                                                <td
                                                    class="@if ($coin->price_change_percentage_7d > 0) text-success @elseif($coin->price_change_percentage_7d < 0) text-danger @endif">
                                                    {{ number_format($coin->price_change_percentage_7d, 2) }}%</td>
                                                <td>${{ number_format($coin->market_cap, 0) }}</td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
