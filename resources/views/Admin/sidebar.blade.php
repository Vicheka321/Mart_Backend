<aside class="w-72 h-full bg-white dark:bg-gray-800 p-4 overflow-auto ">

    <div class="space-y-4 ">

        <!-- Dashboard -->

        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider pt-6">Dashboards</p>
        <a href="{{ route('admin.dashboard') }}" class="h-10 menu-item group flex items-center gap-3 p-3 rounded-lg 
{{ request()->routeIs('admin.dashboard')
    ? 'bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 hover:text-indigo-600 dark:hover:text-indigo-300' 
}} 
hover:translate-x-1 transition-all duration-200 transition">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" color="currentColor">
                <path d="M18 2V10M22 6L14 6" stroke="currentColor"></path>
                <path
                    d="M2 6C2 4.59987 2 3.8998 2.27248 3.36502C2.51217 2.89462 2.89462 2.51217 3.36502 2.27248C3.8998 2 4.59987 2 6 2C7.40013 2 8.1002 2 8.63498 2.27248C9.10538 2.51217 9.48783 2.89462 9.72752 3.36502C10 3.8998 10 4.59987 10 6C10 7.40013 10 8.1002 9.72752 8.63498C9.48783 9.10538 9.10538 9.48783 8.63498 9.72752C8.1002 10 7.40013 10 6 10C4.59987 10 3.8998 10 3.36502 9.72752C2.89462 9.48783 2.51217 9.10538 2.27248 8.63498C2 8.1002 2 7.40013 2 6Z"
                    stroke="currentColor"></path>
                <path
                    d="M2 18C2 16.5999 2 15.8998 2.27248 15.365C2.51217 14.8946 2.89462 14.5122 3.36502 14.2725C3.8998 14 4.59987 14 6 14C7.40013 14 8.1002 14 8.63498 14.2725C9.10538 14.5122 9.48783 14.8946 9.72752 15.365C10 15.8998 10 16.5999 10 18C10 19.4001 10 20.1002 9.72752 20.635C9.48783 21.1054 9.10538 21.4878 8.63498 21.7275C8.1002 22 7.40013 22 6 22C4.59987 22 3.8998 22 3.36502 21.7275C2.89462 21.4878 2.51217 21.1054 2.27248 20.635C2 20.1002 2 19.4001 2 18Z"
                    stroke="currentColor"></path>
                <path
                    d="M14 18C14 16.5999 14 15.8998 14.2725 15.365C14.5122 14.8946 14.8946 14.5122 15.365 14.2725C15.8998 14 16.5999 14 18 14C19.4001 14 20.1002 14 20.635 14.2725C21.1054 14.5122 21.4878 14.8946 21.7275 15.365C22 15.8998 22 16.5999 22 18C22 19.4001 22 20.1002 21.7275 20.635C21.4878 21.1054 21.1054 21.4878 20.635 21.7275C20.1002 22 19.4001 22 18 22C16.5999 22 15.8998 22 15.365 21.7275C14.8946 21.4878 14.5122 21.1054 14.2725 20.635C14 20.1002 14 19.4001 14 18Z"
                    stroke="currentColor"></path>
            </svg>
            <span>Dashboard</span>
        </a>


        <!-- Products -->
        <div x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('brands.*') ? 'true' : 'false' }} }"
            class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M7,2H3A1,1,0,0,0,2,3V21a1,1,0,0,0,1,1H7a1,1,0,0,0,1-1V3A1,1,0,0,0,7,2ZM5,21a2,2,0,1,1,2-2A2,2,0,0,1,5,21Zm2-9H3V3H7ZM6,19a1,1,0,1,1-1-1A1,1,0,0,1,6,19ZM14,2H10A1,1,0,0,0,9,3V21a1,1,0,0,0,1,1h4a1,1,0,0,0,1-1V3A1,1,0,0,0,14,2ZM12,21a2,2,0,1,1,2-2A2,2,0,0,1,12,21Zm2-9H10V3h4Zm-1,7a1,1,0,1,1-1-1A1,1,0,0,1,13,19ZM21,2H17a1,1,0,0,0-1,1V21a1,1,0,0,0,1,1h4a1,1,0,0,0,1-1V3A1,1,0,0,0,21,2ZM19,21a2,2,0,1,1,2-2A2,2,0,0,1,19,21Zm2-9H17V3h4Zm-1,7a1,1,0,1,1-1-1A1,1,0,0,1,20,19Z">
                        </path>
                    </svg>

                    <span>Products</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="{{ route('products.index') }}" class="block 
{{ request()->routeIs('products.*')
    ? 'text-indigo-600 dark:text-indigo-400'
    : 'text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400' 
}} 
hover:translate-x-1 transition-all duration-200">
                    All Products
                </a>
                <a href="{{ route('categories.index') }}" class="block 
{{ request()->routeIs('categories.*')
    ? 'text-indigo-600 dark:text-indigo-400'
    : 'text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400' 
}} 
hover:translate-x-1 transition-all duration-200">
                    Categories
                </a>
                <a href="{{ route('brands.index') }}" class="block
{{ request()->routeIs('brands.*')
    ? 'text-indigo-600 dark:text-indigo-400'
    : 'text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400' 
}} 
hover:translate-x-1 transition-all duration-200">
                    Brands
                </a>

            </div>

        </div>

        <!-- Orders -->
        <a href="{{ route('orders.index') }}" class="h-10 menu-item group flex items-center gap-3 p-3 rounded-lg 
{{ request()->routeIs('orders.*')
    ? 'bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 hover:text-indigo-600 dark:hover:text-indigo-300' 
}} 
hover:translate-x-1 transition-all duration-200 transition">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M13.28 4.78a.75.75 0 0 0-1.06-1.06l-2.97 2.97-1.22-1.22a.75.75 0 0 0-1.06 1.06l1.75 1.75a.75.75 0 0 0 1.06 0l3.5-3.5Z">
                </path>
                <path fill-rule="evenodd"
                    d="M4.86 6.883a.75.75 0 0 1 .632.852l-.336 2.265h2.484a1.25 1.25 0 0 1 1.185.855l.159.474a.25.25 0 0 0 .237.171h1.558a.25.25 0 0 0 .237-.17l.159-.475a1.25 1.25 0 0 1 1.185-.855h2.484l-.336-2.265a.75.75 0 1 1 1.484-.22l.413 2.792c.063.425.095.853.095 1.282v1.661a3.25 3.25 0 0 1-3.25 3.25h-6.5a3.25 3.25 0 0 1-3.25-3.25v-1.66c0-.43.032-.858.094-1.283l.414-2.792a.75.75 0 0 1 .852-.632Zm.14 4.706v-.089h2.46l.1.303a1.75 1.75 0 0 0 1.66 1.197h1.56a1.75 1.75 0 0 0 1.66-1.197l.1-.303h2.46v1.75a1.75 1.75 0 0 1-1.75 1.75h-6.5a1.75 1.75 0 0 1-1.75-1.75v-1.66Z">
                </path>
            </svg>
            <span>Orders</span>
        </a>

        <!-- Customers -->
        <div x-data="{ open: false }" class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor">
                        <defs></defs>
                        <path
                            d="m26,30h-2v-5c-.0033-2.7601-2.2399-4.9967-5-5h-6c-2.7601.0033-4.9967,2.2399-5,5v5h-2v-5c.0045-3.8641,3.1359-6.9955,7-7h6c3.8641.0045,6.9955,3.1359,7,7v5Z">
                        </path>
                        <path
                            d="m22,6v4c0,1.1025-.8972,2-2,2h-1c-.5522,0-1,.4478-1,1s.4478,1,1,1h1c2.2056,0,4-1.7944,4-4v-4h-2Z">
                        </path>
                        <path
                            d="m16,16c-3.8599,0-7-3.1401-7-7S12.1401,2,16,2c1.9885,0,3.8901.8503,5.2173,2.3329l-1.4902,1.334c-.9482-1.0593-2.3066-1.6669-3.7271-1.6669-2.7571,0-5,2.243-5,5s2.2429,5,5,5v2Z">
                        </path>
                        <rect id="_Transparent_Rectangle_" data-name="&amp;lt;Transparent Rectangle&amp;gt;"
                            class="cls-1" width="32" height="32" style="fill: none"></rect>
                    </svg>

                    <span>Customers</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="#" class="menu-item block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Customer
                    List</a>
                <a href="#" class="block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Reviews</a>
            </div>

        </div>

        <!-- Payments -->
        <div x-data="{ open: false }" class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" fill="none">
                        <path
                            d="M18.25 16.5C17.8358 16.5 17.5 16.8358 17.5 17.25C17.5 17.6642 17.8358 18 18.25 18H21.75C22.1642 18 22.5 17.6642 22.5 17.25C22.5 16.8358 22.1642 16.5 21.75 16.5H18.25ZM2.00391 8.75C2.00391 6.67893 3.68284 5 5.75391 5H22.2505C24.3216 5 26.0005 6.67893 26.0005 8.75V19.2501C26.0005 21.3211 24.3216 23.0001 22.2505 23.0001H5.75391C3.68284 23.0001 2.00391 21.3211 2.00391 19.2501V8.75ZM5.75391 6.5C4.51127 6.5 3.50391 7.50736 3.50391 8.75V9.5H24.5005V8.75C24.5005 7.50736 23.4932 6.5 22.2505 6.5H5.75391ZM3.50391 19.2501C3.50391 20.4927 4.51127 21.5001 5.75391 21.5001H22.2505C23.4932 21.5001 24.5005 20.4927 24.5005 19.2501V11H3.50391V19.2501Z"
                            fill="currentColor"></path>
                    </svg>

                    <span>Payments</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="#" class="menu-item block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Transactions</a>
                <a href="#" class="block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Payment
                    Methods</a>
            </div>

        </div>

        <!-- Marketing -->
        <div x-data="{ open: false }" class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        color="currentColor">
                        <ellipse cx="18" cy="10" rx="4" ry="8" stroke="currentColor"></ellipse>
                        <path
                            d="M18 2C14.8969 2 8.46512 4.37761 4.77105 5.85372C3.07942 6.52968 2 8.17832 2 10C2 11.8217 3.07942 13.4703 4.77105 14.1463C8.46512 15.6224 14.8969 18 18 18"
                            stroke="currentColor"></path>
                        <path d="M11 22L9.05674 20.9303C6.94097 19.7657 5.74654 17.4134 6.04547 15"
                            stroke="currentColor"></path>
                    </svg>

                    <span>Marketing</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="#" class="menu-item block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Coupons</a>
                <a href="{{ route('banners.index') }}" class="block
{{ request()->routeIs('banners.*')
    ? 'text-indigo-600 dark:text-indigo-400'
    : 'text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400' 
}} 
hover:translate-x-1 transition-all duration-200">
                    Banners
                </a>
                <a href="#" class="block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Promotions</a>
            </div>

        </div>

        <!-- Reports -->
        <div x-data="{ open: false }" class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 21H15M9 21V16M9 21H3.6C3.26863 21 3 20.7314 3 20.4V16.6C3 16.2686 3.26863 16 3.6 16H9M15 21V9M15 21H20.4C20.7314 21 21 20.7314 21 20.4V3.6C21 3.26863 20.7314 3 20.4 3H15.6C15.2686 3 15 3.26863 15 3.6V9M15 9H9.6C9.26863 9 9 9.26863 9 9.6V16"
                            stroke="currentColor" stroke-width="1.5"></path>
                    </svg>

                    <span>Reports</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="#" class="menu-item block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Sales
                    Report</a>
                <a href="#" class="block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Product
                    Report</a>
                <a href="#" class="block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">Customer
                    Report</a>
            </div>

        </div>

        <!-- Settings -->
        <div x-data="{ open: false }" class="space-y-3">

            <button @click="open = !open" class="h-10 w-full group flex items-center justify-between p-3 rounded-lg 
                    text-gray-700 dark:text-gray-300 
                    hover:bg-indigo-50 dark:hover:bg-indigo-500/10 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">

                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" fill="currentColor">
                        <path fill="currentColor"
                            d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357 357 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a352 352 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357 357 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088-24.512 11.968a294 294 0 0 0-34.816 20.096l-22.656 15.36-116.224-25.088-65.28 113.152 79.68 88.192-1.92 27.136a293 293 0 0 0 0 40.192l1.92 27.136-79.808 88.192 65.344 113.152 116.224-25.024 22.656 15.296a294 294 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152 24.448-11.904a288 288 0 0 0 34.752-20.096l22.592-15.296 116.288 25.024 65.28-113.152-79.744-88.192 1.92-27.136a293 293 0 0 0 0-40.256l-1.92-27.136 79.808-88.128-65.344-113.152-116.288 24.96-22.592-15.232a288 288 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384 192 192 0 0 1 0-384m0 64a128 128 0 1 0 0 256 128 128 0 0 0 0-256">
                        </path>
                    </svg>

                    <span>Settings</span>
                </div>

                <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform duration-200"
                    viewBox="0 0 24 24" fill="currentColor">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" points="7 2 17 12 7 22">
                    </polyline>
                </svg>

            </button>

            <div x-show="open" x-transition class="ml-8 space-y-2">

                <a href="#" class="menu-item block text-gray-400 dark:text-gray-500 
                    hover:text-indigo-600 dark:hover:text-indigo-400 
                    hover:translate-x-1 transition-all duration-200 transition">General
                    Settings</a>

            </div>

        </div>

    </div>
</aside>



<script>
    // ScriptActiveMenu
    const menuItems = document.querySelectorAll('.menu-item, .submenu-item');

    menuItems.forEach(item => {
        item.addEventListener('click', function () {

            menuItems.forEach(i => {
                i.classList.remove(
                    'bg-indigo-100',
                    'text-indigo-600',
                    'dark:bg-indigo-500/10',
                    'dark:text-indigo-400'
                );

                i.classList.add(
                    'text-gray-500',
                    'dark:text-gray-400'
                );
            });

            this.classList.add(
                'bg-indigo-100',
                'text-indigo-600',
                'dark:bg-indigo-500/10',
                'dark:text-indigo-400'
            );

            this.classList.remove(
                'text-gray-500',
                'dark:text-gray-400'
            );

        });
    });


</script>