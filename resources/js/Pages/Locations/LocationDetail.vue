<template>
  <div class="mt-0">
      <Header/>
    </div>
  <div class="p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
      <div v-if="loading" class="text-center text-gray-400">Đang tải dữ liệu địa điểm...</div>

      <div v-else class="space-y-6">
        <h1 class="text-3xl font-bold text-center text-gray-600 mb-6">{{ location.name }}</h1>

        <div v-if="location.image">
          <img
            :src="location.image"
            alt="Hình ảnh địa điểm"
            class="w-full h-96 object-cover rounded-lg"
            @error="event => event.target.src = fallbackImg"
          />
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Mô tả</h2>
          <p class="text-gray-600 mt-2">{{ location.description }}</p>
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Phương tiện di chuyển</h2>
          <div v-html="location.transportation" class="text-gray-600 mt-2"></div>
        </div>

        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Thông tin đặt chỗ</h2>
          <p class="text-gray-600 mt-2">{{ location.booking_info || 'Chưa có thông tin' }}</p>
        </div>

        <!-- Danh sách Khách sạn -->
        <div v-if="location.hotels?.length">
          <h2 class="text-2xl font-semibold text-gray-800">Khách sạn</h2>
          <div class="mt-2 space-y-3">
            <div
              v-for="hotel in location.hotels"
              :key="hotel.id"
              class="flex items-start p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              <img
                v-if="hotel.image"
                :src="hotel.image"
                alt="Ảnh khách sạn"
                class="w-20 h-20 object-cover rounded-lg mr-4"
                @error="event => event.target.src = fallbackImg"
              />
              <div class="flex-1">
                <p class="text-gray-600">
                  <span class="font-medium">{{ hotel.name }}</span>
                  <span v-if="hotel.address"> - {{ hotel.address }}</span>
                  <span v-if="hotel.price"> - Giá: {{ hotel.price.toLocaleString('vi-VN') }} VND</span>
                </p>
                <a :href="`/hotels/${hotel.id}`" class="text-blue-500 hover:underline text-sm">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <p class="text-gray-600 mt-2">Tổng: {{ location.hotels.length }} khách sạn</p>
        </div>

        <!-- Danh sách Tour -->
        <div v-if="location.tours?.length">
          <h2 class="text-2xl font-semibold text-gray-800">Tour</h2>
          <div class="mt-2 space-y-3">
            <div
              v-for="tour in location.tours"
              :key="tour.id"
              class="flex items-start p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              <img
                v-if="tour.image"
                :src="tour.image"
                alt="Ảnh tour"
                class="w-20 h-20 object-cover rounded-lg mr-4"
                @error="event => event.target.src = fallbackImg"
              />
              <div class="flex-1">
                <p class="text-gray-600">
                  <span class="font-medium">{{ tour.name }}</span>
                  <span v-if="tour.duration"> - Thời gian: {{ tour.duration }}</span>
                  <span v-if="tour.price"> - Giá: {{ tour.price.toLocaleString('vi-VN') }} VND</span>
                </p>
                <a :href="`/tours/${tour.id}`" class="text-blue-500 hover:underline text-sm">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <p class="text-gray-600 mt-2">Tổng: {{ location.tours.length }} tour</p>
        </div>

        <!-- Danh sách Bài viết -->
        <div v-if="location.posts?.length">
          <h2 class="text-2xl font-semibold text-gray-800">Bài viết</h2>
          <div class="mt-2 space-y-3">
            <div
              v-for="post in location.posts"
              :key="post.id"
              class="flex items-start p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
              <img
                v-if="post.image"
                :src="post.image"
                alt="Ảnh bài viết"
                class="w-20 h-20 object-cover rounded-lg mr-4"
                @error="event => event.target.src = fallbackImg"
              />
              <div class="flex-1">
                <p class="text-gray-600">
                  <span class="font-medium">{{ post.title }}</span>
                  <span v-if="post.created_at">
                    - Đăng: {{ new Date(post.created_at).toLocaleDateString('vi-VN') }}
                  </span>
                </p>
                <a :href="`/posts/${post.id}`" class="text-blue-500 hover:underline text-sm">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <p class="text-gray-600 mt-2">Tổng: {{ location.posts.length }} bài viết</p>
        </div>

        <div class="flex justify-center mt-6">
          <a href="/locations" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors">
            Quay lại danh sách địa điểm
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-6">
      <Footer/>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const { props } = usePage()
const locationId = props.locationId

const location = ref(null)
const loading = ref(true)
const fallbackImg = '/image/placeholder.jpg'

onMounted(async () => {
  try {
    const res = await axios.get(`/api/locations/${locationId}`)
    location.value = res.data
  } catch (error) {
    console.error('Lỗi khi lấy chi tiết địa điểm:', error)
  } finally {
    loading.value = false
  }
})
</script>
