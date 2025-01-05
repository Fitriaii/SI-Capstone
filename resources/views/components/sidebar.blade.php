<div class="flex">
    {{-- Sidebar --}}
    <div class="fixed inset-y-0 left-0 z-30 w-64 text-white shadow-lg bg-blue-950"
        x-data="{ open: true, activeRoute: '{{ Request::path() }}' }"
        :class="{ 'translate-x-0': open, '-translate-x-full': !open }">

        {{-- Sidebar Navigation --}}
        <nav class="px-4 mt-8 space-y-2">
            {{-- Sidebar --}}
            <div class="fixed inset-y-0 left-0 w-64 text-white transition-transform duration-300 transform bg-blue-950"
                 x-data="{
                     open: true,
                     activeRoute: '{{ Request::path() }}',
                     isActive(route) {
                         // Periksa apakah rute saat ini cocok atau merupakan anak dari rute yang diberikan
                         return this.activeRoute === route || this.activeRoute.startsWith(route + '/');
                     },
                     isParentActive(routes) {
                         // Periksa apakah salah satu rute dalam array adalah induk dari rute saat ini
                         return routes.some(route =>
                             this.activeRoute === route || this.activeRoute.startsWith(route + '/')
                         );
                     }
                 }"
                 :class="{'translate-x-0': open, '-translate-x-full': !open}">

                {{-- Sidebar Navigation --}}
                <nav class="mt-8">

                    <div class="px-4 space-y-2">
                        <!-- Header -->
                        <div class="text-center">
                            <h2 class="mt-2 text-lg font-semibold">Desa Sendangarum</h2>
                        </div>

                        <!-- User Panel -->
                        <div class="flex items-center justify-center px-8 py-4 text-center border-b border-blue-300 user-panel">
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-white hover:text-blue-200">
                                Halooo!! {{ Auth::user()->name }}
                            </a>
                        </div>

                        {{-- Dashboard Link --}}
                        <a href="{{ url('/dashboard') }}"
                           :class="isActive('dashboard') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                           class="flex items-center p-3 transition-colors rounded-lg group">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-xs font-bold group-hover:text-blue-500">Dashboard</span>
                        </a>

                        {{-- Data Kependudukan Link --}}
                        <a href="{{ url('/data-kependudukan') }}"
                           :class="isActive('data-kependudukan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                           class="flex items-center p-3 transition-colors rounded-lg group">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                            </svg>
                            <span class="text-xs font-bold group-hover:text-blue-500">Data Kependudukan</span>
                        </a>

                        {{-- Data Kondisi Rumah Link --}}
                        <a href="{{ url('/data-kondisi-rumah') }}"
                           :class="isActive('data-kondisi-rumah') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                           class="flex items-center p-3 transition-colors rounded-lg group">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                                <path fill-rule="evenodd" d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                            </svg>
                            <span class="text-xs font-bold group-hover:text-blue-500">Data Kondisi Rumah</span>
                        </a>

                        {{-- Data Kepemilikan Aset Link --}}
                        <div x-data="{ openProgram: {{ in_array(Request::path(), ['programbantuan', 'aset', 'layanan']) ? 'true' : 'false' }} }">
                            <a href="#"
                                x-on:click="openProgram = !openProgram"
                                :class="isParentActive(['programbantuan', 'aset', 'layanan']) ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                class="flex items-center p-3 mb-2 transition-colors rounded-lg group">

                                <!-- Ikon dengan warna dinamis -->
                                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    :class="isParentActive(['programbantuan', 'aset', 'layanan']) ? 'bg-blue-300 text-blue-950' : 'text-textlp'">
                                    <path fill-rule="evenodd" d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z" />
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-.696.178c-.682.24-1.286.68-1.765 1.15-.505.495-.989 1.166-1.134 1.918a.75.75 0 1 0 1.465.36c.097-.396.362-.81.723-1.164.36-.353.804-.64 1.217-.777.35-.123.713-.157 1.027-.086v3.184a2.96 2.96 0 0 0-1.221.544c-.728.577-1.031 1.367-1.031 2.033 0 .666.303 1.456 1.031 2.033.38.301.856.532 1.221.543v.816a.75.75 0 0 0 1.5 0v-.816a3.837 3.837 0 0 0 1.027-.086c.413-.137.857-.424 1.217-.777.361-.353.626-.768.723-1.164a.75.75 0 0 0-1.465-.36 2.09 2.09 0 0 1-.435.7 2.36 2.36 0 0 1-.778.507 2.246 2.246 0 0 0-.696.177v-3.184a2.96 2.96 0 0 0 1.221-.544c.728-.577 1.031-1.367 1.031-2.033 0-.666-.303-1.456-1.031-2.033a2.96 2.96 0 0 0-1.221-.544V6Z" />
                                </svg>

                                <span class="text-xs font-bold group-hover:text-blue-500">Data Kepemilikan Aset</span>
                            </a>

                            <!-- Dropdown -->
                            <div x-show="openProgram" x-transition class="ml-8 space-y-2">
                                <a href="{{ url('/programbantuan') }}"
                                   :class="isActive('programbantuan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group">
                                    <span class="text-xs font-bold group-hover:text-blue-500">Program Bantuan</span>
                                </a>
                                <a href="{{ url('/aset') }}"
                                   :class="isActive('aset') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group">
                                    <span class="text-xs font-bold group-hover:text-blue-500">Aset Bergerak/Tidak Bergerak</span>
                                </a>
                                <a href="{{ url('/layanan') }}"
                                   :class="isActive('layanan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group">
                                    <span class="text-xs font-bold group-hover:text-blue-500">Layanan</span>
                                </a>
                            </div>
                        </div>

                        <footer class="flex-grow p-4 border-t border-blue-300">
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center p-3 text-white transition-colors rounded-lg group">
                                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xs font-bold group-hover:text-blue-500">Logout</span>
                            </a>

                            <!-- Form logout -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                @csrf
                            </form>
                        </footer>

                    </div>
                </nav>
            </div>
        </nav>

    </div>
</div>
