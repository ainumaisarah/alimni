console.log("SW FILE LOADED");

const CACHE_NAME = 'alimni-cache-v1';

// Pre-cache core assets
const filesToCache = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/logo.png'
];

// Install event — cache core assets
self.addEventListener('install', event => {
    console.log('SW installing and caching core files');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(filesToCache))
    );
    self.skipWaiting();
});

// Activate event — clean old caches
self.addEventListener('activate', event => {
    console.log('SW activating');
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// Fetch event — dynamic caching for GET requests only
self.addEventListener('fetch', event => {
    const requestUrl = new URL(event.request.url);

    // Skip SW itself, manifest, login/register POSTs, and non-GET requests
    if (
        requestUrl.pathname === '/sw.js' ||
        requestUrl.pathname === '/manifest.json' ||
        event.request.method !== 'GET' ||
        requestUrl.pathname.startsWith('/login') ||
        requestUrl.pathname.startsWith('/register')
    ) return;

    // Dynamic caching for student quiz pages
    if (requestUrl.pathname.startsWith('/student/quizzes/')) {
        event.respondWith(
            caches.match(event.request).then(cachedResponse => {
                if (cachedResponse) return cachedResponse;

                return fetch(event.request).then(response => {
                    return caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, response.clone());
                        return response;
                    });
                }).catch(() => caches.match('/offline.html'));
            })
        );
        return;
    }

    // Default GET fetch handler for other pages
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            return cachedResponse || fetch(event.request)
                .then(response => {
                    return caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, response.clone());
                        return response;
                    });
                })
                .catch(() => caches.match('/offline.html'));
        })
    );
});

// Background sync for offline submissions
self.addEventListener('sync', event => {
    if (event.tag === 'sync-submissions') {
        console.log('[SW] Syncing offline submissions...');
        event.waitUntil(syncSubmissions());
    }
});

// Function to ask clients to send offline submissions
async function syncSubmissions() {
    const allClients = await self.clients.matchAll({ includeUncontrolled: true });
    if (!allClients.length) return;

    allClients.forEach(client => {
        client.postMessage({ action: 'sync-submissions' });
    });
}
