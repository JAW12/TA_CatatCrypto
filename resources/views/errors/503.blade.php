<x-guest-layout>
<div class="container-fluid p-0">
    <div class="iq-maintenance text-center">
        <img src="{{asset('images/error/01.png')}}" class="img-fluid mb-4" alt="">
        <div class="maintenance-bottom text-white pb-0">
            <div class="bg-primary" style="background: transparent; height: 320px;">
                <div class="gradient-bottom">
                    <div class="bottom-text general-zindex">
                        <h1 class="mb-2 text-white">Tunggu sebentar! Kami sedang dalam pemeliharaan</h1>
                        <p>Tidak akan memakan waktu lama sampai kami memperbaiki kesalahan. Kami akan kembali lagi dalam</p>
                        <ul class="countdown d-flex justify-content-center align-items-center list-inline" data-date="April 22 2023 20:20:22">
                            <li>
                                <span data-days>0</span>Hari
                            </li>
                            <li>
                                <span data-hours>0</span>Jam
                            </li>
                            <li>
                                <span data-minutes>0</span>Menit
                            </li>
                            <li>
                                <span data-seconds>0</span>Detik
                            </li>
                        </ul>
                    </div>
                    <div class="c xl-circle">
                        <div class="c lg-circle">
                            <div class="c md-circle">
                            <div class="c sm-circle">
                                <div class="c xs-circle"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sign-bg">
        <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g opacity="0.05">
            <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
            <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
            <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
            <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
            </g>
        </svg>
    </div>
</div>
</x-guest-layout>


<script src="{{asset('js/countdown.js')}}"></script>
