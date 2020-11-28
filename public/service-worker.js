const MY_CACHE = 'my-cache-name';
// here are all the files that will be added to cache
const MY_FILES = [
        '/css/app.css',
        '/images/logo.png',        
        '/images/icon.png',
        '/images/maskable_icon.png',
        '/js/app.js'
];

// install our service worker
self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(MY_CACHE).then(function(cache) {
      return cache.addAll(MY_FILES);
    })
  );
});
// after activation delete all caches in our domain that aren't ours
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
// strategy 'Network falling back to cache'
self.addEventListener('fetch', function(event) {
  event.respondWith(
    fetch(event.request).catch(function() {
      return caches.match(event.request);
    })
  );
});