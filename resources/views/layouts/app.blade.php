<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* .smooth {transition: box-shadow 0.3s ease-in-out;} */
        /* ::selection{background-color: aliceblue} */
	</style>

</head>

<body class="font-sans leading-normal tracking-normal bg-white">

	<div class="w-full p-0 m-0 bg-[#ebf8ff] md:flex md:justify-between md:items-center lg:px-32" id="nav">
        <div class="flex items-center justify-between px-4">

		    <div class="">
				<a class="" href="/">
                    <img src="/images/club-logo-blue-100.png" class="hidden h-16 mt-2 mb-2 md:block md:h-24"> 
                    <img src="/images/RotarySimplified.png" class="h-16 mt-2 mb-2 md:hidden md:h-24"> 
				</a>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>


	<footer class="bg-gray-900">	
		<div class="container flex items-center max-w-6xl px-2 py-8 mx-auto"> 

			<div class="flex flex-wrap items-center w-full mx-auto">
				<div class="flex justify-start w-1/3">
					<a class="text-gray-200 no-underline hover:underline" href="/">
                        <img src="/images/RotaryLogo.png" class="inline w-32 mr-2">
                        <span class="align-middle">Ravenshead &amp; Blidworth</span>
					</a>
				</div>
		</div>


	</footer>
</body>
</html>
