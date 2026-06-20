<div class="info-shell">
    <section class="info-hero" aria-labelledby="info-title">
        <img class="info-image w-full h-full" src="{{ Storage::url('img/informativo.jpg') }}" alt="Estudiantes revisando informacion academica">

        <div class="info-content">
            <p class="info-eyebrow">Orientaci&oacute;n acad&eacute;mica</p>
            <h2 id="info-title" class="info-title">C&oacute;mo conocer mi puntaje de postulaci&oacute;n</h2>

            <p>
                La prueba <strong>Transformar</strong> mide aptitudes de concentraci&oacute;n y razonamiento verbal, num&eacute;rico y l&oacute;gico.
            </p>
            <p>
                La nota del examen Transformar equivale al 50% del porcentaje total para acceder a un cupo en la Educaci&oacute;n Superior.
                El otro 50% depende del r&eacute;cord acad&eacute;mico de la formaci&oacute;n b&aacute;sica superior y bachillerato.
            </p>

            <div class="info-callout">
                El puntaje para postulaci&oacute;n est&aacute; compuesto por estos componentes:
            </div>

            <p><strong>a) Puntaje de evaluaci&oacute;n (50%).</strong></p>
            <p>
                Los resultados son medidos en par&aacute;metros cuantificables, medibles y objetivos, con una nota m&aacute;xima de 1000/1000.
            </p>

            <p><strong>b) Antecedentes acad&eacute;micos (50%).</strong></p>
            <p>
                Para obtener el t&iacute;tulo correspondiente al cierre del ciclo escolar del bachillerato se requiere acreditar una nota
                de 7 puntos sobre 10 bajo estas ponderaciones:
            </p>
            <ul class="info-list">
                <li>30%: promedio obtenido en B&aacute;sica Superior, desde octavo hasta d&eacute;cimo a&ntilde;o de EGB.</li>
                <li>40%: promedio de los 3 a&ntilde;os de bachillerato.</li>
                <li>30%: nota del examen de grado Ser Bachiller.</li>
                <li>10%: nota del Programa de Participaci&oacute;n Estudiantil.</li>
            </ul>

            <p><strong>c) Puntaje adicional por acciones afirmativas.</strong></p>
            <p>
                Si el postulante cumple condiciones de acciones afirmativas, puede recibir puntaje adicional que se suma al
                puntaje de evaluaci&oacute;n y antecedentes acad&eacute;micos.
            </p>
        </div>
    </section>

    <section class="info-actions-grid" aria-label="Herramientas de consulta">
        <a href="{{ route('calculadora') }}" class="info-action">
            <span class="info-action-icon" aria-hidden="true">
                <x-ui.icon name="calculator" />
            </span>
            <span>
                <span class="info-action-title">Calculadora</span>
                <span class="info-action-copy">Calcula y revisa escenarios de puntaje desde la plataforma.</span>
            </span>
        </a>

        <a href="{{ route('oferta-academica') }}" class="info-action">
            <span class="info-action-icon" aria-hidden="true">
                <x-ui.icon name="book" />
            </span>
            <span>
                <span class="info-action-title">Puntajes referenciales</span>
                <span class="info-action-copy">Explora carreras y referencias disponibles para orientar tu postulaci&oacute;n.</span>
            </span>
        </a>
    </section>
</div>
