
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

import EchoLibrary from "laravel-echo"

window.Echo = new EchoLibrary({
    broadcaster: 'pusher',
    key: '05ddfe6c26eaafb78b1b',
    cluster: 'mt1',
    forceTLS: true,
    encrypted: true,
    wsPort: 6001
});

// Echo.channel('chat.1')
//     .listen('NewMessage', (e) => {
//         console.log(e);
//     });

