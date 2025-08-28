<script setup>
import { ref, onMounted, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

// Khởi tạo biến
// const user = ref(null);
const locations = ref([]);
const hotels = ref([]);
const posts = ref([]);
const events = ref([]);
const tours = ref([]);
const videos = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const showError = ref(false);
const searchResults = ref({ locations: [], tours: [], hotels: [], posts: [] });

// Lấy dữ liệu khi component được mount
onMounted(async () => {
  loading.value = true;

  try {
    // Lấy user từ session
    const user = computed(() => usePage().props.auth.user);

    // Nếu là admin, chuyển hướng
    if (user.value?.role === 'admin') {
      router.visit('/dashboard');
      return;
    }

    // Gọi song song API
    const [locationsRes, hotelsRes, postsRes, eventsRes, toursRes, videosRes] = await Promise.all([
      axios.get('/api/locations'),
      axios.get('/api/hotels'),
      axios.get('/api/posts'),
      axios.get('/api/events'),
      axios.get('/api/tours'),
      axios.get('/api/videos'),
    ]);

    // Hàm xử lý an toàn dữ liệu dạng mảng
    const safeArray = (data) => Array.isArray(data) ? data : [];

    // Gán dữ liệu vào biến
    locations.value = safeArray(locationsRes.data?.data || locationsRes.data);
    hotels.value = safeArray(hotelsRes.data?.data || hotelsRes.data);
    tours.value = safeArray(toursRes.data?.data || toursRes.data);
    videos.value = safeArray(videosRes.data?.data || videosRes.data);

    // Bài viết: tính toán thêm
    const rawPosts = safeArray(postsRes.data?.data || postsRes.data);
    posts.value = rawPosts.map(post => ({
      ...post,
      likes_count: post.likes?.length || 0,
      comments_count: post.comments?.length || 0,
      average_rating: post.ratings?.length
        ? (post.ratings.reduce((sum, r) => sum + r.rating, 0) / post.ratings.length).toFixed(1)
        : 0,
    }));

    // Sự kiện: sort theo ngày
    const rawEvents = safeArray(eventsRes.data?.data || eventsRes.data);
    events.value = rawEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

  } catch (err) {
    if (err.response?.status === 401) {
      router.visit('/login');
    }
    console.error('Lỗi khi tải dữ liệu:', err);
  } finally {
    loading.value = false;
  }
});

// Xử lý tìm kiếm
const search = async () => {
  if (!searchQuery.value.trim()) {
    showError.value = true;
    return;
  }

  showError.value = false;
  loading.value = true;

  try {
    const response = await axios.get('/api/search', {
      params: { q: searchQuery.value.trim() },
    });
    searchResults.value = response.data;
  } catch (err) {
    console.error('Tìm kiếm lỗi:', err);
    showError.value = true;
  } finally {
    loading.value = false;
  }
};
// Hàm hỗ trợ
const truncateText = (text, length) => {
  if (!text) return '';
  return text.length > length ? text.slice(0, length) + '...' : text;
};
const formatDate = date => new Date(date).toLocaleDateString('vi-VN');
</script>

<template>
  <div class="mb-1">
    <Header/>
  </div>
  <div class="min-h-screen bg-slate-100">
    <!-- Main Content -->
    <main v-if="loading" class="text-center text-gray-600 text-xl py-10">
      Đang tải...
    </main>
    <main v-else class="space-y-12">
      <!-- Hero Section -->
      <section class="relative">
        <Carousel :autoplay="5000" :wrap-around="true" :items-to-show="1">
          <Slide v-for="location in locations.slice(0, 4)" :key="location.id">
            <div class="relative h-[70vh] w-full">
              <img
                :src="location.image"
                :alt="location.name"
                class="w-full h-full object-cover"
              />
              <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <div class="text-center text-white p-6">
                  <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ location.name }}</h1>
                  <p class="text-lg md:text-xl font-semibold max-w-2xl mx-auto mb-6">{{ truncateText(location.description, 100) }}</p>
                  <a
                    href="/tours"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-semibold transition duration-300"
                  >
                    Lên kế hoạch
                  </a>
                </div>
              </div>
            </div>
          </Slide>
          <template #addons>
            <Navigation />
            <Pagination />
          </template>
        </Carousel>
      </section>

      <!-- Search Bar -->
  <section class="max-w-4xl mx-auto px-4">
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Tìm kiếm chuyến đi</h2>
      <div class="flex flex-col sm:flex-row gap-4">
        <input
          v-model.trim="searchQuery"
          type="text"
          placeholder="Tìm địa điểm, tour, khách sạn..."
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="search"
        />
        <button
          @click="search"
          :disabled="!searchQuery"
          class="bg-yellow-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition duration-300 disabled:bg-blue-500 disabled:cursor-not-allowed"
        >
          Tìm kiếm
        </button>
      </div>
      <p v-if="searchQuery === '' && showError" class="text-red-500 text-sm mt-2">
        Vui lòng nhập từ khóa tìm kiếm!
      </p>
    </div>
  </section>

  <!-- Kết quả tìm kiếm -->
  <section v-if="searchResults?.locations?.length || searchResults?.tours?.length || searchResults?.hotels?.length || searchResults?.posts?.length" class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Kết quả tìm kiếm</h2>

        <div v-if="searchResults?.locations?.length">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Địa điểm</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="location in searchResults?.locations" :key="location.id" class="bg-white rounded-lg shadow-md overflow-hidden">
              <img :src="location.image" :alt="location.name" class="w-full h-48 object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ location.name }}</h3>
                <p class="text-gray-600 mt-2" v-html="truncateText(location.description, 100)"></p>
                <a :href="`/locations/${location.id}`" class="mt-4 inline-block text-yellow-600 hover:text-blue-700 font-semibold">Khám phá</a>
              </div>
            </div>
          </div>
        </div>

        <div v-if="searchResults?.tours?.length">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Tour</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="tour in searchResults?.tours" :key="tour.id" class="bg-white rounded-lg shadow-md overflow-hidden">
              <img :src="tour.image" :alt="tour.title" class="w-full h-48 object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ tour.title }}</h3>
                <p class="text-gray-600 mt-2" v-html="truncateText(tour.description, 100)"></p>
                <p class="text-green-600 font-semibold mt-2">{{ tour.price }} VND</p>
                <a :href="`/tours/${tour.id}`" class="mt-4 inline-block text-yellow-600 hover:text-blue-700 font-semibold">Xem chi tiết</a>
              </div>
            </div>
          </div>
        </div>

        <div v-if="searchResults?.hotels?.length">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Khách sạn</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="hotel in searchResults?.hotels" :key="hotel.id" class="bg-white rounded-lg shadow-md overflow-hidden">
              <img :src="hotel.image" :alt="hotel.name" class="w-full h-48 object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ hotel.name }}</h3>
                <p class="text-gray-600 mt-2" v-html="truncateText(hotel.description, 100)"></p>
                <p class="text-green-600 font-semibold mt-2">{{ hotel.price }} VND/đêm</p>
                <a :href="`/hotels/${hotel.id}`" class="mt-4 inline-block text-yellow-600 hover:text-blue-700 font-semibold">Đặt ngay</a>
              </div>
            </div>
          </div>
        </div>

        <div v-if="searchResults?.posts?.length">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Bài viết</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="post in searchResults?.posts" :key="post.id" class="bg-white rounded-lg shadow-md overflow-hidden">
              <img :src="post.image" :alt="post.title" class="w-full h-48 object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ post.title }}</h3>
                <p class="text-gray-600 mt-2" v-html="truncateText(post.content, 100)"></p>
                <div class="flex space-x-4 text-sm text-gray-500 mt-2">
                  <span>{{ post.likes_count }} lượt thích</span>
                  <span>{{ post.comments_count }} bình luận</span>
                  <span>{{ post.average_rating }} sao</span>
                </div>
                <a :href="`/posts/${post.id}`" class="mt-4 inline-block text-yellow-600 hover:text-blue-700 font-semibold">Đọc thêm</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Events -->
      <section class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Sự kiện nổi bật</h2>
        <Carousel
          :items-to-show="1"
          :breakpoints="{
            640: { itemsToShow: 2, snapAlign: 'start' },
            1024: { itemsToShow: 3, snapAlign: 'start' },
          }"
        >
          <Slide v-for="event in events.slice(0, 6)" :key="event.id">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mx-2">
              <img
                :src="event.image"
                :alt="event.title"
                class="w-full h-48 object-cover"
              />
              <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800">{{ event.title }}</h3>
                <p class="text-gray-600 mt-2 font-extralight" v-html="truncateText(event.description, 100)"></p>
                <p class="text-sm text-green-600 mt-2">Ngày: {{ formatDate(event.date) }}</p>
                <a
                  href="/events"
                  class="mt-4 inline-block text-blue-500 hover:text-blue-700 font-semibold"
                >
                  Xem chi tiết
                </a>
              </div>
            </div>
          </Slide>
          <template #addons>
            <Navigation />
          </template>
        </Carousel>
      </section>

      <!-- Explore Locations -->
      <section class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Khám phá địa điểm</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="location in locations.slice(0, 6)" :key="location.id" class="bg-white rounded-lg shadow-md overflow-hidden">
            <img
              :src="location.image"
              :alt="location.name"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ location.name }}</h3>
              <p class="text-gray-600 mt-2" v-html="truncateText(location.description, 100)"></p>
              <a
                :href="`/locations/${location.id}`"
                class="mt-4 inline-block text-blue-600 hover:text-blue-700 font-semibold"
              >
                Khám phá
              </a>
            </div>
          </div>
        </div>
        <div class="text-end mt-6">
          <a
            href="/locations"
            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition duration-300"
          >
            Xem tất cả
          </a>
        </div>
      </section>

      <!-- Top Tours -->
      <section class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Tour hàng đầu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="tour in tours.slice(0, 6)" :key="tour.id" class="bg-white rounded-lg shadow-md overflow-hidden">
            <img
              :src="tour.image"
              :alt="tour.title"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ tour.title }}</h3>
              <p class="text-gray-600 mt-2" v-html="truncateText(tour.description, 100)"></p>
              <p class="text-green-600 font-semibold mt-2">{{ tour.price }} VND</p>
              <a
                :href="`/tours/${tour.id}`"
                class="mt-4 inline-block text-blue-500 hover:text-blue-700 font-semibold"
              >
                Xem chi tiết
              </a>
            </div>
          </div>
        </div>
        <div class="text-end mt-6">
          <a
            href="/tours"
            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition duration-300"
          >
            Xem tất cả
          </a>
        </div>
      </section>

      <!-- Best Hotels -->
      <section class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Khách sạn tốt nhất</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="hotel in hotels.slice(0, 6)" :key="hotel.id" class="bg-white rounded-lg shadow-md overflow-hidden">
            <img
              :src="hotel.image"
              :alt="hotel.name"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ hotel.name }}</h3>
              <p class="text-gray-600 mt-2" v-html="truncateText(hotel.description, 100)"></p>
              <p class="text-green-600 font-semibold mt-2">{{ hotel.price }} VND/đêm</p>
              <a
                :href="`/hotels/${hotel.id}`"
                class="mt-4 inline-block text-blue-500 hover:text-blue-700 font-semibold"
              >
                Đặt ngay
              </a>
            </div>
          </div>
        </div>
        <div class="text-end mt-6">
          <a
            href="/hotels"
            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition duration-300"
          >
            Xem tất cả
          </a>
        </div>
      </section>

      <!-- Latest Posts -->
      <section class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Bài viết mới nhất</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="post in posts.slice(0, 6)" :key="post.id" class="bg-white rounded-lg shadow-md overflow-hidden">
            <img
              :src="post.image"
              :alt="post.title"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ post.title }}</h3>
              <p class="text-gray-600 mt-2" v-html="truncateText(post.content, 100)"></p>
              <div class="flex space-x-4 text-sm text-gray-500 mt-2">
                <span>{{ post.likes_count }} lượt thích</span>
                <span>{{ post.comments_count }} bình luận</span>
                <span>{{ post.average_rating }} sao</span>
              </div>
              <a
                :href="`/posts/${post.id}`"
                class="mt-4 inline-block text-blue-500 hover:text-blue-700 font-semibold"
              >
                Đọc thêm
              </a>
            </div>
          </div>
        </div>
        <div class="text-end mt-6">
          <a
            href="/posts"
            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition duration-300"
          >
            Xem tất cả
          </a>
        </div>
      </section>

      <!-- Video Section -->
      <section class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Video khám phá</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="video in videos.slice(0, 3)" :key="video.id" class="bg-white rounded-lg shadow-md overflow-hidden">
            <iframe
            :src="'https://www.youtube.com/embed/' + video.youtube_url.split('/')[3].split('?')[0]"
            class="w-full h-48"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
          ></iframe>

            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ video.title }}</h3>
              <p class="text-gray-600 mt-2" v-html="truncateText(video.description, 100)"></p>
            </div>
          </div>
        </div>
      </section>
    </main>
    <div class="mt-16">
      <Footer/>
    </div>
  </div>
</template>

<style scoped>
/* Tùy chỉnh vue3-carousel */
.carousel__prev,
.carousel__next {
  background-color: #2563eb; /* blue-600 */
  color: white;
  border-radius: 50%;
}
.carousel__pagination-button::after {
  background-color: #2563eb; /* blue-600 */
}
</style>