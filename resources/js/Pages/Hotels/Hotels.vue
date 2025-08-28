<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const hotels = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await axios.get('/api/hotels');
    hotels.value = res.data;
  } catch (err) {
    console.error('Lỗi khi tải khách sạn:', err);
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="mt-0">
      <Header/>
    </div>
  <div class="p-6 bg-slate-100 min-h-screen">
    <h1 class="text-3xl font-semibold text-center text-gray-600 mt-10 mb-14">Danh sách khách sạn</h1>
    <div v-if="loading" class="text-center text-gray-500">Đang tải danh sách khách sạn...</div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="hotel in hotels" :key="hotel.id" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ hotel.name }}</h2>

        <div v-if="hotel.image" class="mb-4">
          <img
            :src="`http://localhost:8000${hotel.image}`"
            alt="Hình ảnh khách sạn"
            class="w-full h-48 object-cover rounded-lg border border-gray-200"
            @error="event => event.target.src = 'http://localhost:8000/image/placeholder.jpg'"
          />
        </div>

        <div class="space-y-2 text-sm">
          <div>
            <h3 class="font-medium text-gray-800">Giá</h3>
            <p class="text-gray-600">{{ hotel.price?.toLocaleString('vi-VN') }} VND</p>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Địa chỉ</h3>
            <p class="text-gray-600">{{ hotel.address || 'Chưa có địa chỉ' }}</p>
          </div>
          <div v-if="hotel.location">
            <h3 class="font-medium text-gray-800">Địa điểm</h3>
            <p class="text-gray-600">
              <a :href="`/locations/${hotel.location.id}`" class="text-blue-500 hover:underline">
                {{ hotel.location.name }}
              </a>
            </p>
          </div>
        </div>

        <div class="flex justify-end mt-4">
          <a :href="`/hotels/${hotel.id}`" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
            Xem chi tiết
          </a>
        </div>
      </div>
    </div>

    <p v-if="!hotels.length && !loading" class="text-center text-gray-500 mt-6">
      Chưa có khách sạn nào!
    </p>
  </div>
  <div class="mt-6">
      <Footer/>
    </div>
</template>
