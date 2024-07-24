<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts


        @stack('scripts')

            <!-- Agrega tu script aquí -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    function formatNumberInput(input) {
                        input.addEventListener('input', function (e) {
                            // Remove all non-digit characters
                            let value = e.target.value.replace(/\D/g, '');

                            // Limit to a maximum length
                            if (value.length > 5) {
                                value = value.slice(0, 5);
                            }

                            // Format with thousands separator
                            const formattedValue = new Intl.NumberFormat('es-ES').format(value);
                            e.target.value = formattedValue;
                        });
                    }

                    const pesoBruto = document.getElementById('pesoBruto');
                    const pesoTara = document.getElementById('pesoTara');
                    const pesoNeto = document.getElementById('pesoNeto');

                    if (pesoBruto) formatNumberInput(pesoBruto);
                    if (pesoTara) formatNumberInput(pesoTara);
                    if (pesoNeto) formatNumberInput(pesoNeto);
                });
            </script>

                @section('content')
                    <form>
                        <div>
                            <label for="pesoBruto">Peso Bruto:</label>
                            <input type="text" id="pesoBruto" name="peso_bruto" maxlength="8">
                        </div>
                        <div>
                            <label for="pesoTara">Peso Tara:</label>
                            <input type="text" id="pesoTara" name="peso_tara" maxlength="8">
                        </div>
                        <div>
                            <label for="pesoNeto">Peso Neto:</label>
                            <input type="text" id="pesoNeto" name="peso_neto" maxlength="8">
                        </div>
                        <!-- otros campos de formulario -->
                    </form>
                @endsection
    </body>
</html>



<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Otros meta tags y enlaces -->
    @livewireStyles
</head>
<body>
    <!-- Contenido de la aplicación -->
    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
