@section('title', 'Demografi Pengguna')
@push('scripts')
    <script>
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

        var membershipData = {!! json_encode($data['membership_distribution']) !!};
        var membershipChartOptions = {
            chart: {
                type: 'donut',
                width: '100%',
                height: 400,
            },
            series: membershipData.map(function(membership) {
                return membership.total;
            }),
            labels: membershipData.map(function(membership) {
                return membership.name.charAt(0).toUpperCase() + membership.name.slice(1);
            }),
            legend: {
                position: 'bottom',
            }
        };

        // Membuat chart menggunakan ApexCharts.js
        var membershipChart = new ApexCharts(document.querySelector("#membership-chart"), membershipChartOptions);
        membershipChart.render();

        var walletDistribution = {!! json_encode($data['wallet_distribution']) !!};

        var optionsWalletDistribution = {
            series: [walletDistribution['0'], walletDistribution['1-3'], walletDistribution[
                '4-6'], walletDistribution['>6']],
            chart: {
                type: 'donut',
                height: 400,
                width: '100%',
            },
            labels: ['0', '1-3', '4-6', '>6'],
            legend: {
                position: 'bottom',
            }
        };

        var walletChart = new ApexCharts(document.querySelector("#wallet-chart"), optionsWalletDistribution);
        walletChart.render();

        var journalDistribution = {!! json_encode($data['journal_distribution']) !!};

        var optionsJournalDistribution = {
            series: [journalDistribution['0'], journalDistribution['1-3'], journalDistribution[
                '4-6'], journalDistribution['>6']],
            chart: {
                type: 'donut',
                height: 400,
                width: '100%',
            },
            labels: ['0', '1-3', '4-6', '>6'],
            legend: {
                position: 'bottom',
            }
        };

        var journalChart = new ApexCharts(document.querySelector("#journal-chart"), optionsJournalDistribution);
        journalChart.render();

        var tradeDistribution = {!! json_encode($data['trade_distribution']) !!};

        var optionsTradeDistribution = {
            series: [tradeDistribution['0'], tradeDistribution['1-100'], tradeDistribution[
                '101-500'], tradeDistribution['501-1500'], tradeDistribution['>1500']],
            chart: {
                type: 'donut',
                height: 400,
                width: '100%',
            },
            labels: ['0', '1-100', '101-500', '501-1500', '>1500'],
            legend: {
                position: 'bottom',
            }
        };

        var tradeChart = new ApexCharts(document.querySelector("#trade-chart"), optionsTradeDistribution);
        tradeChart.render();
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
                                    <h4 class="card-title">Demografi Pengguna</h4>
                                </div>
                                <div class="col-sm-12 col-md-3 justify-content-md-end mt-3 mt-md-0">
                                    <a href="{{route('admin.users.demography.print')}}" target="_blank" class="btn btn-primary w-100" id="export-pdf">Cetak
                                        Laporan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-dark mb-2"><strong>Demografis Jenis Kelamin</strong></p>
                                    <div id="gender-chart"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Jenis Kelamin</strong></p>
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
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Umur: {{$data['averageAge']}}</strong></p>
                                    <div class="table-responsive">
                                        <table id="gender-list-table" class="table table-striped table-hover"
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
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-dark mb-2"><strong>Demografis Membership yang sedang Aktif</strong></p>
                                    <div id="membership-chart"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Membership</strong></p>
                                    <div class="table-responsive">
                                        <table id="memberships-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Nama Membership</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['membership_distribution'] as $name => $value)
                                                    <tr>
                                                        <td>{{ ucwords($name)}}</td>
                                                        <td>{{ number_format($value, 0)}} Pengguna</td>
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
                                    <p class="text-dark mb-2"><strong>Demografis Dompet</strong></p>
                                    <div id="wallet-chart"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Dompet</strong></p>
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Jumlah Dompet: {{$data['averageWallets']}} Dompet</strong></p>
                                    <div class="table-responsive ">
                                        <table id="wallets-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Jumlah Dompet</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['wallet_distribution'] as $number => $count)
                                                    <tr>
                                                        <td>{{ $number }} Dompet</td>
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
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Dompet Manual</strong></p>
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Jumlah Dompet Manual: {{$data['averageManualWallets']}} Dompet</strong></p>
                                    <div class="table-responsive">
                                        <table id="manual-wallets-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Jumlah Dompet</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['manual_wallet_distribution'] as $number => $count)
                                                    <tr>
                                                        <td>{{ $number }} Dompet</td>
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
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Dompet Binance</strong></p>
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Jumlah Dompet Binance: {{$data['averageBinanceWallets']}} Dompet</strong></p>
                                    <div class="table-responsive">
                                        <table id="binance-wallets-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Jumlah Dompet</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['binance_wallet_distribution'] as $number => $count)
                                                    <tr>
                                                        <td>{{ $number }} Dompet</td>
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
                                    <p class="text-dark mb-2"><strong>Demografis Jurnal</strong></p>
                                    <div id="journal-chart"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Jurnal</strong></p>
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Jumlah Jurnal: {{$data['averageJournals']}} Jurnal</strong></p>
                                    <div class="table-responsive ">
                                        <table id="journals-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Jumlah Jurnal</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['journal_distribution'] as $number => $count)
                                                    <tr>
                                                        <td>{{ $number }} Jurnal</td>
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
                                    <p class="text-dark mb-2"><strong>Demografis Catatan Trading</strong></p>
                                    <div id="trade-chart"></div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-muted mb-2 small"><strong>Daftar Distribusi Catatan Trading</strong></p>
                                    <p class="text-dark mb-2 small"><strong>Rata-Rata Jumlah Catatan Trading: {{$data['averageTrades']}} Catatan Trading</strong></p>
                                    <div class="table-responsive ">
                                        <table id="trades-list-table" class="table table-striped table-hover"
                                            role="grid" data-toggle="data-table">
                                            <thead>
                                                <tr class="light">
                                                    <th>Jumlah Catatan Trading</th>
                                                    <th>Jumlah Pengguna</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($data['trade_distribution'] as $number => $count)
                                                    <tr>
                                                        <td>{{ $number }} Catatan Trading</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
