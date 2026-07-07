const CACHE = 'pib-cache-v3';
const STATIC_ASSETS = [
  '/',
  '/login',
  '/dashboard',
  '/icon-192x192.png',
  '/icon-512x512.png',
  '/logo1.png',
  '/apple-touch-icon.png',
  '/favicon.ico',
  '/favicon.svg',
  '/style/assets/css/main.css',
  '/style/assets/vendor/bootstrap/css/bootstrap.min.css',
  '/style/assets/vendor/bootstrap-icons/bootstrap-icons.css',
  '/style/assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
  '/style/assets/js/main.js',
  '/style/assets/img/pib-logo.png',
  '/style/assets/img/logo.webp'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE).then(cache => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.map(k => { if (k !== CACHE) return caches.delete(k); }))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  if (url.origin !== location.origin) return;

  const isStatic = /\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/i.test(url.pathname);

  if (isStatic) {
    event.respondWith(
      caches.match(request).then(cached => cached || fetch(request).then(res => {
        const clone = res.clone();
        caches.open(CACHE).then(cache => cache.put(request, clone));
        return res;
      }))
    );
    return;
  }

  event.respondWith(
    fetch(request).catch(() => caches.match(request))
  );
});
