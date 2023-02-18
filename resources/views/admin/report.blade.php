@section('title', 'Laporan Web')
@push('scripts')
    <script>
        $(function() {
            var genderDistribution = {!! json_encode($data['gender_distribution']) !!};
            var optionsGender = {
                chart: {
                    type: 'donut',
                    width: '100%',
                    height: 400
                },
                series: [
                    genderDistribution['Pria'],
                    genderDistribution['Wanita'],
                    genderDistribution['Tidak Diketahui']
                ],
                labels: ['Pria', 'Wanita', 'Tidak Diketahui'],
                colors: ['#008FFB', '#FF4560', '#000000'],
                legend: {
                    position: 'bottom'
                }
            }

            var genderChart = new ApexCharts(document.querySelector("#gender-chart"), optionsGender);
            genderChart.render();

            var ageDistribution = {!! json_encode($data['age_distribution']) !!};

            var ageChartOptions = {
                chart: {
                    type: 'donut',
                    width: '100%',
                    height: 400,
                },
                series: [
                    ageDistribution['<20'],
                    ageDistribution['20-29'],
                    ageDistribution['30-39'],
                    ageDistribution['40-49'],
                    ageDistribution['>50'],
                ],
                labels: ['<20', '20-29', '30-39', '40-49', '>50'],
                colors: ['#3C91E6', '#8B4F9A', '#FEB019', '#B1B424', '#1F8A70'],
                legend: {
                    position: 'bottom',
                }
            };

            var ageChart = new ApexCharts(document.querySelector("#age-chart"), ageChartOptions);
            ageChart.render();

            var usersChartOption = {
                chart: {
                    type: 'area',
                    height: 400,
                },
                series: [{
                    name: 'Jumlah pengguna baru',
                    data: {!! $data['newUsersPerDay']->pluck('count') !!}
                }, {
                    name: 'Kumulatif pengguna',
                    data: {!! $data['newUsersPerDay']->pluck('cumulative_count') !!}
                }],
                xaxis: {
                    type: 'datetime',
                    categories: {!! $data['newUsersPerDay']->pluck('date') !!}
                },
            };

            var usersChart = new ApexCharts(document.querySelector('#users-chart'), usersChartOption);
            usersChart.render();

            var walletDistribution = {!! json_encode($data['wallet_distribution']) !!};
            var optionsWallet = {
                chart: {
                    type: 'donut',
                    width: '100%',
                    height: 400
                },
                series: [
                    walletDistribution['Binance'],
                    walletDistribution['Manual'],
                ],
                labels: ['Binance', 'Manual'],
                colors: ['#F3BA2F', '#000000'],
                legend: {
                    position: 'bottom'
                }
            }

            var walletChart = new ApexCharts(document.querySelector("#wallet-chart"), optionsWallet);
            walletChart.render();

            var transactionsChartOptions = {
                chart: {
                    type: 'bar',
                    height: 400,
                },
                series: [{
                    name: 'Jumlah Transaksi',
                    data: {!! $data['transactions']->pluck('total_transactions') !!}
                }, {
                    name: 'Total Penjualan',
                    data: {!! $data['transactions']->pluck('total_sales') !!}
                }],
                xaxis: {
                    categories: {!! $data['transactions']->pluck('name') !!}
                },
                yaxis: [{
                    axisTicks: {
                        show: true,
                    },
                    title: {
                        text: 'Jumlah Transaksi',
                    },
                    min: 0,
                    forceNiceScale: true,
                }, {
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    title: {
                        text: 'Total Penjualan',
                    },
                    min: 0,
                    forceNiceScale: true,
                }],
                colors: ['#2196F3', '#FF9800']
            };

            var transactionsChart = new ApexCharts(document.querySelector('#transactions-chart'),
                transactionsChartOptions);
            transactionsChart.render();


            var categories = @json($data['categories']);

            var categoryOptions = {
                chart: {
                    type: 'bar',
                    height: 400,
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: categories.map((category) => category.strategies_count),
                }],
                xaxis: {
                    categories: categories.map((category) => category.name),
                },
            }

            var categoryChart = new ApexCharts(document.querySelector("#category-chart"), categoryOptions);
            categoryChart.render();

            var mostFavoritedChartOptions = {
                chart: {
                    type: 'bar',
                    height: 400,
                },
                series: [{
                    name: 'Jumlah Disukai',
                    data: {!! $data['mostFavoritedStrategies']->pluck('favorite_count') !!}
                }],
                xaxis: {
                    categories: {!! $data['mostFavoritedStrategies']->pluck('name') !!},
                    labels:{
                        rotate: 0,
                        hideOverlappingLabels: false,
                        trim: true,
                    }
                },
                yaxis: [{
                    axisTicks: {
                        show: true,
                    },
                }],
                colors: ['#2196F3']
            };

            var mostFavoritedChart = new ApexCharts(document.querySelector('#most-favorited-chart'),
                mostFavoritedChartOptions);
            mostFavoritedChart.render();

            var mostUsedChartOptions = {
                chart: {
                    type: 'bar',
                    height: 400,
                },
                series: [{
                    name: 'Jumlah Dipakai',
                    data: {!! $data['mostUsedStrategies']->pluck('trade_count') !!}
                }],
                xaxis: {
                    categories: {!! $data['mostUsedStrategies']->pluck('name') !!},
                    labels:{
                        rotate: 0,
                        hideOverlappingLabels: false,
                        trim: true,
                    }
                },
                yaxis: [{
                    axisTicks: {
                        show: true,
                    },
                }],
                colors: ['#FF9800']
            };

            var mostUsedChart = new ApexCharts(document.querySelector('#most-used-chart'), mostUsedChartOptions);
            mostUsedChart.render();
        });
    </script>
@endpush
<x-app-layout :options="['loading']">
    @if (Auth::user()->email_verified_at == null)
        <x-verify-button></x-verify-button>
    @else
        <div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" id="target-element">
                        <div class="card-header">
                            <div class="row">
                                <div class="header-title col-sm-12 col-md-9">
                                    <h4 class="card-title">Laporan Web</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('admin.report.print')}}" target="_blank" class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                Statistik Pengguna
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>Demografis Jenis Kelamin</strong></p>
                                        <div id="gender-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-muted mb-2 small"><strong>Daftar Distribusi Jenis
                                                Kelamin</strong></p>
                                        <div class="table-responsive">
                                            <table id="gender-list-table" class="table table-striped table-hover"
                                                role="grid" data-toggle="data-table">
                                                <thead>
                                                    <tr class="light">
                                                        <th>Jenis Kelamin</th>
                                                        <th>Jumlah Pengguna</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data['gender_distribution'] as $gender => $count)
                                                        <tr>
                                                            <td>{{ $gender }}</td>
                                                            <td>{{ number_format($count, 0) }} Pengguna</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2">Data tidak tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>Demografis Umur</strong></p>
                                        <div id="age-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-muted mb-2 small"><strong>Daftar Distribusi Umur</strong></p>
                                        <div class="table-responsive">
                                            <table id="age-list-table" class="table table-striped table-hover"
                                                role="grid" data-toggle="data-table">
                                                <thead>
                                                    <tr class="light">
                                                        <th>Rentang Umur</th>
                                                        <th>Jumlah Pengguna</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data['age_distribution'] as $range => $count)
                                                        <tr>
                                                            <td>{{ $range }}</td>
                                                            <td>{{ number_format($count, 0) }} Pengguna</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2">Data tidak tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="text-dark mb-2"><strong>Jumlah Pengguna</strong></p>
                                    <div id="users-chart"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                            height="25" fill="currentColor"
                                                            class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                                            <path fill-rule="evenodd"
                                                                d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Pengguna Baru 30 Hari Terakhir
                                                        <h2 class="counter">{{ $data['usersLast30Days'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                            height="25" fill="currentColor"
                                                            class="bi bi-person-fill-check" viewBox="0 0 16 16">
                                                            <path
                                                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            <path
                                                                d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Pengguna Yang Terverifikasi
                                                        <h2 class="counter">{{ $data['verifiedUsers'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                            height="25" fill="currentColor"
                                                            class="bi bi-person-fill-slash" viewBox="0 0 16 16">
                                                            <path
                                                                d="M13.879 10.414a2.501 2.501 0 0 0-3.465 3.465l3.465-3.465Zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465Zm-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Pengguna Yang Terbanned
                                                        <h2 class="counter">
                                                            {{ $data['blockedUsers'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                Statistik Dompet dan Jurnal
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>Demografis Dompet</strong></p>
                                        <div id="wallet-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-muted mb-2 small"><strong>Daftar Distribusi Dompet</strong></p>
                                        <div class="table-responsive">
                                            <table id="wallet-list-table" class="table table-striped table-hover"
                                                role="grid" data-toggle="data-table">
                                                <thead>
                                                    <tr class="light">
                                                        <th>Jenis Dompet</th>
                                                        <th>Jumlah Dompet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data['wallet_distribution'] as $wallet => $count)
                                                        <tr>
                                                            <td>{{ $wallet }}</td>
                                                            <td>{{ number_format($count, 0) }} Dompet</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2">Data tidak tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-wallet"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Dompet
                                                        <h2 class="counter">{{ $data['walletCount'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-journals"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2z" />
                                                            <path
                                                                d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 2.5v.5H.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2v-.5a.5.5 0 0 0-1 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Jurnal
                                                        <h2 class="counter">{{ $data['journalCount'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-file-richtext" viewBox="0 0 16 16">
                                                            <path
                                                                d="M7 4.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm-.861 1.542 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V7.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V7s1.54-1.274 1.639-1.208zM5 9a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z" />
                                                            <path
                                                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Catatan Trading
                                                        <h2 class="counter">
                                                            {{ $data['tradeCount'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                Statistik Transaksi
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>Demografis Transaksi</strong></p>
                                        <div id="transactions-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-muted mb-2 small"><strong>Daftar Distribusi Transaksi</strong>
                                        </p>
                                        <div class="table-responsive">
                                            <table id="transactions-list-table"
                                                class="table table-striped table-hover" role="grid"
                                                data-toggle="data-table">
                                                <thead>
                                                    <tr class="light">
                                                        <th>Jenis Transaksi</th>
                                                        <th>Jumlah Transaksi</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data['transactions'] as $transaction)
                                                        <tr>
                                                            <td>{{ ucwords($transaction->name) }}</td>
                                                            <td>{{ number_format($transaction->total_transactions, 0) }}
                                                                Transaksi</td>
                                                            <td>Rp {{ number_format($transaction->total_sales, 0) }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">Data tidak tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot class="bg-soft-primary text-primary">
                                                    <tr>
                                                        <th>Total</th>
                                                        <th><strong>{{ number_format($data['transactions']->sum('total_transactions'), 0) }}
                                                                Transaksi</strong></th>
                                                        <th><strong>Rp
                                                                {{ number_format($data['transactions']->sum('total_sales'), 0) }}</strong>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                Statistik Pustaka
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>Demografis Pustaka</strong></p>
                                        <div id="category-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-muted mb-2 small"><strong>Daftar Distribusi Pustaka</strong>
                                        </p>
                                        <div class="table-responsive">
                                            <table id="category-list-table" class="table table-striped table-hover"
                                                role="grid" data-toggle="data-table">
                                                <thead>
                                                    <tr class="light">
                                                        <th>Kategori</th>
                                                        <th>Jumlah Pustaka</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($data['categories'] as $category)
                                                        <tr>
                                                            <td>{{ ucwords($category->name) }}</td>
                                                            <td>{{ number_format($category->strategies_count, 0) }}
                                                                Pustaka</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2">Data tidak tersedia</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-file-earmark-person-fill"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm2 5.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-.245S4 12 8 12s5 1.755 5 1.755z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Pustaka Umum
                                                        <h2 class="counter">{{ $data['systemAddedStrategies'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-file-person-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Pustaka Pengguna
                                                        <h2 class="counter">{{ $data['publicAddedStrategies'] }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card bg-soft-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="bg-white text-primary rounded p-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            class="bi bi-file-richtext-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM7 4.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm-.861 1.542 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V7.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V7s1.54-1.274 1.639-1.208zM5 9h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-end">
                                                        Jumlah Pustaka Keseluruhan
                                                        <h2 class="counter">
                                                            {{ $data['categories']->sum('strategies_count') }}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>3 Pustaka Terfavorit</strong></p>
                                        <div id="most-favorited-chart"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-dark mb-2"><strong>3 Pustaka Terpopuler</strong></p>
                                        <div id="most-used-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
