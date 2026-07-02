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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Englebert&family=Knewave&family=Monoton&family=Rubik+Moonrocks&display=swap" rel="stylesheet">



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <div
             x-data="{
            commentsDrawer: false,
            selectedPost: null
        }"
        class="min-h-screen bg-gray-100">

            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="min-h-screen pt-20 transition-all duration-300">
                <div>
                    {{ $slot }}
                </div>
            </main>
        </div>
        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('user', {
                   avatar: @js(Auth::check()
                    ? (Auth::user()->avatar
                        ? asset('storage/' . Auth::user()->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name))
                    : null
                )
            })
        })
        </script>
        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('comments', {
                open: false,
                postId: null,

                open(id) {
                    this.postId = id
                    this.open = true
                },

                close() {
                    this.open = false
                    this.postId = null
                }
            })
        })
        </script>

        

    </body>
</html>
