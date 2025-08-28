<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const searchQuery = ref('');
const showDropdown = ref(false);

// ✅ Lấy user từ props (Inertia session)
const user = computed(() => usePage().props.auth.user);
const isAuthenticated = computed(() => !!user.value);

const logout = async () => {
  try {
    await router.post('/logout'); // Laravel Inertia session logout
  } catch (error) {
    console.error('Đăng xuất thất bại:', error);
  }
};

const search = () => {
  if (searchQuery.value) {
    router.visit(`/search?query=${encodeURIComponent(searchQuery.value)}`);
  }
};

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value;
};
</script>

<template>
  <nav class="bg-slate-50 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <a href="/" class="text-2xl font-serif text-gray-600">CẨM NANG DU LỊCH</a>
        </div>
        <!-- Menu -->
        <div class="hidden md:flex space-x-8">
          <a href="/locations" class="text-gray-700 hover:text-yellow-600">Địa điểm</a>
          <a href="/tours" class="text-gray-700 hover:text-yellow-600">Tour</a>
          <a href="/hotels" class="text-gray-700 hover:text-yellow-600">Khách sạn</a>
          <a href="/posts" class="text-gray-700 hover:text-yellow-600">Mẹo du lịch</a>
          <a href="/events" class="text-gray-700 hover:text-yellow-600">Sự kiện</a>
        </div>
        <!-- Tìm kiếm và Auth -->
        <div class="flex items-center space-x-4">
          <div v-if="isAuthenticated" class="relative">
            <button
              @click="toggleDropdown"
              class="text-gray-700 hover:text-yellow-600 flex items-center space-x-1 focus:outline-none"
            >
              <span>{{ user ? user.name : 'Tài khoản' }}</span>
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <div
              v-show="showDropdown"
              class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-[60]"
            >
              <a
                href="/profile"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
              >
                Hồ sơ
              </a>
              <a
                href="/my-bookings"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
              >
                Lịch sử booking
              </a>

              <button
                @click="logout"
                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
              >
                Đăng xuất
              </button>
            </div>
          </div>
          <div v-else class="space-x-2">
            <a href="/login" class="text-gray-700 hover:text-yellow-600">Đăng nhập</a>
            <a href="/register" class="text-gray-700 hover:text-yellow-600">Đăng ký</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>