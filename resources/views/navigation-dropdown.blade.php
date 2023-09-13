<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                @php
                use Illuminate\Support\Facades\Auth;
                use App\Http\Livewire\Meetings\Show;

                $metting=new Show();
                $user = Auth::user();
                $pagado = $metting->check_payment($user);
                @endphp

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('informacion') }}" :active="request()->routeIs('informacion')">
                        {{ __('Panel Informativo') }}
                    </x-jet-nav-link>

                    <!-- <x-jet-nav-link href="{{ route('calculadora') }}" :active="request()->routeIs('calculadora')">
                        {{ __('Nota de postulación') }}
                    </x-jet-nav-link> -->

                    <!-- <x-jet-nav-link href="{{ route('oferta-academica') }}" :active="request()->routeIs('oferta-academica')">
                        {{ __('Carreras') }}
                    </x-jet-nav-link> -->

                    @if ($pagado === true || $user->hasRole('superadmin'))
                    <x-jet-nav-link href="{{ route('dashboard-meetings') }}" :active="request()->routeIs('dashboard-meetings')">
                        {{ __('Reuniones') }}
                    </x-jet-nav-link>
                    @endif
                    @can ('edit_users')
                    <!-- <x-jet-nav-link href="{{ route('dashboard-users') }}" :active="request()->routeIs('users-meetings')">
                        {{ __('Usuarios') }}
                    </x-jet-nav-link> -->
                    <x-jet-nav-link href="{{ route('dashboard-students') }}" :active="request()->routeIs('dashboard-students')">
                        {{ __('Estudiantes') }}
                    </x-jet-nav-link>
                    <!-- <x-jet-nav-link href="{{ route('permisos.index') }}" :active="request()->routeIs('permisos.index')">
                        {{ __('Permisos') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link href="{{ route('asignacion.index') }}" :active="request()->routeIs('asignacion.index')">
                        {{ __('Roles') }}
                    </x-jet-nav-link> -->
                    <!-- <x-jet-nav-link href="{{ route('excel.upload') }}" :active="request()->routeIs('excel.upload')">
                        {{ __('Subir Base') }}
                    </x-jet-nav-link> -->
                    <x-jet-nav-link href="{{ route('actualizar_base.get') }}" :active="request()->routeIs('actualizar_base.get')">
                        {{ __('Actualizar Base') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('get.register_participants') }}" :active="request()->routeIs('get.register_participants')">
                        {{ __('Regist. Zoom') }}
                    </x-jet-nav-link>
                    {{--
                    <x-jet-nav-link href="{{ route('get.register_participants') }}" :active="request()->routeIs('pdfview',['download'=>'pdf'])">
                        {{ __('Registrar Zoom') }}
                    </x-jet-nav-link>
                    --}}
                    <x-jet-nav-link href="{{ route('asistencias-crud') }}" :active="request()->routeIs('asistencias-crud')">
                        Asistencias
                    </x-jet-nav-link>
                    @endcan
                    @can ('crud_drives')
                    <x-jet-nav-link href="{{ route('drives') }}" :active="request()->routeIs('drives')">
                        Drives
                    </x-jet-nav-link>
                    @endcan
                    @can ('read_course_programs')
                    @if (Auth::user()->student_group_id != 3)
                    <x-jet-nav-link href="{{ route('course_programm') }}" :active="request()->routeIs('drives')">
                        Transformar
                    </x-jet-nav-link>
                    @endif
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </button>
                        @else
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        @endif
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Administrar cuenta') }}
                        </div>

                        <x-jet-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Perfil') }}
                        </x-jet-dropdown-link>

                        <!-- <x-jet-dropdown-link href="{{ route('pdfview',['download'=>'pdf']) }}">
                            {{ __('Certificado') }}
                        </x-jet-dropdown-link> -->

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                            {{ __('API Tokens') }}
                        </x-jet-dropdown-link>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <!-- Team Management -->
                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Team') }}
                        </div>

                        <!-- Team Settings -->
                        <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                            {{ __('Team Settings') }}
                        </x-jet-dropdown-link>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-dropdown-link href="{{ route('teams.create') }}">
                            {{ __('Create New Team') }}
                        </x-jet-dropdown-link>
                        @endcan

                        <div class="border-t border-gray-100"></div>

                        <!-- Team Switcher -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" />
                        @endforeach

                        <div class="border-t border-gray-100"></div>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-jet-dropdown-link>
                        </form>
                    </x-slot>
                </x-jet-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Perfil') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                    {{ __('API Tokens') }}
                </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Salir') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <div class="border-t border-gray-200"></div>

                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Manage Team') }}
                </div>

                <!-- Team Settings -->
                <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                    {{ __('Team Settings') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                    {{ __('Create New Team') }}
                </x-jet-responsive-nav-link>

                <div class="border-t border-gray-200"></div>

                <!-- Team Switcher -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Switch Teams') }}
                </div>

                @foreach (Auth::user()->allTeams() as $team)
                <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>
<!-- Codigo del chatbot 
<script type="text/javascript">
    var vsid = "kc22227ac5ae748";
    (function() { 
        var vsjs = document.createElement('script'); vsjs.type = 'text/javascript'; vsjs.async = true; vsjs.setAttribute('defer', 'defer');
        vsjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'www.leadchatbot.com/vsa/chat.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(vsjs, s);
    })();
</script>-->