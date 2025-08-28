<script setup>
import { ref, onMounted, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import Sidebar from './Admin/Sidebar.vue';
import Header from './Admin/Header.vue';
import Footer from './Admin/Footer.vue';

const user = computed(() => usePage().props.auth.user);

const stats = ref({
  users: 0, posts: 0, tours: 0, bookings: 0,
  hotels: 0, locations: 0, events: 0, videos: 0,
  comments: 0, ratings: 0, likes: 0, pendingPosts: 0
});
const posts = ref([]);
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = 10;
const loading = ref(true);
const pendingBookings = ref([]);

const statItems = [
  { label: 'Người dùng', key: 'users', color: 'from-blue-500 to-indigo-500', icon: 'user' },
  { label: 'Bài viết', key: 'posts', color: 'from-yellow-500 to-orange-500', icon: 'file' },
  { label: 'Tour', key: 'tours', color: 'from-green-500 to-teal-500', icon: 'map' },
  { label: 'Đặt tour', key: 'bookings', color: 'from-purple-500 to-pink-500', icon: 'calendar' }
];

const fetchData = async () => {
  try {
    const [statsRes, postsRes, bookingsRes] = await Promise.all([
      axios.get('/admin/stats'),
      axios.get('/posts/pending', { 
        params: { 
          page: currentPage.value,
          per_page: perPage
    } }),
      axios.get('/admin/bookings/pending')
    ]);
    stats.value = statsRes.data;
    posts.value = postsRes.data.data;
    pendingBookings.value = bookingsRes.data;
    currentPage.value = postsRes.data.current_page;
    lastPage.value = postsRes.data.last_page;
  } catch (err) {
    console.error('Lỗi API:', err.response?.data || err.message);
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (!user.value) return router.visit('/login');
  if (user.value.role !== 'admin') return router.visit('/');
  await fetchData();
});

const goToPage = async (page) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page;
    await fetchData();
  }
};

const approvePost = async (postId) => {
  if (confirm('Duyệt bài viết này?')) {
    try {
      await axios.put(`/api/posts/${postId}/approve`);
      posts.value = posts.value.filter(post => post.id !== postId);
      stats.value.pendingPosts--;
    } catch (err) {
      console.error('Lỗi duyệt bài:', err);
    }
  }
};

const rejectPost = async (postId) => {
  if (confirm('Bạn chắc chắn muốn xóa bài viết này?')) {
    try {
      await axios.delete(`/api/posts/${postId}`);
      posts.value = posts.value.filter(post => post.id !== postId);
      stats.value.pendingPosts--;
    } catch (err) {
      console.error('Lỗi xóa bài:', err);
    }
  }
};

const approveBooking = async (id) => {
  if (confirm('Duyệt booking này?')) {
    try {
      await axios.put(`/admin/bookings/${id}/approve`);
      pendingBookings.value = pendingBookings.value.filter(b => b.id !== id);
    } catch (err) {
      console.error('Lỗi duyệt booking:', err);
    }
  }
};

const rejectBooking = async (id) => {
  if (confirm('Xóa booking này?')) {
    try {
      await axios.delete(`/admin/bookings/${id}`);
      pendingBookings.value = pendingBookings.value.filter(b => b.id !== id);
    } catch (err) {
      console.error('Lỗi xóa booking:', err);
    }
  }
};

const goToDetail = (postId) => {
  router.visit(`/admin/posts/${postId}`);
};

const formatDate = date => new Date(date).toLocaleDateString('vi-VN');

const logout = () => {
  router.post('/logout');
};

</script>

<template>
  <div class="min-h-screen bg-gray-50 flex">
   <!-- Sidebar -->
    <Sidebar />
    <!-- Main -->
    <div class="flex-1">
      <!-- Header -->
     <Header/>
      <!-- Nội dung -->
      <main class="px-6 py-8">
      <div v-if="loading" class="flex justify-center items-center">
        <svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
        </svg>
      </div>

      <div v-else>
        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <div
            v-for="item in statItems"
            :key="item.label"
            :class="`bg-gradient-to-r ${item.color} p-5 rounded-xl shadow text-white flex justify-between items-center`"
          >
            <div>
              <h2 class="text-sm uppercase opacity-80">{{ item.label }}</h2>
              <p class="text-2xl font-bold">{{ stats[item.key] }}</p>
            </div>
            <div>
              <svg v-if="item.icon === 'user'" class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9.003 9.003 0 0112 15a9.003 9.003 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <svg v-else-if="item.icon === 'file'" class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4z" />
              </svg>
              <svg v-else-if="item.icon === 'map'" class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A2 2 0 013 15.382V4.618a2 2 0 01.553-1.382L9 1m0 0l6 3m-6-3v19m6-16l5.447 2.724A2 2 0 0121 8.618v10.764a2 2 0 01-.553 1.382L15 23m0 0V4" />
              </svg>
              <svg v-else-if="item.icon === 'calendar'" class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Pending posts -->
        <div class="bg-white rounded-xl shadow p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Bài viết chờ duyệt ({{ stats.pendingPosts }})</h2>

          <div v-if="posts.length === 0" class="text-center text-gray-500">Không có bài viết chờ duyệt</div>

          <div v-for="post in posts" :key="post.id" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-lg text-gray-800">{{ post.title }}</h3>
                <p class="text-sm text-gray-500">Tác giả: {{ post.user.name }} – {{ formatDate(post.created_at) }}</p>
              </div>
              <div class="flex gap-2">
                <button @click="goToDetail(post.id)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Xem</button>
                <button @click="approvePost(post.id)" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Duyệt</button>
                <button @click="rejectPost(post.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Xóa</button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex justify-center items-center gap-4">
            <button
              :disabled="currentPage === 1"
              @click="goToPage(currentPage - 1)"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded disabled:opacity-50"
            >
              ← Trước
            </button>
            <span class="text-gray-600 font-semibold">Trang {{ currentPage }} / {{ lastPage }}</span>
            <button
              :disabled="currentPage === lastPage"
              @click="goToPage(currentPage + 1)"
              class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded disabled:opacity-50"
            >
              Sau →
            </button>
          </div>
        </div>
        <!-- Pending bookings -->
        <div class="bg-white rounded-xl shadow p-6 mt-10">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Booking chờ duyệt ({{ pendingBookings.length }})
          </h2>

          <div v-if="pendingBookings.length === 0" class="text-center text-gray-500">
            Không có booking chờ duyệt
          </div>

          <div v-for="booking in pendingBookings" :key="booking.id" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-lg text-gray-800">Khách: {{ booking.user.name }}</h3>
                <p class="text-sm text-gray-500">
                  Tour: {{ booking.tour.title }} – Ngày: {{ formatDate(booking.created_at) }}
                </p>
                <p class="text-sm text-gray-500">
                  Số người: {{ booking.people }} | Tổng tiền: {{ booking.total_price.toLocaleString('vi-VN') }}đ
                </p>
              </div>
              <div class="flex gap-2">
                <button @click="approveBooking(booking.id)" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                  Duyệt
                </button>
                <button @click="rejectBooking(booking.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
      </main>
    </div>
  </div>
  <!-- Footer -->
   <Footer/>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
body {
  font-family: 'Poppins', sans-serif;
}
.material-icons {
  font-size: 20px;
  line-height: 1;
}

</style>