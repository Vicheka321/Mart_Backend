<nav class="w-full">
    <!-- left -->
    <div class="flex items-center justify-between px-6 pt-3 bg-white dark:bg-gray-800 transition">

        <div class="flex items-center gap-5">
            <div class="w-60 flex items-center gap-2">
                <img src="{{ asset('images/icons/logo.png') }}" class="w-9">
                <h1 class="text-lg font-semibold text-indigo-600">Darita Mart</h1>
            </div>

            <div class="relative w-80">
                <input type="text" placeholder="Search..." class="w-full h-10 pl-10 pr-4 rounded-xl border border-gray-300 
                focus:outline-none focus:ring-2 focus:ring-indigo-400
                hover:border-indigo-400 transition text-sm placeholder-gray-400
                dark:bg-gray-800 dark:border-gray-600 dark:text-white">

                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" fill="currentColor">
                    <path
                        d="m795.904 750.72 124.992 124.928a32 32 0 0 1-45.248 45.248L750.656 795.904a416 416 0 1 1 45.248-45.248zM480 832a352 352 0 1 0 0-704 352 352 0 0 0 0 704">
                    </path>
                </svg>
            </div>
        </div>

        <!-- right -->
        <div class="flex items-center gap-5">

            <!-- DarkLightModeToggle -->
            <button type="button" onclick="toggleDarkMode()"
                class="cursor-pointer hover:scale-105 transition text-gray-400 dark:text-gray-300 hover:text-indigo-600">
                <svg x-show="!dark" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                    viewBox="0 0 24 24" fill="currentColor">
                    <rect fill="none" height="24" width="24"></rect>
                    <path
                        d="M9.37,5.51C9.19,6.15,9.1,6.82,9.1,7.5c0,4.08,3.32,7.4,7.4,7.4c0.68,0,1.35-0.09,1.99-0.27C17.45,17.19,14.93,19,12,19 c-3.86,0-7-3.14-7-7C5,9.07,6.81,6.55,9.37,5.51z M12,3c-4.97,0-9,4.03-9,9s4.03,9,9,9s9-4.03,9-9c0-0.46-0.04-0.92-0.1-1.36 c-0.98,1.37-2.58,2.26-4.4,2.26c-2.98,0-5.4-2.42-5.4-5.4c0-1.81,0.89-3.42,2.26-4.4C12.92,3.04,12.46,3,12,3L12,3z">
                    </path>
                </svg>
            </button>

            <!-- Notification -->
            <div class="relative">
                <button class="cursor-pointer hover:scale-105 transition text-gray-600 hover:text-indigo-600">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none"
                        stroke="currentColor">
                        <path
                            d="M427.68,351.43C402,320,383.87,304,383.87,217.35,383.87,138,343.35,109.73,310,96c-4.43-1.82-8.6-6-9.95-10.55C294.2,65.54,277.8,48,256,48S217.79,65.55,212,85.47c-1.35,4.6-5.52,8.71-9.95,10.53-33.39,13.75-73.87,41.92-73.87,121.35C128.13,304,110,320,84.32,351.43,73.68,364.45,83,384,101.61,384H410.49C429,384,438.26,364.39,427.68,351.43Z"
                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                        </path>
                        <path d="M320,384v16a64,64,0,0,1-128,0V384"
                            style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px">
                        </path>
                    </svg>
                </button>
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
            </div>


            <div x-data="{ open: false }" class="relative">

                <!-- Avatar -->
                <img @click="open = !open" src="https://i.pravatar.cc/40"
                    class="w-9 h-9 rounded-full cursor-pointer border-2 border-gray-200">

                <!-- Dropdown -->
                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl p-4 space-y-2 dark:bg-gray-900">

                    <!-- My Profile -->
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400 hover:translate-x-1 transition-all duration-200 transition">

                        <!-- SVG -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <span>My Profile</span>
                    </a>

                    <!-- My Account -->
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400 hover:translate-x-1 transition-all duration-200 transition">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m0 0l4-4m-4 4l4 4" />
                        </svg>

                        <span>My Account</span>
                    </a>

                    <!-- My Task -->
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400 hover:translate-x-1 transition-all duration-200 transition">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 12h6M9 19h6" />
                        </svg>

                        <span>My Task</span>
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                    <!-- Logout -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full py-2 rounded-xl border border-indigo-500 text-indigo-600 
                hover:bg-indigo-50 dark:hover:bg-gray-400 transition font-medium">
                            Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>


    </div>
</nav>


<script>

    // scriptDailogAlert
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // scriptChangeTheme
    function toggleDarkMode() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }
</script>

