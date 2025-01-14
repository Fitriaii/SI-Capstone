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
                        class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                            <!-- Icon yang berubah saat hover -->
                            <img :src="isActive('dashboard') ? '/images/dashboardAktif.png' : '/images/dashboardnonAktif.png'"
                                class="w-6 h-6 mr-4 transition-opacity duration-300 group-hover:opacity-0"
                                alt="Dashboard icon">
                            <img src="/images/dashboardAktif.png"
                                class="absolute w-6 h-6 mr-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                alt="Dashboard icon active">
                            <span class="text-sm font-bold">Dashboard</span>
                        </a>


                        <a href="{{ url('/data-kependudukan') }}"
                        :class="isActive('data-kependudukan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                        class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                            <!-- Icon yang berubah saat hover -->
                            <img :src="isActive('data-kependudukan') ? '/images/pendudukAktif.png' : '/images/penduduknonAktif.png'"
                                class="w-6 h-6 mr-4 transition-opacity duration-300 group-hover:opacity-0"
                                alt="Penduduk icon">
                            <img src="/images/pendudukAktif.png"
                                class="absolute w-6 h-6 mr-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                alt="Penduduk icon active">
                            <span class="text-sm font-bold">Keterangan Tempat</span>
                        </a>

                        {{-- Data Kondisi Rumah Link --}}
                        <a href="{{ url('/data-kondisi-rumah') }}"
                        :class="isActive('data-kondisi-rumah') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                        class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                            <!-- Icon yang berubah saat hover -->
                            <img :src="isActive('data-kondisi-rumah') ? '/images/homeAktif.png' : '/images/homenonAktif.png'"
                                class="w-6 h-6 mr-4 transition-opacity duration-300 group-hover:opacity-0"
                                alt="Rumah icon">
                            <img src="/images/homeAktif.png"
                                class="absolute w-6 h-6 mr-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                alt="Rumah icon active">
                            <span class="text-sm font-bold">Keterangan Perumahan</span>
                        </a>


                        {{-- Data Kepemilikan Aset Link --}}
                        <div x-data="{ openProgram: {{ in_array(Request::path(), ['programbantuan', 'aset', 'layanan']) ? 'true' : 'false' }} }">
                            <!-- Parent Button -->
                            <a href="#"
                            x-on:click="openProgram = !openProgram"
                            :class="isParentActive(['programbantuan', 'aset', 'layanan']) ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                            class="flex items-center p-3 mb-2 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                                <!-- Icon yang berubah saat hover -->
                                <img :src="isParentActive(['programbantuan', 'aset', 'layanan']) ? '/images/programAktif.png' : '/images/programnonAktif.png'"
                                    class="w-6 h-6 mr-4 transition-opacity duration-300 group-hover:opacity-0"
                                    alt="Icon">
                                <img src="/images/programAktif.png"
                                    class="absolute w-6 h-6 mr-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                    alt="Icon active">
                                <span class="text-sm font-bold">Program, Aset dan Layanan</span>
                            </a>


                            <!-- Dropdown Menu -->
                            <div x-show="openProgram" x-transition class="ml-8 space-y-2">
                                <a href="{{ url('/programbantuan') }}"
                                   :class="isActive('programbantuan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                                    <span class="text-sm font-bold">Program Bantuan</span>
                                </a>
                                <a href="{{ url('/aset') }}"
                                   :class="isActive('aset') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                                    <span class="text-sm font-bold">Aset Bergerak/Tidak Bergerak</span>
                                </a>
                                <a href="{{ url('/layanan') }}"
                                   :class="isActive('layanan') ? 'bg-blue-300 text-blue-950' : 'text-textlp'"
                                   class="flex items-center p-3 transition-colors rounded-lg group hover:bg-blue-200 hover:text-blue-950">
                                    <span class="text-sm font-bold">Layanan</span>
                                </a>
                            </div>
                        </div>


                        <footer class="flex-grow p-4 border-t border-blue-300">
                            <a href="{{ url('/logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            :class="isActive('logout') ? 'bg-red-300 text-red-950' : 'text-textlp'"
                            class="flex items-center p-3 transition-colors rounded-lg group hover:bg-red-200 hover:text-red-950">
                                <!-- Icon yang berubah saat hover -->
                                <img :src="isActive('logout') ? '/images/exitAktif.png' : '/images/exitnonAktif.png'"
                                    class="w-6 h-6 mr-4 transition-opacity duration-300 group-hover:opacity-0"
                                    alt="Logout icon">
                                <img src="/images/exitAktif.png"
                                    class="absolute w-6 h-6 mr-4 transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                                    alt="Logout icon active">
                                <span class="text-sm font-bold ">Keluar</span>
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
