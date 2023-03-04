@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
@endpush
@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush
<x-guest-layout>
    <div class="container my-5">
        <div class="d-flex justify-content-center mb-5">
            <svg width="50" class="text-primary" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                    transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                    transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                    transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                    transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
            </svg>
            <h1 class="ms-2 logo-title">{{ config('app.name') }}</h1>
        </div>
        <x-back-button>{{ route('pages.landing-page') }}</x-back-button>
        <div class="card shadow">
            <div class="card-header bg-primary py-3 d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title text-white ">Jurnal Trading</h4>
                </div>
            </div>
            <div class="card-body">
                <p>Untuk menggunakan jurnal trading di CatatCrypto, pengguna dapat mengikuti langkah-langkah berikut:
                </p>
                <ol>
                    <li>Pengguna memiliki masa trial / membership yang berlaku.</li>
                    <li>Pengguna pergi ke menu Jurnal Trading > Daftar Jurnal di navigasi</li>
                    <li>Pengguna memastikan memiliki jumlah jurnal yang dapat ditambahkan.</li>
                    <li>Apabila bisa, pengguna dapat menekan tombol "+ Tambah Jurnal"</li>
                    <li>Isi informasi jurnal tersebut, seperti nama jurnal, saldo jurnal, resiko per trading yang
                        diberlakukan untuk jurnal tersebut, target per bulan, dan keterangan jurnal tersebut.</li>
                    <li>Pengguna dapat menambahkan catatan trading dengan menekan tombol "+ Tambah Catatan"</li>
                    <li>Pengguna mengisi informasi koin, seperti koin apa yang ditradingkan, tipe trading (long /
                        short), leverage yang digunakan, harga entri dan jumlah yang direncanakan.</li>
                    <li>Sistem akan menampilkan margin yang akan digunakan.</li>
                    <li>Pengguna mengisi informasi TP 1 dan SL 1 yang sudah tersedia.</li>
                    <li>Sistem akan menampilkan rekomendasi jumlah koin yang sesuai dengan resiko jurnal, potensi
                        keuntungan / kerugian dari informasi TP / SL tersebut dan perkiraan risk ratio tersebut.</li>
                    <li>Pengguna dapat menambahkan target TP / SL tambahan dan mengisi informasi tersebut, potensi
                        keuntungan dan kerugian juga akan dihitungkan secara otomatis.</li>
                    <li>Pengguna dapat memilih analisa yang digunakan menggunakan timeframe apa saja.</li>
                    <li>Pengguna juga dapat memilih analisa yang digunakan menggunakan strategi apa saja, dari strategi
                        entri, pola fibonacci, pola candlestick, pola chart, dan indikator yang digunakan.</li>
                    <li>Untuk masing-masing timeframe, pengguna dapat menambahkan gambar analisa dengan mengupload
                        gambar / mengisi link URL Trading View.</li>
                    <li>Pengguna dapat menambahkan catatan untuk trading plan tersebut.</li>
                    <li>Pengguna menekan tombol "Tambah".</li>
                    <li>Pengguna kemudian dapat menambahkan transaksi sebagai eksekusi catatan trading / trading plan
                        tersebut dengan menekan tombol "+ Tambah Transaksi" dan mengisi informasi transaksi tersebut
                        seperti harga, jumlah, biaya tambahan dan jenis transaksi (Entri / Tutup).</li>
                    <li>Eksekusi trading plan akan dipantau, dari status Pending, Aktif (sedang berjalan, masih ada sisa
                        jumlah), dan Selesai (sisa jumlah sudah 0).</li>
                    <li>Pengguna juga dapat menambahkan 4 screenshot sebagai dokumentasi, seperti proses perkembangan
                        pergerakan harga, berita, artikel atau dokumentasi share PNL dari exchanges yang digunakan.</li>
                </ol>
            </div>
        </div>
        <div class="text-center pb-5" data-aos="zoom-in">
            <a href="{{ route('index') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </div>
    </div>
</x-guest-layout>
