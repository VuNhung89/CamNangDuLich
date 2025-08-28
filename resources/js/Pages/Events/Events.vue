<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const events = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await axios.get('/api/events');
    events.value = res.data.data || res.data;
  } catch (err) {
    console.error('Lỗi khi tải sự kiện:', err);
  } finally {
    loading.value = false;
  }
});

const formatDate = date => new Date(date).toLocaleDateString('vi-VN');
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '...' : text;
};
</script>

<template>
  <div class="mt-0">
      <Header/>
    </div>
  <div class="p-6 bg-slate-50 min-h-screen">
    <h1 class="text-3xl font-bold text-center text-gray-600 mb-14 mt-10">Danh sách sự kiện</h1>

    <div v-if="loading" class="text-center text-gray-500">
      Đang tải danh sách sự kiện...
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="event in events"
        :key="event.id"
        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow"
      >
        <!-- Hình ảnh -->
        <div v-if="event.image" class="mb-4">
          <img
            :src="event.image"
            alt="Hình ảnh sự kiện"
            class="w-full h-48 object-cover rounded-lg border border-gray-200"
            @error="event => event.target.src = '/images/placeholder.jpg'"
          />
        </div>

        <!-- Tiêu đề -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2">
          {{ truncateText(event.title, 50) }}
        </h2>

        <!-- Ngày & địa điểm -->
        <div class="space-y-2 text-sm">
          <div>
            <h3 class="font-medium text-gray-800">Ngày</h3>
            <p class="text-gray-600">{{ formatDate(event.date) }}</p>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Địa điểm</h3>
            <p class="text-gray-600">{{ event.location || 'Chưa rõ địa điểm' }}</p>
          </div>
        </div>

        <!-- Nút Xem chi tiết -->
        <div class="flex justify-end mt-4">
          <a
            :href="`/events/${event.id}`"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
          >
            Xem chi tiết
          </a>
        </div>
      </div>
    </div>

    <p v-if="!events.length && !loading" class="text-center text-gray-500 mt-6">
      Chưa có sự kiện nào!
    </p>
  </div>
  <div class="mt-6">
      <Footer/>
    </div>
</template>
