import Alpine from 'alpinejs';
import './echo';

window.Alpine = Alpine;
Alpine.start();

// ============================================================
// Global AJAX helpers
// ============================================================

/**
 * POST with CSRF token and JSON body.
 */
window.postJson = async function (url, data = {}) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            'Accept': 'application/json',
            'X-Socket-ID': window.Echo ? window.Echo.socketId() : '',
        },
        body: JSON.stringify(data),
    });
    return response;
};

/**
 * GET with JSON accept header.
 */
window.getJson = async function (url) {
    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
    });
    return response;
};

/**
 * POST with FormData (file uploads).
 */
window.postForm = async function (url, formData) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            'Accept': 'application/json',
            'X-Socket-ID': window.Echo ? window.Echo.socketId() : '',
        },
        body: formData,
    });
    return response;
};

// ============================================================
// Flash message auto-dismiss
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            setTimeout(() => flash.remove(), 400);
        }, 4000);
    }
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
