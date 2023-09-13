<!-- <div class="min-h-screen flex flex-col sm:justify-center bg-green-800 bg-opacity-100 items-center pt-6 sm:py-6 bg-gray-100"> -->
<div class="min-h-screen flex flex-col sm:justify-center bg-opacity-50 items-center pt-6 sm:py-6 " style="background-image: url(&quot;/storage/img/fondo.jpeg&quot;);">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white bg-opacity-50 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
    <div class="mt-8 text-sm">
        <p style="font-weight: bold; color: white;">En caso de que tenga algún inconveniente para ingresar, puede
            escribir un correo a <a href="mailto:soporte@eus3pre.com">soporte@eus3pre.com</a>
        </p>
    </div>
</div>