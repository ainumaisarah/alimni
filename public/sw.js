console.log("SW FILE LOADED");

const CACHE_NAME = 'alimni-static-v1';

// ✅ ONLY static assets (SAFE)
const STATIC_FILES = [
    '/offline.html',
    '/manifest.json',
    '/logo.png'
];

// --------------------
// INSTALL
// --------------------
self.addEventListener('install', event => {
    console.log('[SW] Installing');
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_FILES);
        })
    );
    self.skipWaiting();
});

// --------------------
// ACTIVATE
// --------------------
self.addEventListener('activate', event => {
    console.log('[SW] Activating');
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// --------------------
// FETCH
// --------------------
self.addEventListener('fetch', event => {
    const req = event.request;

    // ❌ NEVER touch non-GET (POST, PUT, DELETE)
    if (req.method !== 'GET') return;

    const url = new URL(req.url);

    // ❌ NEVER cache auth / role / session pages
    if (
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/register') ||
        url.pathname.startsWith('/logout') ||
        url.pathname.startsWith('/dashboard') ||
        url.pathname.startsWith('/student') ||
        url.pathname.startsWith('/teacher') ||
        url.pathname.startsWith('/admin')
    ) {
        return; // let Laravel handle it normally
    }

    // ✅ Cache ONLY static assets
    if (
        req.destination === 'style' ||
        req.destination === 'script' ||
        req.destination === 'image'
    ) {
        event.respondWith(
            caches.match(req).then(cached => {
                return (
                    cached ||
                    fetch(req).then(response => {
                        return caches.open(CACHE_NAME).then(cache => {
                            cache.put(req, response.clone());
                            return response;
                        });
                    })
                );
            })
        );
    }
});

// --------------------
// BACKGROUND SYNC
// --------------------
self.addEventListener('sync', event => {
    if (event.tag === 'sync-submissions') {
        console.log('[SW] Syncing offline submissions');
        event.waitUntil(notifyClientsToSync());
    }
});

async function notifyClientsToSync() {
    const clients = await self.clients.matchAll({ includeUncontrolled: true });
    clients.forEach(client => {
        client.postMessage({ action: 'sync-submissions' });
    });
}
