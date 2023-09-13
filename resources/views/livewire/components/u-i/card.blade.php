<div>
    <div class="rounded-lg overflow-hidden">
        <div class="relative overflow-hidden pb-60">
            <img class="absolute h-full w-full object-cover object-center" src="{{$imgurl}}" alt="" />
        </div>
        <div class="relative {{$colorurl}}">
            <div class="py-10 px-8">
                <h3 class="text-2xl font-bold">{{$titulo}}</h3>
                <div class="mt-5 flex flex-col">
                    @if (count($links) > 1)
                    @foreach ($links as $i => $link)
                    <a target="_blank" href="{{$link}}" class="flex items-center">
                        <p class="mr-4">Parte #{{$i+1}}</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14.125" height="13.358" viewBox="0 0 14.125 13.358">
                            <g transform="translate(-3 -3.293)">
                                <path id="Path_7" data-name="Path 7" d="M14.189,10.739H3V9.2H14.189L9.361,4.378l1.085-1.085,6.679,6.679-6.679,6.679L9.361,15.566Z" fill="#1d1d1d" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </a>
                    @endforeach
                    @elseif (count($links) == 1)
                    <a target="_blank" href="{{$links[0]}}" class="flex items-center">
                        <p class="mr-4">Ver</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14.125" height="13.358" viewBox="0 0 14.125 13.358">
                            <g transform="translate(-3 -3.293)">
                                <path id="Path_7" data-name="Path 7" d="M14.189,10.739H3V9.2H14.189L9.361,4.378l1.085-1.085,6.679,6.679-6.679,6.679L9.361,15.566Z" fill="#1d1d1d" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>