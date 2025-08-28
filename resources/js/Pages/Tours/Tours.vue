<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Footer from '@/Components/Footer.vue'
import Header from '@/Components/Header.vue'

const tours = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await axios.get('/api/tours')
    tours.value = res.data
  } catch (error) {
    console.error('Lỗi khi lấy danh sách tour:', error)
  } finally {
    loading.value = false
  }
})

const formatDate = (date) => {
  return date ? new Date(date).toLocaleDateString('vi-VN') : 'Không rõ'
}

const fullImage = (path) => {
  return path?.startsWith('http') ? path : `http://localhost:8000${path}`
}
</script>

<template>
  <div class="mt-0">
      <Header/>
    </div>
  <div class="p-6 bg-slate-100 min-h-screen">
    <h1 class="text-3xl font-semibold text-center text-gray-600 mb-10 mt-14">Danh sách tour</h1>

    <!-- Loading -->
    <div v-if="loading" class="text-center text-gray-500">Đang tải danh sách tour...</div>

    <!-- Danh sách tour -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="tour in tours"
        :key="tour.id"
        class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow"
      >
        <!-- Tiêu đề -->
        <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ tour.title }}</h2>

        <!-- Hình ảnh -->
        <div v-if="tour.image" class="mb-4">
          <img
            :src="fullImage(tour.image)"
            alt="Hình ảnh tour"
            class="w-full h-48 object-cover rounded-lg"
            @error="event => event.target.src = '/image/placeholder.jpg'"
          />
        </div>

        <!-- Thông tin -->
        <div class="space-y-2 text-sm">
          <div>
            <h3 class="font-medium text-gray-800">Giá</h3>
            <p class="text-gray-600">
              {{ tour.price ? tour.price.toLocaleString('vi-VN') + ' VND' : 'Chưa rõ' }}
            </p>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Thời gian</h3>
            <p class="text-gray-600">
              {{ formatDate(tour.start_date) }} - {{ formatDate(tour.end_date) }}
            </p>
          </div>
        </div>

        <!-- Nút Xem chi tiết -->
        <div class="flex justify-end mt-4">
          <a
            :href="`/tours/${tour.id}`"
            class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
          >
            Xem chi tiết
          </a>
        </div>
      </div>
    </div>

    <!-- Không có dữ liệu -->
    <p v-if="!tours.length && !loading" class="text-center text-gray-500 mt-6">
      Chưa có tour nào!
    </p>
  </div>
  <div class="mt-6">
      <Footer/>
    </div>
</template>
