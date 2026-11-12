
@props(['title' => 'PuskesMap - Peta Puskesmas Kota Bogor', 'bodyClass' => ''])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
<<<<<<< HEAD

    <!-- Space Grotesk Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

=======
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.documentElement.classList.add('js');
        setTimeout(function () {
            if (!window.__revealReady) { document.documentElement.classList.remove('js'); }
        }, 2500);
    </script>
>>>>>>> 6ec0def87e3397568d0d2ff82ca49e4e81337d74
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased overflow-x-hidden {{ $bodyClass }}">
    {{ $slot }}
</body>
</html>
