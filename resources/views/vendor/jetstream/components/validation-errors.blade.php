@if ($errors->any())
    <div {{ $attributes }}>
        <div class="validation-errors-title">Por favor, aseg&uacute;rese de llenar correctamente el formulario.</div>

        <ul class="validation-errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
