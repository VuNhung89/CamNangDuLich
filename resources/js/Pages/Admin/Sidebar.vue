<template>
  <nav class="w-64 min-h-screen bg-white border-r shadow">
    <div class="h-16 flex items-center px-6 border-b">
      <a href="/admin" class="text-xl font-serif text-gray-800">CẨM NANG DU LỊCH</a>
    </div>

    <ul class="mt-4 space-y-2 px-4">
      <li v-for="item in menuItems" :key="item.path">
        <a
          :href="item.path"
          class="flex items-center gap-3 px-4 py-2 rounded-lg font-medium shadow-sm transition-all"
          :class="[
            $page.url.startsWith(item.path) ? item.color + ' ring-2 ring-offset-2 ring-opacity-50' : 'hover:bg-gray-100 text-gray-700'
          ]"
        >
          <span class="material-icons text-[20px]">{{ item.icon }}</span>
          <span class="text-sm">{{ item.label }}</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script setup>
const menuItems = [
  { path: '/dashboard', label: 'Bảng điều khiển', icon: 'dashboard', color: 'bg-blue-100 text-blue-700' },
  { path: '/admin/users', label: 'Người dùng', icon: 'people', color: 'bg-pink-100 text-pink-700' },
  { path: '/admin/posts', label: 'Bài viết', icon: 'article', color: 'bg-yellow-100 text-yellow-700' },
  { path: '/admin/tours', label: 'Tour', icon: 'map', color: 'bg-green-100 text-green-700' },
  { path: '/admin/hotels', label: 'Khách sạn', icon: 'hotel', color: 'bg-purple-100 text-purple-700' },
  { path: '/admin/locations', label: 'Địa điểm', icon: 'place', color: 'bg-indigo-100 text-indigo-700' },
  { path: '/admin/events', label: 'Sự kiện', icon: 'event', color: 'bg-rose-100 text-rose-700' },
  { path: '/admin/bookings', label: 'Booking', icon: 'book', color: 'bg-slate-100 text-slate-700'}
];
</script>
