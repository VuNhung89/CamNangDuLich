<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import axios from 'axios';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

// ✅ Lấy user từ session (nếu cần hiển thị thông tin)
const user = computed(() => usePage().props.auth.user);

// ✅ Lấy eventId từ route props
const { props } = usePage();
const eventId = props.eventId;

// ✅ State
const event = ref(null);
const loading = ref(true);

// ✅ Gọi API public để lấy chi tiết sự kiện
onMounted(async () => {
  try {
    const res = await axios.get(`/api/events/${eventId}`);
    event.value = res.data;
  } catch (err) {
    if (err.response?.status === 404) {
      event.value = null;
    }
    console.error('Lỗi khi tải sự kiện:', err);
  } finally {
    loading.value = false;
  }
});

// ✅ Helpers
const formatDate = date => new Date(date).toLocaleDateString('vi-VN');
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '...' : text;
};
</script>

<template>
  <div class="mb-1">
    <Header />
  </div>
  <div class="min-h-screen bg-gray-50">
    <!-- Main Content -->
    <main v-if="loading" class="text-center text-gray-600 text-xl py-10">
      Đang tải...
    </main>
    <main v-else class="space-y-12">
      <!-- Event Detail Section -->
      <section class="max-w-7xl mx-auto px-4 py-8">
        <div v-if="event" class="bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Event Image -->
          <img
            :src="event.image"
            :alt="event.title"
            class="w-full h-[60vh] object-cover"
          />
          <div class="p-6">
            <!-- Event Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
              {{ event.title }}
            </h1>
            <!-- Event Date and Location -->
            <div class="flex flex-col md:flex-row md:space-x-auto text-gray-600 mb-6">
              <p class="text-lg">
                <span class="font-semibold">Ngày:</span> {{ formatDate(event.date) }}
              </p>
              <p class="text-lg">
                <span class="font-semibold">Địa điểm:</span> {{ event.location || 'Default' }}
              </p>
            </div>
            <!-- Event Description -->
            <div class="text-gray-700 text-base md:text-lg leading-relaxed mb-6">
              <h2 class="text-2xl font-semibold text-gray-800 mb-3">Mối quan hệ</h2>
              <p v-html="event.description"></p>
            </div>
            <!-- Action Button -->
            <div class="text-center">
              <Link
                href="/events"
                class="bg-blue-500 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-semibold transition duration-300"
              >
                Quay lại danh sách sự kiện
              </Link>
            </div>
          </div>
        </div>
        <div v-else class="text-center text-red-500 text-xl py-6">
          Không tìm thấy sự kiện!
        </div>
      </section>
    </main>
    <div class="mt-16">
      <Footer />
    </div>
  </div>
</template>

<style scoped>
/* Tùy chỉnh kiểu dáng */
</style>