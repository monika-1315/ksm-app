<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    
    <link rel="manifest" crossorigin="use-credentials" href="./manifest.json">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fdd835">
    <meta name="description" content="Aplikacja Katolickiego Stowarzyszenia Młodzieży Legnickiej">
    <title>KSM DL</title>

   
</head>
<body >
    <div id="app"></div>
<script src="/js/app.js"></script>
<script>
// sprawdzamy czy przeglądarka posiada wsparcie dla service workera
if ('serviceWorker' in navigator) {
    // próba instalacji
    navigator.serviceWorker.register('/service-worker.js').then(function () {
        console.log('Service worker zainstalowany');
    }).catch(function (err) {
        // jeśli coś pójdzie nie tak- konsola nam powie co trzeba poprawić
        console.log('Service worker nie zainstalowany, sprawdź błąd:', err)
    });
}
</script>
</body>
</html>