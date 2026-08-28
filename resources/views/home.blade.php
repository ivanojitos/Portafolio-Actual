<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="{{ $profile->meta_description }}"
    >

    <meta name="author" content="{{ $profile->full_name }}">

    <title>{{ $profile->meta_title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <livewire:home :$profile />

    @livewireScripts
</body>
</html>
