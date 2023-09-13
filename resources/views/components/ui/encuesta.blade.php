 <!-- This example requires Tailwind CSS v2.0+ -->
 <div id="modal_encuesta" class="fixed z-10 inset-0 overflow-y-auto">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
     <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
     <div class="fixed inset-0 transition-opacity" aria-hidden="true">
       <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
     </div>

     <!-- This element is to trick the browser into centering the modal contents. -->
     <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
     <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
     @php
     $subject_id = -1;
     $today = new \Carbon\Carbon();
     //$today->day=28;
     $img_url = '';
     if($today->dayOfWeek == \Carbon\Carbon::TUESDAY){
     $img_url = 'img/dm.png';
     // mate
     $subject_id = 1;
     }
     elseif($today->dayOfWeek == \Carbon\Carbon::WEDNESDAY){
     $img_url = 'img/dl.png';
     // lengua
     $subject_id = 2;
     }
     elseif($today->dayOfWeek == \Carbon\Carbon::THURSDAY){
     $img_url = 'img/ds.png';
     // naturales
     $subject_id = 3;
     }
     elseif($today->dayOfWeek == \Carbon\Carbon::FRIDAY){
     $img_url = 'img/dc.png';
     // sociales
     $subject_id = 4;
     }
     elseif($today->dayOfWeek == \Carbon\Carbon::SATURDAY){
     $img_url = 'img/dov.png';
     // orientacion
     $subject_id = 5;
     }
     else
     $img_url = 'img/dm.png';
     @endphp
     <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg md:max-w-4xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
       <div class="">
         <img class="w-full" src="{{ Storage::url($img_url) }}" />
       </div>
       <div class="flex items-center min-h-screen bg-gray-50 dark:bg-gray-900 md:mx-10">
         <div class="container mx-auto">
           <div class="mx-auto bg-white p-5 rounded-md shadow-sm">
             <!-- <div class="text-center">
               <h1 class="my-3 text-2xl font-semibold text-gray-700 dark:text-gray-200">Encuesta de Satisfacción Eus3 Preuniversitario</h1>
             </div> -->
             <div class="m-7">
               <form id="form">
                 @csrf
                 <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                 <input type="hidden" value="{{ $subject_id }}" name="subject_id">

                 <!-- separador -->
                 <div class="bg-orange-300 max-w-sm md:max-w-lg h-2 my-6"></div>

                 <p class="text-xl font-bold">1 - Califique su satisfacción con respecto a las clases recibidas</p>
                 <div class="flex justify-between flex-wrap">
                   <div class="flex flex-col items-center">
                     Malo
                     <img src="{{ Storage::url('img/s1.jpg') }}" alt="Malo" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="score_mate" value="malo" required>
                   </div>

                   <div class="flex flex-col items-center">
                     Regular
                     <img src="{{ Storage::url('img/s2.jpg') }}" alt="Regular" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="score_mate" value="regular">
                   </div>

                   <div class="flex flex-col items-center">
                     Bueno
                     <img src="{{ Storage::url('img/s3.jpg') }}" alt="Bueno" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="score_mate" value="bueno">
                   </div>

                   <div class="flex flex-col items-center">
                     Muy Bueno
                     <img src="{{ Storage::url('img/s4.jpg') }}" alt="Muy Bueno" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="score_mate" value="muy bueno">
                   </div>

                   <div class="flex flex-col items-center">
                     Excelente
                     <img src="{{ Storage::url('img/s5.jpg') }}" alt="Excelente" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="score_mate" value="excelente">
                   </div>
                 </div>

                 <!-- separador -->
                 <div class="bg-orange-300 max-w-sm md:max-w-lg h-2 my-6"></div>

                 <p class="text-xl font-bold">2 - ¿Con qué frecuencia participa en las clases impartidas?</p>
                 <input type="radio" id="age1" name="frecuencia" value="siempre" required>
                 <label for="age1">Siempre</label><br>
                 <input type="radio" id="age2" name="frecuencia" value="casi siempre">
                 <label for="age2">Casi Siempre</label><br>
                 <input type="radio" id="age3" name="frecuencia" value="nunca">
                 <label for="age3">Nunca</label>

                 <!-- separador -->
                 <div class="bg-orange-300 max-w-sm md:max-w-lg h-2 my-6"></div>

                 <p class="text-xl font-bold">3 - ¿Cómo fue la atención para resolver sus dudas?</p>
                 <input type="radio" id="age1" name="atencion" value="Rápida, pues contestaron al momento" required>
                 <label for="age1"> Rápida, pues contestaron al momento</label><br>
                 <input type="radio" id="age2" name="atencion" value="Tuve que esperar, pero contestaron">
                 <label for="age2"> Tuve que esperar, pero contestaron</label><br>
                 <input type="radio" id="age3" name="atencion" value="Lenta, pues no había asesoramiento">
                 <label for="age3">Lenta, pues no había asesoramiento</label><br>
                 <input type="radio" id="age3" name="atencion" value="Insuficiente, pues no recibí respuesta">
                 <label for="age3">Insuficiente, pues no recibí respuesta</label>

                 <!-- separador -->
                 <div class="bg-orange-300 max-w-sm md:max-w-lg h-2 my-6"></div>

                 <p class="text-xl font-bold">4 - De forma general, califique a Eus3 Preuniversitario</p>


                 <div class="flex justify-between flex-wrap">
                   <div class="flex flex-col items-center">
                     <img src="{{ Storage::url('img/s1.jpg') }}" alt="Los Angeles" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="satisfaccion" value="malo" required>
                     Malo
                   </div>

                   <div class="flex flex-col items-center">
                     <img src="{{ Storage::url('img/s2.jpg') }}" alt="Los Angeles" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="satisfaccion" value="regular">
                     Regular
                   </div>

                   <div class="flex flex-col items-center">
                     <img src="{{ Storage::url('img/s3.jpg') }}" alt="Los Angeles" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="satisfaccion" value="bueno">
                     Bueno
                   </div>

                   <div class="flex flex-col items-center">
                     <img src="{{ Storage::url('img/s4.jpg') }}" alt="Los Angeles" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="satisfaccion" value="muy bueno">
                     Muy Bueno
                   </div>

                   <div class="flex flex-col items-center">
                     <img src="{{ Storage::url('img/s5.jpg') }}" alt="Los Angeles" class="sm:w-2/4 w-16">
                     <input type="radio" id="male" name="satisfaccion" value="excelente">
                     Excelente
                   </div>
                 </div>

                 <!-- separador -->
                 <div class="bg-orange-300 max-w-sm md:max-w-lg h-2 my-6"></div>

                 <p class="text-xl font-bold">5 - Recomendaciones para mejorar su experiencia en Eus3 Preuniversitario </p>
                 <div class="mb-6">
                   <textarea rows="5" name="recomendacion" id="message" placeholder="Su recomendación" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-gray-900 dark:focus:border-gray-500"></textarea>
                 </div>
                 <div class="mb-6">
                   <button type="submit" class="w-full px-3 py-4 text-white bg-indigo-500 rounded-md focus:bg-indigo-600 focus:outline-none">Enviar Encuesta</button>
                 </div>
                 <p class="text-base text-center text-gray-400" id="result">
                 </p>
               </form>
             </div>
           </div>
         </div>
       </div>

       <script>
         const form = document.getElementById('form');
         const result = document.getElementById('result');

         form.addEventListener('submit', function(e) {
           const formData = new FormData(form);
           e.preventDefault();
           var object = {};
           formData.forEach((value, key) => {
             object[key] = value
           });
           var json = JSON.stringify(object);
           console.log(json);

           result.innerHTML = "Please wait..."

           fetch('{{route("encuesta")}}', {
               method: 'POST',
               headers: {
                 'Content-Type': 'application/json',
                 'Accept': 'application/json'
               },
               body: json
             })
             .then(async (response) => {
               let json = await response.json();
               if (response.status == 200) {
                 result.innerHTML = json.message;
                 result.classList.remove('text-gray-500');
                 result.classList.add('text-green-500');
               } else {
                 console.log(response);
                 result.innerHTML = json.message;
                 result.classList.remove('text-gray-500');
                 result.classList.add('text-red-500');
               }
               document.querySelector('#modal_encuesta').style.display = "none";
             })
             .catch(error => {
               console.log(error);
               result.innerHTML = "Something went wrong!";
             })
             .then(function() {
               form.reset();
               setTimeout(() => {
                 result.style.display = "none";
               }, 5000);
             });
         })
       </script>
     </div>
   </div>
 </div>