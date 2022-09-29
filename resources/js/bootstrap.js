import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.Popper = require('@popperjs/core').default;
window.$ = window.jQuery = require('jquery');
window.bootstrap = require('bootstrap/dist/js/bootstrap.min.js');
window.counterUp = require('counterup2');
window.noUiSlider = require('nouislider');
require( 'datatables.net-bs5' );
require( 'datatables.net-buttons-bs5' );
require( 'datatables.net-buttons/js/buttons.colVis.js' );
require( 'datatables.net-buttons/js/buttons.print.js' );
require( 'datatables.net-datetime' );
require( 'datatables.net-fixedcolumns-bs5' );
require( 'datatables.net-fixedheader-bs5' );
require( 'datatables.net-responsive-bs5' );
require( 'datatables.net-rowgroup-bs5' );
require( 'datatables.net-scroller-bs5' );
require( 'datatables.net-searchbuilder-bs5' );
require( 'datatables.net-searchpanes-bs5' );
require('smooth-scrollbar');
window.Swal = require('sweetalert2');
require('vanillajs-datepicker');
window.Scrollbar = require('smooth-scrollbar/dist/smooth-scrollbar')
window.ApexCharts = require('apexcharts');
window.waypoint = require('waypoints/lib/noframework.waypoints');
window.Swiper = require('swiper');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
