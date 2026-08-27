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
        content="Portafolio profesional de un desarrollador de software especializado en Laravel."
    >

    <title>Mi Portafolio | Desarrollador de Software</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <livewire:home />

    @livewireScripts
</body>
</html>
