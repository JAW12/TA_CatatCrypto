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
        <x-back-button>{{route('pages.landing-page')}}</x-back-button>
        <div class="card shadow">
            <div class="card-header bg-primary py-3 d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title text-white ">Manajemen Portofolio Investasi Aset Kripto</h4>
                </div>
            </div>
            <div class="card-body">
                <p>Untuk melakukan manajemen portofolio investasi aset kripto di CatatCrypto, pengguna dapat mengikuti langkah-langkah berikut:</p>
                <ol>
                    <li>Pastikan pengguna memiliki masa trial atau membership yang berlaku.</li>
                    <li>Pergi ke menu Dompet dan klik Daftar Dompet di navigasi.</li>
                    <li>Pastikan pengguna memiliki jumlah dompet yang dapat ditambahkan.</li>
                    <li>Jika bisa, pengguna dapat menekan tombol "+ Tambah Dompet".</li>
                    <li>Isi informasi dompet seperti nama dompet, saldo dompet, dan keterangan dompet. Jika pengguna memiliki akses integrasi Binance, pengguna dapat mengisi Binance API Key dan Secret Key.</li>
                    <li>Buat Binance API Key di Binance API Management dan pastikan akses yang diberikan hanya "allow reading" dan "allow margin & spot trading". Untuk authorized IP access bisa berikan '103.55.39.194'.</li>
                    <li>Pengguna dapat menambahkan aset dengan menekan tombol "+ Tambah Aset".</li>
                    <li>Pilih aset yang tersedia dan lihat informasi aset tersebut.</li>
                    <li>Jika sudah yakin, pengguna dapat menekan tombol "Tambah".</li>
                    <li>Pengguna dapat menekan aset yang sudah ditambahkan tersebut di daftar Aset dari dompet tersebut.</li>
                    <li>Pengguna dapat menekan tombol "+ Tambah Transaksi" untuk menambahkan transaksi untuk aset tersebut.</li>
                    <li>Isi informasi transaksi aset seperti jenis transaksi, harga, jumlah, biaya tambahan, dan lain-lain.</li>
                    <li>Pengguna dapat menekan tombol "Kumpul". Jika dompet terintegrasi Binance, akun Binance yang terintegrasi akan otomatis terdapat order seperti transaksi yang dibuat.</li>
                </ol>
            </div>
        </div>
        <div class="text-center pb-5" data-aos="zoom-in">
            <a href="{{route('index')}}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </div>
    </div>
</x-guest-layout>
