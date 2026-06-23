@props([
    'action',
    'title' => 'Cargar estudiantes',
    'eyebrow' => 'Carga masiva',
    'description' => 'Sube un archivo .xlsx o .csv para procesar estudiantes.',
    'submitLabel' => 'Subir excel',
    'showDeleteOption' => false,
    'deleteLabel' => 'Borrar usuarios ausentes',
    'deleteHelp' => 'Elimina estudiantes que no esten dentro del archivo cargado.',
    'showPaymentLink' => false,
    'paymentHref' => null,
    'paymentLabel' => 'Editar pago individual',
])

@php
    $fileInputId = 'file-upload-' . substr(md5($action . $title), 0, 10);
    $deleteInputId = $fileInputId . '-delete';
    $safeSuccess = null;

    if ($message = session('success')) {
        $safeSuccess = trim(strip_tags(str_ireplace(['<br>', '<br/>', '<br />'], "\n", $message)));
    }
@endphp

<div class="import-page">
    <section class="import-hero" aria-label="Resumen de carga">
        <div class="import-hero-copy">
            <p class="import-eyebrow">{{ $eyebrow }}</p>
            <h2>{{ $title }}</h2>
            <p>{{ $description }}</p>
        </div>
        <div class="import-hero-meta" aria-label="Requisitos del archivo">
            <span class="import-meta-pill">XLSX / CSV</span>
            <span class="import-meta-pill">Max. 2 MB</span>
        </div>
    </section>

    @if ($safeSuccess)
        <div class="eus-alert eus-alert-success import-alert" role="status">
            <x-ui.icon name="check" :size="18" />
            <span>{!! nl2br(e($safeSuccess)) !!}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="eus-alert eus-alert-danger import-alert" role="alert">
            <x-ui.icon name="alert-triangle" :size="18" />
            <div>
                <strong>No se pudo procesar el archivo.</strong>
                <ul class="import-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <section class="import-panel" aria-label="Formulario de carga Excel">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="import-form-grid">
                <label for="{{ $fileInputId }}" class="import-dropzone">
                    <span class="import-dropzone-icon" aria-hidden="true">
                        <x-ui.icon name="upload" :size="30" />
                    </span>
                    <span class="import-dropzone-title">Seleccionar archivo Excel</span>
                    <span class="import-dropzone-copy">Tambien puedes arrastrarlo y soltarlo aqui.</span>
                    <span class="import-dropzone-hint">Formato .xlsx o .csv, maximo 2MB</span>
                    <input id="{{ $fileInputId }}" name="file" type="file" class="sr-only" accept=".xlsx,.csv">
                </label>

                <aside class="import-side-panel" aria-label="Opciones de importacion">
                    <div class="import-guidance">
                        <div class="import-guidance-icon" aria-hidden="true">
                            <x-ui.icon name="file-spreadsheet" :size="22" />
                        </div>
                        <div>
                            <h3>Antes de subir</h3>
                            <p>Verifica encabezados, correos y paralelos para evitar rechazos durante la importacion.</p>
                        </div>
                    </div>

                    @if($showDeleteOption)
                        <label for="{{ $deleteInputId }}" class="import-option import-option-danger">
                            <input id="{{ $deleteInputId }}" type="checkbox" name="borrar">
                            <span>
                                <strong>{{ $deleteLabel }}</strong>
                                <small>{{ $deleteHelp }}</small>
                            </span>
                        </label>
                    @else
                        <div class="import-option">
                            <x-ui.icon name="check" :size="18" />
                            <span>
                                <strong>Actualizacion segura</strong>
                                <small>Esta carga mantiene los registros no incluidos en el archivo.</small>
                            </span>
                        </div>
                    @endif
                </aside>
            </div>

            <div class="import-actions">
                @if($showPaymentLink && $paymentHref)
                    <a href="{{ $paymentHref }}" class="eus-btn eus-btn-secondary">
                        {{ $paymentLabel }}
                    </a>
                @endif

                <button type="submit" class="eus-btn eus-btn-primary import-submit">
                    <x-ui.icon name="upload" :size="17" />
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </section>
</div>
