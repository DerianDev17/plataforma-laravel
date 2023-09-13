<div>
<label class="select-none container block relative cursor-pointer pl-8">
  <input id="{{$sesion->date ?? ''}}" class="absolute opacity-0 left-0 top-0 cursor-pointer" type="checkbox" checked="checked">
  {{$sesion->date ?? ''}} {{$sesion->time ?? ''}}
  <span class="h-6 w-6 checkmark absolute top-0 left-0 bg-gray-400"></span>
</label>
</div>
