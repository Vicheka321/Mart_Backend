  <div class="flex-1 flex flex-col"></div>
  <nav class="w-full">
      <div class="flex items-center justify-between px-6 py-3 bg-white border-b">

          <!-- LEFT: Search -->
          <div class="relative w-72">

              <!-- Input -->
              <input type="text" placeholder="Search..." class="w-full h-10 pl-10 pr-4 rounded-2xl border border-gray-300 
        focus:outline-none focus:ring-2 focus:ring-indigo-400">

              <!-- Icon -->
              <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" fill="currentColor">
                  <path
                      d="m795.904 750.72 124.992 124.928a32 32 0 0 1-45.248 45.248L750.656 795.904a416 416 0 1 1 45.248-45.248zM480 832a352 352 0 1 0 0-704 352 352 0 0 0 0 704">
                  </path>
              </svg>

          </div>

          <!-- RIGHT -->
          <div class="flex items-center gap-5">



              <!-- Dark Mode -->
              <button @click="dark = !dark"
                  class="hover:scale-105 transition text-gray-600 dark:text-gray-300 hover:text-indigo-600">

                  <!-- Moon (Light mode) -->
                  <svg x-show="!dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M21 12.79A9 9 0 1111.21 3 
        7 7 0 0021 12.79z"></path>
                  </svg>

                  <!-- Sun (Dark mode) -->
                  <svg x-show="dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path
                          d="M12 4V2M12 22v-2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41M12 18a6 6 0 100-12 6 6 0 000 12z" />
                  </svg>

              </button>

              <!-- Notification -->
              <div class="relative">
                  <button class="hover:scale-105 transition text-gray-600 hover:text-indigo-600">
                      🔔
                  </button>
                  <span class="absolute top-0 right-0 w-2 h-2 bg-indigo-500 rounded-full"></span>
              </div>

              <!-- PROFILE DROPDOWN -->
              <div x-data="{ open: false }" class="relative">

                  <!-- Avatar -->
                  <img @click="open = !open" src="https://i.pravatar.cc/40"
                      class="w-8 h-8 rounded-full cursor-pointer">

                  <!-- Dropdown -->
                  <div x-show="open" @click.outside="open = false" x-transition
                      class="absolute right-0 mt-3 w-52 bg-white rounded-xl shadow-lg p-3">

                      <!-- Item -->
                      <a href="#" class="flex items-center gap-2 p-2 rounded-lg bg-indigo-100 text-indigo-600">
                          👤 <span>My Profile</span>
                      </a>

                      <a href="#" class="flex items-center gap-2 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                          ✉️ <span>My Account</span>
                      </a>

                      <a href="#" class="flex items-center gap-2 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                          📋 <span>My Task</span>
                      </a>

                      <hr class="my-2">

                      <!-- Logout -->
                      <form action="{{ route('logout') }}" method="POST">
                          @csrf
                          <button onclick="confirmLogout()"
                              class="w-full text-center border border-indigo-500 text-indigo-600 py-2 rounded-lg hover:bg-indigo-50">
                              Logout
                          </button>
                      </form>

                  </div>

              </div>

          </div>

      </div>


  </nav>
  </div>

<script>
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
</script>