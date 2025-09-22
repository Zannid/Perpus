import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

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
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
    forceTLS: true
});

window.Echo.channel('peminjaman-channel')
    .listen('.peminjaman.created', (e) => {
        // Tambahkan notifikasi ke dropdown
        let dropdown = document.querySelector('.dropdown-menu');
        let newNotif = document.createElement('li');
        newNotif.innerHTML = `
            <a class="dropdown-item d-flex justify-content-between" href="/peminjaman/${e.peminjaman.id}">
                <div>
                    <span class="fw-semibold d-block">${e.peminjaman.user.name}</span>
                    <small class="text-muted">Mengajukan peminjaman: ${e.peminjaman.buku.judul}</small>
                </div>
                <small class="text-muted">baru saja</small>
            </a>
        `;
        dropdown.prepend(newNotif);

        // Update badge
        let badge = document.querySelector('.badge.bg-danger');
        if (badge) {
            badge.innerText = parseInt(badge.innerText) + 1;
        } else {
            let bell = document.querySelector('.bx-bell');
            let span = document.createElement('span');
            span.className = 'badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle';
            span.innerText = 1;
            bell.parentNode.appendChild(span);
        }
    });
