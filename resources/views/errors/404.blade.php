<x-guest-layout>
<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>

<div class="gradient">
    <div class="container">
        <img src="{{asset('images/error/404.png')}}" class="img-fluid mb-4 w-50" alt="">
        <h2 class="mb-0 mt-4 text-white">Oops! Halaman ini tidak ditemukan.</h2>
        <p class="mt-2 text-white">Halaman yang ingin ditujukan tidak tersedia.</p>
        <a class="btn bg-white text-primary d-inline-flex align-items-center" href="{{route('index')}}">Kembali</a>
    </div>
    <div class="box">
        <div class="c xl-circle">
            <div class="c lg-circle">
                <div class="c md-circle">
                    <div class="c sm-circle">
                        <div class="c xs-circle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
