const MY_CACHE = 'my-cache-name';
// w tej tablicy lądują wszystkie pliki, które chcemy dodać do cache
const MY_FILES = [
        '/css/app.css',
        '/images/logo.png',        
        '/images/icon.png',
        '/js/app.js',
        '/images/logo.png?9a5e800bbc300cb2cb86ef1a5b0af43d',
        '/manifest.json'
];

// instalujemy nasz service worker
self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(MY_CACHE).then(function(cache) {
      return cache.addAll(MY_FILES);
    })
  );
});
// po aktywacji chcę skasować wszystkie cache w naszej domenie, które nie są naszym cache (to opcjonalne)
self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.filter(function(cacheName) {
          return cacheName !== MY_CACHE;
        }).map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    })
  );
});
// strategia 'Network falling back to cache'
self.addEventListener('fetch', function(event) {
  event.respondWith(
    fetch(event.request).catch(function() {
      return caches.match(event.request);
    })
  );
});