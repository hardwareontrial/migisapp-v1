import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.VUE_APP_WEBSOCKETS_KEY,
    wsHost: process.env.VUE_APP_WEBSOCKETS_SERVER,
    wsPort:6001,
    forceTLS: false,
    disableStats: true,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    // forceTLS: true
});
