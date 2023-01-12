@push('styles')
    <style>
        a.btn-social,
        .btn-social {
            border-radius: 50%;
            color: #ffffff !important;
            display: inline-block;
            height: 40px;
            line-height: 38px;
            margin: 4px 2px;
            text-align: center;
            text-decoration: none;
            transition: background-color .3s;
            webkit-transition: background-color .3s;
            width: 40px;
        }

        .btn-social .bi,
        .btn-social i {
            backface-visibility: ;
            moz-backface-visibility: ;
            ms-transform: scale(1);
            o-transform: scale(1);
            transform: scale(1);
            transition: all .25s;
            webkit-backface-visibility: ;
            webkit-transform: scale(1);
            webkit-transition: all .25s;
        }

        .btn-social:hover,
        .btn-social:focus {
            color: #fff;
            outline: none;
            text-decoration: none;
        }

        .btn-social:hover .bi,
        .btn-social:focus .bi,
        .btn-social:hover i,
        .btn-social:focus i {
            ms-transform: scale(1.3);
            o-transform: scale(1.3);
            transform: scale(1.3);
            webkit-transform: scale(1.3);
        }

        .btn-facebook {
            background-color: #4267B2;
        }

        .btn-facebook:hover {
            background-color: #1877F2;
        }

        .btn-github {
            background-color: #060606;
        }

        .btn-github:hover {
            background-color: #1B1F22;
        }

        .btn-google-plus {
            background-color: #b31412;
        }

        .btn-google-plus:hover {
            background-color: #ea4335;
        }

        .btn-instagram {
            background-color: #30618A;
        }

        .btn-instagram:hover {
            background-color: #405DE6;
        }

        .btn-reddit {
            background-color: #FF4500;
        }

        .btn-reddit:hover {
            background-color: #FF4500;
        }

        .btn-twitter {
            background-color: #1DA1F2;
        }

        .btn-twitter:hover {
            background-color: #55acee;
        }

        .btn-youtube {
            background-color: #FF0000;
        }

        .btn-youtube:hover {
            background-color: #e52d27;
        }

        .btn-email {
            background-color: #44c456;
        }

        .btn-email:hover {
            background-color: #6bd079;
        }

        .btn-telegram {
            background-color: #229ED9;
        }

        .btn-telegram:hover {
            background-color: #2AABEE;
        }


        .btn-discord {
            background-color: #7289da;
        }

        .btn-discord:hover {
            background-color: #667BC4;
        }
    </style>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function load(query) {
            $.ajax({
                url: "{{ route('user.wallet.asset.load', $wallet->id) }}",
                type: "get",
                dataType: "json",
                data: {
                    query: query
                },
                success: function(data) {
                    $("#twitter_timeline").empty();
                    $("#facebook_url").hide();
                    $("#github_url").hide();
                    $("#instagram_url").hide();
                    $("#reddit_url").hide();
                    $("#twitter_url").hide();
                    $("#telegram_url").hide();
                    $("#discord_url").hide();

                    // console.log(data);
                    $("#thumbnail").attr("src", data.thumb);
                    $("#name").text(data.name);
                    if (data.market_cap_rank != null) {
                        $("#market_cap_rank").text("#" + data.market_cap_rank.toLocaleString(
                            'en-US'));
                    } else {
                        $("#market_cap_rank").hide();
                    }
                    $("#current_price").text(data.current_price.toLocaleString('en-US') + "$");
                    $("#market_cap").text(data.market_cap.toLocaleString('en-US') + "$");
                    if (data.total_supply != null) {
                        $("#total_supply").text(data.total_supply.toLocaleString('en-US') +
                            "$");
                    } else {
                        $("#total_supply").text("-");
                    }
                    if (data.market_cap_24h != null) {
                        $("#market_cap_24h").text(data.market_cap_24h.toLocaleString('en-US') +
                            "$");
                    } else {
                        $("#market_cap_24h").text("-");
                    }
                    if (data.circulating_supply != null) {
                        $("#circulating_supply").text(data.circulating_supply.toLocaleString(
                            'en-US') + "$");
                    } else {
                        $("#circulating_supply").text("-");
                    }

                    let platforms = JSON.parse(data.platforms);
                    let platforms_mapped = Object.entries(platforms).map(([k, v]) =>
                        `${capitalizeFirstLetter(k)}: ${v}`);
                    // console.log(platforms_mapped);
                    let platforms_text = "";
                    platforms_mapped.forEach(element => {
                        if (element != ": ") {
                            platforms_text += element + "<br>";
                        }
                    });
                    if (platforms_text == "") {
                        platforms_text = "-";
                    }
                    // console.log(platforms_text);
                    $("#platforms").html(platforms_text);

                    let links = JSON.parse(data.links);
                    // console.log(links.homepage[0]);
                    $("#homepage").html(
                        `<a href="${links.homepage[0]}">${links.homepage[0]}</a>`);

                    // console.log(links);

                    if (links.facebook_username != "") {
                        $("#facebook_url").show();
                        $("#facebook_url").attr("href", "https://www.facebook.com/" + links
                            .facebook_username);
                    }

                    if (links.repos_url.github[0] != "") {
                        $("#github_url").show();
                        $("#github_url").attr("href", links.repos_url.github[0]);
                    }

                    if (links.facebook_username != "") {
                        $("#facebook_url").show();
                        $("#facebook_url").attr("href", "https://www.facebook.com/" + links
                            .facebook_username);
                    }

                    links.announcement_url.forEach(element => {
                        if (element.includes("instagram")) {
                            $("#instagram_url").show();
                            $("#instagram_url").attr("href", element);
                        }
                    });

                    links.chat_url.forEach(element => {
                        if (element.includes("instagram")) {
                            $("#instagram_url").show();
                            $("#instagram_url").attr("href", element);
                        }
                    });

                    if (links.subreddit_url != "") {
                        $("#reddit_url").show();
                        $("#reddit_url").attr("href", links.subreddit_url);
                    }

                    if (links.twitter_screen_name != "") {
                        $("#twitter_url").show();
                        $("#twitter_url").attr("href", "https://twitter.com/" + links
                            .twitter_screen_name);

                        $("#twitter-timeline").html(
                            `<a class="twitter-timeline" height="950"
                                        href="https://twitter.com/${links.twitter_screen_name}?ref_src=twsrc%5Etfw">Tweets dari @${links.twitter_screen_name}</a>`
                            );

                        var tag = document.createElement('script');
                        tag.setAttribute('id', 'twitter-script');
                        tag.src = "https://platform.twitter.com/widgets.js";
                        tag.defer = true;
                        document.getElementById('twitter-timeline').append(tag);
                        $("#twitter-timeline").show();
                        $("#tradingview").attr("class", "col-sm-12 col-md-8");
                    } else {
                        $("#tradingview").attr("class", "col-12");
                        $("#twitter-timeline").hide();
                    }

                    if (links.telegram_channel_identifier != "") {
                        $("#telegram_url").show();
                        $("#telegram_url").attr("href", "https://t.me/" + links
                            .telegram_channel_identifier);
                    }

                    links.announcement_url.forEach(element => {
                        if (element.includes("discord")) {
                            $("#discord_url").show();
                            $("#discord_url").attr("href", element);
                        }
                    });

                    links.chat_url.forEach(element => {
                        if (element.includes("discord")) {
                            $("#discord_url").show();
                            $("#discord_url").attr("href", element);
                        }
                    });

                    $(".tradingview-widget-container").html(
                        `
                        <div id="tradingview_7f87e" style="height: 100vh"></div>
                        <div class="tradingview-widget-copyright">
                            <a href="https://id.tradingview.com/symbols/${data.binance_symbol}/?exchange=BINANCE"                                                         rel="noopener" target="_blank">
                                <span class="blue-text">Chart ${data.binance_symbol}</span>
                            </a> oleh TradingView
                        </div>`);

                    new TradingView.widget({
                        "autosize": true,
                        "symbol": `BINANCE:${data.binance_symbol}`,
                        "interval": "60",
                        "timezone": "Asia/Jakarta",
                        "theme": "light",
                        "style": "1",
                        "locale": "id",
                        "toolbar_bg": "#f1f3f6",
                        "enable_publishing": false,
                        "withdateranges": true,
                        "hide_side_toolbar": false,
                        "details": true,
                        "studies": [
                            "MACD@tv-basicstudies",
                            "RSI@tv-basicstudies",
                            "Stochastic@tv-basicstudies"
                        ],
                        "container_id": "tradingview_7f87e"
                    });

                    $("#asetDetail").fadeIn();
                }
            });
        }

        load("{{$asset->coin_gecko_id}}");
    </script>
@endpush

<x-app-layout :options="['loading']">
    <x-back-button>{{route('user.wallet.asset.detail', ['wallet' => $wallet->id, 'asset' => $asset->id])}}</x-back-button>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body min-vh-100">
                        <div id="asetDetail" class="mt-3" style="display: none">
                            <div class="row justify-content-around">
                                <div class="col-sm-12 col-md-4 row justify-content-center align-items-center">
                                    <img id="thumbnail"
                                        src="https://assets.coingecko.com/coins/images/8713/thumb/STP.png?1560262664"
                                        alt="logo crypto" class="col-sm-12 col-md-4 img-fluid">
                                    <div class="header-title col-sm-12 col-md-8 mt-3">
                                        <h5 class="card-title"><span id="name"></span>
                                            <span class="badge rounded-pill bg-soft-primary"
                                                id="market_cap_rank"></span>
                                        </h5>
                                        <p>Harga Sekarang: <span id="current_price"></span></p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 mt-4 row justify-content-center align-items-center ">
                                    <p>Kapitulasi Pasar: <span id="market_cap"></span></p>
                                    <p>Volume Pasar 24 Jam Terakhir: <span id="market_cap_24h"></span></p>
                                </div>
                                <div class="col-sm-12 col-md-4 mt-3 row justify-content-center align-items-center ">
                                    <p>Pasokan yang Beredar: <span id="circulating_supply"></span></p>
                                    <p>Jumlah Pasokan: <span id="total_supply"></span></p>
                                </div>
                            </div>
                            <div class="row justify-content-around mt-3 mx-1">
                                <div class="col-sm-12 col-md-8 row justify-content-center align-items-center">
                                    <table>
                                        <tr style="height: 3em">
                                            <td
                                                style="vertical-align: top;
                                            text-align: left; width: 20%">
                                                Kontrak:</td>
                                            <td id="platforms"
                                                style="vertical-align: top;
                                            text-align: left; word-wrap: break-word; width:80%;   white-space: normal !important;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="vertical-align: top;
                                            text-align: left; width: 20%">
                                                Situs Web:</td>
                                            <td id="homepage"
                                                style="vertical-align: top;
                                            text-align: left;">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-12 col-md-4 d-inline-flex justify-content-start align-items-start">
                                    <p>Sosial Media:</p>
                                    <div id="social_media" class="ms-3">
                                        <a id="facebook_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-facebook"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" class="bi bi-facebook"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                            </svg></a>
                                        <a id="github_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-github"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" class="bi bi-github"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                            </svg></a>
                                        <a id="instagram_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-instagram"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor"
                                                class="bi bi-instagram" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                            </svg></a>
                                        <a id="reddit_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-reddit"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" class="bi bi-reddit"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M6.167 8a.831.831 0 0 0-.83.83c0 .459.372.84.83.831a.831.831 0 0 0 0-1.661zm1.843 3.647c.315 0 1.403-.038 1.976-.611a.232.232 0 0 0 0-.306.213.213 0 0 0-.306 0c-.353.363-1.126.487-1.67.487-.545 0-1.308-.124-1.671-.487a.213.213 0 0 0-.306 0 .213.213 0 0 0 0 .306c.564.563 1.652.61 1.977.61zm.992-2.807c0 .458.373.83.831.83.458 0 .83-.381.83-.83a.831.831 0 0 0-1.66 0z" />
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.828-1.165c-.315 0-.602.124-.812.325-.801-.573-1.9-.945-3.121-.993l.534-2.501 1.738.372a.83.83 0 1 0 .83-.869.83.83 0 0 0-.744.468l-1.938-.41a.203.203 0 0 0-.153.028.186.186 0 0 0-.086.134l-.592 2.788c-1.24.038-2.358.41-3.17.992-.21-.2-.496-.324-.81-.324a1.163 1.163 0 0 0-.478 2.224c-.02.115-.029.23-.029.353 0 1.795 2.091 3.256 4.669 3.256 2.577 0 4.668-1.451 4.668-3.256 0-.114-.01-.238-.029-.353.401-.181.688-.592.688-1.069 0-.65-.525-1.165-1.165-1.165z" />
                                            </svg></a>
                                        <a id="twitter_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-twitter"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor"
                                                class="bi bi-twitter" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                            </svg></a>
                                        <a id="youtube_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-youtube"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor"
                                                class="bi bi-youtube" viewBox="0 0 16 16">
                                                <path
                                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                            </svg></a>
                                        <a id="telegram_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-telegram"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor"
                                                class="bi bi-telegram" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z" />
                                            </svg></a>
                                        <a id="discord_url" href="#" style="display:none" target="_blank"
                                            class="btn-social btn-discord">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-discord" viewBox="0 0 16 16">
                                                <path
                                                    d="M13.545 2.907a13.227 13.227 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.19 12.19 0 0 0-3.658 0 8.258 8.258 0 0 0-.412-.833.051.051 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.041.041 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032c.001.014.01.028.021.037a13.276 13.276 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019c.308-.42.582-.863.818-1.329a.05.05 0 0 0-.01-.059.051.051 0 0 0-.018-.011 8.875 8.875 0 0 1-1.248-.595.05.05 0 0 1-.02-.066.051.051 0 0 1 .015-.019c.084-.063.168-.129.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.052.052 0 0 1 .053.007c.08.066.164.132.248.195a.051.051 0 0 1-.004.085 8.254 8.254 0 0 1-1.249.594.05.05 0 0 0-.03.03.052.052 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.235 13.235 0 0 0 4.001-2.02.049.049 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.034.034 0 0 0-.02-.019Zm-8.198 7.307c-.789 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612Zm5.316 0c-.788 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612Z" />
                                            </svg></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-around mt-3">
                                <div class="col-sm-12 col-md-8" id="tradingview">
                                    <!-- TradingView Widget BEGIN -->
                                    <div class="tradingview-widget-container">

                                    </div>
                                    <!-- TradingView Widget END -->
                                    <p class="text-dark"><small>
                                            Cara pembacaan: <br>
                                            • MACD: Cross kearah atas sinyal beli, Cross kearah bawah sinyal jual. <br>
                                            • RSI: Garis diatas 70 (Overbought / Terlalu mahal), Garis dibawah 30
                                            (Oversold / Terlalu murah) <br>
                                            • Stochastic: Cross garis diatas 80 ke bawah (Overbought / Terlalu mahal),
                                            Cross garis dibawah 20 ke atas (Oversold / Terlalu murah) <br>
                                            • Teknikal Trading View: Sinyal beli / jual dari Trading View
                                        </small>
                                    </p>
                                </div>
                                <div class="col-sm-12 col-md-4" id="twitter-timeline">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
