<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Semilla Digital')
<img src="https://semilladigital.com/brand/semilla-logo-horizontal.svg" class="logo" alt="Semilla Digital">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
