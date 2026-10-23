/*
 * SPC Universal — service worker
 *
 * Strategy:
 *   - Navigations (pages): network-first, cached copy saved and used as the
 *     offline fallback, so the app always shows fresh data when online.
 *   - Static assets (css/js/images/fonts): cache-first with a background
 *     refresh, so repeat visits load instantly.
 *   - Everything else (POST, APIs, third-party CDNs): untouched.
 *
 * Bump CACHE whenever a breaking asset change ships — old caches are
 * removed automatically on activate.
 */
const CACHE = 'spc-pwa-v1';

self.addEventListener('install', function () {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (key) { return key !== CACHE; })
                    .map(function (key) { return caches.delete(key); })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    const request = event.request;

    // Only plain GETs of our own origin are cache-managed.
    if (request.method !== 'GET') return;
    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    // Pages: network first, cached page as offline fallback.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).then(function (response) {
                if (response && response.status === 200) {
                    const copy = response.clone();
                    caches.open(CACHE).then(function (cache) { cache.put(request, copy); });
                }
                return response;
            }).catch(function () {
                return caches.match(request).then(function (cached) {
                    return cached || new Response('You are offline.', {
                        status: 503,
                        headers: { 'Content-Type': 'text/plain' }
                    });
                });
            })
        );
        return;
    }

    // Static assets: cache first, refresh in the background.
    if (request.destination === 'style' || request.destination === 'script' ||
        request.destination === 'image' || request.destination === 'font') {
        event.respondWith(
            caches.match(request).then(function (cached) {
                const network = fetch(request).then(function (response) {
                    if (response && response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE).then(function (cache) { cache.put(request, copy); });
                    }
                    return response;
                }).catch(function () { return cached; });

                return cached || network;
            })
        );
    }
});
