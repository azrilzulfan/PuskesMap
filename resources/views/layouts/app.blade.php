@props([
    'title' => 'PuskesMap - Sistem Pendukung Keputusan Spasial Kota Bogor',
    'bodyClass' => '',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body data-alpine="bundled" class="sdss-shell min-h-dvh bg-[var(--base)] text-[var(--text)] antialiased overflow-x-hidden {{ $bodyClass }}">
    {{ $slot }}
</body>
</html>
