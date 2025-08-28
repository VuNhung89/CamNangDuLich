<script setup>
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const { props } = usePage()
const hotel = ref({})
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await axios.get(`/api/hotels/${props.hotelId}`)
    hotel.value = res.data
  } catch (err) {
    console.error('Lỗi khi lấy chi tiết khách sạn:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="mb-1">
    <Header />
  </div>
  <div class="p-6 bg-slate-50">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
      <div v-if="loading" class="text-center text-gray-400">
        Đang tải chi tiết khách sạn...
      </div>

      <div v-else class="space-y-6">
        <h1 class="text-3xl font-bold text-center text-gray-600 mb-6">{{ hotel.name }}</h1>

        <div v-if="hotel.image">
          <img
            :src="`http://localhost:8000${hotel.image}`"
            alt="Hình ảnh khách sạn"
            class="w-full h-96 object-cover rounded-lg"
            @error="event => event.target.src = 'http://localhost:8000/image/placeholder.jpg'"
          />
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Mô tả</h2>
          <div v-html="hotel.description" class="text-gray-600 mt-2"></div>
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Giá</h2>
          <p class="text-gray-600 mt-2">
            {{ hotel.price ? hotel.price.toLocaleString('vi-VN') + ' VND' : 'Chưa có thông tin giá' }}
          </p>
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Địa chỉ</h2>
          <p class="text-gray-600 mt-2">{{ hotel.address || 'Chưa có địa chỉ' }}</p>
        </div>

        <div v-if="hotel.location">
          <h2 class="text-2xl font-semibold text-gray-800">Địa điểm</h2>
          <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200 flex items-start space-x-4">
            <div v-if="hotel.location.image" class="flex-shrink-0">
              <img
                :src="`http://localhost:8000${hotel.location.image}`"
                alt="Hình ảnh địa điểm"
                class="w-24 h-24 object-cover rounded-lg border border-gray-200"
                @error="event => event.target.src = 'http://localhost:8000/image/placeholder.jpg'"
              />
            </div>
            <div class="flex-1">
              <a
                :href="`/locations/${hotel.location.id}`"
                class="text-blue-500 hover:underline font-medium text-lg"
              >
                {{ hotel.location.name }}
              </a>
              <p class="text-gray-600 mt-1 text-sm">
                {{ hotel.location.description
                  ? hotel.location.description.substring(0, 100) + '...'
                  : 'Chưa có mô tả' }}
              </p>
            </div>
          </div>
        </div>

        <div class="flex justify-center mt-6">
          <a
            href="/hotels"
            class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors"
          >
            Quay lại danh sách khách sạn
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-16">
      <Footer />
    </div>
</template>
