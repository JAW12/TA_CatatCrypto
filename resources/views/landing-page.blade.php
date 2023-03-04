@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
@endpush
@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        #manajemenPorto .carousel-item img {
            object-fit: contain;
            object-position: center;
            overflow: hidden;
            height: 50vh;
        }

        #jurnalTrading .carousel-item img {
            object-fit: contain;
            object-position: center;
            overflow: hidden;
            height: 100vh;
        }
    </style>
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
        <div class="card shadow">
            <div class="card-header bg-primary py-3 d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title text-white ">Apa itu CatatCrypto?</h4>
                </div>
            </div>
            <div class="card-body">
                <p>CatatCrypto adalah sebuah platform untuk mengelola investasi aset kripto Anda. Dengan CatatCrypto,
                    Anda dapat memantau portofolio investasi Anda, mencatat jurnal trading, mengakses daftar pustaka
                    untuk belajar analisis teknikal, dan melihat laporan performa investasi dan kegiatan trading.</p>
            </div>
        </div>
        <div class="card my-5 animate__animated animate__fadeInLeft animate__delay-0.5s" data-aos="fade-right">
            <div class="card-header">
                <h2>Manajemen Portofolio Investasi Aset Kripto</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="manajemenPorto" class="carousel carousel-dark slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#manajemenPorto" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#manajemenPorto" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#manajemenPorto" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#manajemenPorto" data-bs-slide-to="3"
                                    aria-label="Slide 4"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/landingpage/dompet.png') }}" class="d-block w-100"
                                        alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/detail_dompet.png') }}" class="d-block w-100"
                                        alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_demografi_aset.png') }}" class="d-block w-100"
                                        alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_aset.png') }}" class="d-block w-100"
                                        alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#manajemenPorto"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#manajemenPorto"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Selanjutnya</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Dompet Kripto</h3>
                        <p>Kelola investasi kripto Anda dengan mudah menggunakan dompet kripto di CatatCrypto. Anda
                            dapat membuat dompet manual atau terintegrasi dengan akun Binance untuk melakukan transaksi
                            SPOT jual beli secara langsung.</p>
                        <h3>Alokasi Investasi</h3>
                        <p>Anda dapat melihat alokasi aset investasi Anda secara keseluruhan ataupun masing-masing
                            dompet.</p>
                        <h3>Performa Investasi</h3>
                        <p>Anda juga dapat melihat performa dari aset yang Anda investasikan, seperti perubahan
                            kumulatif kuantitas dan nilai dari aset tersebut.</p>
                        <a href="{{route('pages.manajemen-porto')}}" class="btn btn-primary">Cara Menggunakan</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-5" data-aos="fade-left">
            <div class="card-header">
                <h2>Jurnal Trading</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Trading Plan</h3>
                        <p>Catat trading plan Anda dengan mudah menggunakan fitur jurnal trading di CatatCrypto. Anda
                            dapat menambahkan informasi seperti leverage, harga entri, margin, jumlah koin, target take
                            profit / stop loss, dan rasio resiko perkiraan dari target tersebut. Anda juga dapat
                            menentukan trading plan ini menggunakan analisa timeframe apa saja disertai gambar
                            analisanya untuk timeframe tersebut, menambahkan strategi entri, candlestick, chart pattern,
                            fibonacci, dan indikator apa yang dipakai dalam analisa untuk trading plan tersebut. Selain
                            itu, Anda juga dapat melakukan track kegiatan trading dari transaksi yang dilakukan dan
                            menambahkan beberapa screenshot sebagai dokumentasi.</p>
                        <h3>Metrik Trading</h3>
                        <p>Anda dapat melihat metrik-metrik yang menentukan performa dalam kegiatan trading Anda,
                            seperti keuntungan yang didapatkan, persentase kemenangan, jumlah trading yang dilakukan,
                            dan lain-lain.
                        <h3>Perfoma Trading</h3>
                        <p>Anda dapat melihat performa kegiatan trading Anda
                            berdasarkan koin, timeframe atau strategi (pustaka) yang dipakai. Seperti koin kripto
                            manakah yang memberikan keuntungan / kerugian terbesar, frekuensi trading dengan koin
                            tersebut, analisa menggunakan timeframe apa yang paling menguntungkan, dan strategi
                            (pustaka) apa yang digunakan dalama analisa yang cukup tepat (persentase berhasilnya besar).
                        </p>
                        <a href="{{route('pages.jurnal-trading')}}" class="btn btn-primary">Cara Menggunakan</a>
                    </div>
                    <div class="col-md-6">
                        <div id="jurnalTrading" class="carousel carousel-dark slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="3"
                                    aria-label="Slide 4"></button>
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="4"
                                    aria-label="Slide 5"></button>
                                <button type="button" data-bs-target="#jurnalTrading" data-bs-slide-to="5"
                                    aria-label="Slide 6"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/landingpage/jurnal_trading.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/catatan_trading.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_metrik.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_riwayat.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_pustaka.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/landingpage/laporan_koin.png') }}"
                                        class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#jurnalTrading"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#jurnalTrading"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Selanjutnya</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-5" data-aos="flip-left">
            <div class="card-header">
                <h2>Daftar Pustaka</h2>
            </div>
            <div class="card-body">
                <p>CatatCrypto menyediakan daftar pustaka sebagai modul edukasi untuk belajar analisa teknikal yang umum
                    dipakai dari strategi entri, pola chart, pola fibonacci, pola candlestick dan indikator yang umum
                    dipakai. Dengan modul edukasi ini, Anda dapat meningkatkan pengetahuan dan keterampilan dalam
                    kegiatan trading Anda, sehingga dapat mencapai tujuan investasi yang diinginkan.</p>
            </div>
        </div>
        <div class="text-center pb-5" data-aos="zoom-in">
            <a href="{{route('index')}}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </div>
    </div>
</x-guest-layout>
