<x-auth.shell compact>
    <div class="eus-card auth-compact-card">
        <div class="eus-card-body auth-compact-body">
            <div>
                {{ $logo }}
            </div>

            <div class="mt-6">
                {{ $slot }}
            </div>

            <div class="auth-help">
                <p>
                    En caso de que tenga alg&uacute;n inconveniente para ingresar, puede escribir un correo a
                    <a href="mailto:soporte@semilladigital.com">soporte@semilladigital.com</a>.
                </p>
            </div>
        </div>
    </div>
</x-auth.shell>
