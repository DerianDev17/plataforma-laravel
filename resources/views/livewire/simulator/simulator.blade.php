<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Simulador EUS3') }}
    </h2>
</x-slot>






<!--<div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 items-center text-center">

        <form>
            <div class="pb-1.5">
                Ingresa tu nota de examen: <input class="border rounded px-4 py-2" type="text" id="firstNumber" /><br></div>
            <div>
                Ingresa tu nota de grado: <input class="border rounded px-4 py-2" type="text" id="secondNumber" /><br></div><br>
            <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button" onClick="multiplyBy()" Value="Calcular" />
        </form>
        <br>
        <p>El resultado es : <br>
            <strong class="text-3xl">
                <span id="result"></span>
            </strong>
        </p>
    </div>
</div>-->
<!--
<script>
    function multiplyBy() {
        num1 = document.getElementById("firstNumber").value;
        num2 = document.getElementById("secondNumber").value;

        if (isNaN(num1) || num1 < 1 || num1 > 1000) {
            alert('Rango en el numero incorrecto');
        } else if (isNaN(num2) || num2 < 1 || num2 > 10) {
            alert('Rango en el numero incorrecto');
        } else {
            num3 = (num1 * 500) / 1000
            num4 = (num2 * 500) / 10
            document.getElementById("result").innerHTML = num3 + num4 + ' puntos.';
        }
    }
</script>
--> <!--
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <p> Hola mundo </p>
    </div>-->