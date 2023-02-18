@push('styles')
    <style>
         .accordion-body{
            background-color: #F2F5FC !important ;
        }
    </style>
@endpush
<x-guest-layout>
    {{-- <div id="faqAccordion" class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-accordion career-style faq-style">
                    <div class="card iq-accordion-block">
                        <div class="active-faq clearfix" id="headingOne">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a role="contentinfo" class="accordion-title" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            <h6>Lorem ipsum dolor sit </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse show" id="collapseOne" aria-labelledby="headingOne"
                            data-parent="#faqAccordion">
                            <p class="mb-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS. </p>
                        </div>
                    </div>
                    <div class="card iq-accordion-block">
                        <div class="active-faq clearfix" id="headingTwo">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12"><a role="contentinfo" class="accordion-title collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            <h6> consectetur adipiscing elit
                                            </h6>
                                        </a></div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse" id="collapseTwo" aria-labelledby="headingTwo"
                            data-parent="#faqAccordion">
                            <p class="mb-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS.
                            </p>
                        </div>
                    </div>
                    <div class="card iq-accordion-block ">
                        <div class="active-faq clearfix" id="headingThree">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12"><a role="contentinfo" class="accordion-title collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            <h6>Etiam sit amet justo non </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse" id="collapseThree" aria-labelledby="headingThree"
                            data-parent="#faqAccordion">
                            <p class="mb-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS.
                            </p>
                        </div>
                    </div>
                    <div class="card iq-accordion-block ">
                        <div class="active-faq clearfix" id="headingFour">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12"><a role="contentinfo" class="accordion-title collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
                                            <h6> velit accumsan laoreet </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse" id="collapseFour" aria-labelledby="headingFour"
                            data-parent="#faqAccordion">
                            <p class="mb-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS.
                            </p>
                        </div>
                    </div>
                    <div class="card iq-accordion-block">
                        <div class="active-faq clearfix" id="headingFive">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12"><a role="contentinfo" class="accordion-title collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                            aria-expanded="false" aria-controls="collapseFive">
                                            <h6> Donec volutpat metus in erat </h6>
                                        </a></div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse" id="collapseFive" aria-labelledby="headingFive"
                            data-parent="#faqAccordion">
                            <p class="mb-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS.
                            </p>
                        </div>
                    </div>
                    <div class="card iq-accordion-block">
                        <div class="active-faq clearfix" id="headingSix">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12"><a role="contentinfo" class="accordion-title collapsed"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                            aria-expanded="false" aria-controls="collapseSix">
                                            <h6> quam quis massa tristique </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-details collapse" id="collapseSix" aria-labelledby="headingSix"
                            data-parent="#faqAccordion">
                            <p class="mb-0">>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus
                                terry richardson ad squid. 3 wolf moon officia
                                aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil
                                anim keffiyeh helvetica, craft
                                beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard
                                of them accusamus labore
                                sustainable VHS.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

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
                    <h4 class="card-title text-white ">Syarat Penggunaan</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Dengan menggunakan situs ini, Anda dianggap telah membaca, memahami, dan menyetujui semua
                            syarat dan ketentuan yang berlaku. Jika Anda tidak menyetujui dengan syarat dan ketentuan
                            yang ada, silakan berhenti menggunakan situs ini.</p>
                        <h5>1. Konten Situs</h5>
                        <p>Semua informasi, konten, dan materi pada situs ini hanya sebagai informasi umum dan tidak
                            dianggap sebagai saran investasi atau penawaran investasi. Situs ini tidak memberikan
                            jaminan apapun terhadap keakuratan, kelengkapan, atau keandalan informasi yang terdapat di
                            dalamnya. Pengguna situs bertanggung jawab penuh atas keputusan investasi yang diambil.</p>
                        <h5>2. Kebijakan Privasi</h5>
                        <p>Situs ini memiliki kebijakan privasi yang harus dipatuhi oleh setiap pengguna. Informasi
                            pribadi pengguna akan dilindungi sesuai dengan kebijakan privasi situs ini. Pengguna situs
                            disarankan untuk membaca kebijakan privasi ini sebelum menggunakan situs.</p>
                        <h5>3. Batasan Tanggung Jawab</h5>
                        <p>Situs ini tidak bertanggung jawab atas kerugian atau kerusakan yang diakibatkan oleh
                            penggunaan situs atau informasi yang terdapat di dalamnya. Pengguna situs bertanggung jawab
                            sepenuhnya atas penggunaan situs dan segala risiko yang mungkin timbul dari penggunaan
                            situs.</p>
                        <h5>4. Perubahan Syarat dan Ketentuan</h5>
                        <p>Situs ini berhak untuk mengubah syarat dan ketentuan pada setiap waktu tanpa pemberitahuan
                            terlebih dahulu. Pengguna situs diharapkan untuk memeriksa syarat dan ketentuan yang berlaku
                            secara berkala.</p>
                        <h5>5. Pertanyaan yang Sering Ditanyakan (Frequently Asked Question FAQ)</h5>
                        <div class="accordion mt-3" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Apa itu CatatCrypto?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        CatatCrypto adalah website yang digunakan untuk manajemen portofolio investasi aset kripto dan jurnal trading.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Apakah informasi di CatatCrypto akurat?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Semua informasi yang ada di CatatCrypto hanya sebagai informasi umum dan tidak dianggap sebagai saran investasi atau penawaran investasi. CatatCrypto tidak memberikan jaminan apapun terhadap keakuratan, kelengkapan, atau keandalan informasi yang terdapat di dalamnya. Pengguna situs bertanggung jawab penuh atas
                                        keputusan investasi yang diambil.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Bagaimana mengatur portofolio di CatatCrypto?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Anda dapat mengatur portofolio di CatatCrypto dengan menambahkan dompet portofolio yang dimiliki dan mengatur aset yang dimiliki dompet portofolio tersebut.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                        Bagaimana menjaga privasi di CatatCrypto?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        CatatCrypto memiliki kebijakan privasi yang harus dipatuhi oleh setiap pengguna. Informasi pribadi pengguna akan dilindungi sesuai dengan kebijakan privasi situs ini. Pengguna situs disarankan untuk membaca kebijakan privasi ini sebelum
                                        menggunakan situs.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Apakah saya harus membayar untuk menggunakan situs ini?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Tidak, penggunaan situs ini sepenuhnya gratis. Namun, terdapat layanan berbayar yang dapat membantu pengguna dalam pengelolaan portofolio dan jurnal trading mereka.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        Apakah situs ini memberikan saran investasi?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Tidak, situs ini hanya menyediakan informasi umum mengenai aset kripto dan jurnal trading. Pengguna situs bertanggung jawab penuh atas keputusan investasi yang diambil.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        Apakah situs ini aman untuk digunakan?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Situs ini menggunakan standar keamanan yang tinggi untuk melindungi informasi pengguna. Namun, pengguna situs disarankan untuk selalu menggunakan tindakan pencegahan tambahan seperti
                                        password yang kuat dan menghindari akses situs menggunakan jaringan yang tidak terpercaya.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>
