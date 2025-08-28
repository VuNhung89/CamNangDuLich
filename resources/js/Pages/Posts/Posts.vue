<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const posts = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await axios.get('/api/posts');
    posts.value = res.data.data || res.data;
  } catch (err) {
    console.error('Lỗi khi tải bài viết:', err);
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
    <h1 class="text-3xl font-semibold text-center text-gray-600 mt-10 mb-14">Danh sách bài viết</h1>
    <div v-if="loading" class="text-center text-gray-500">Đang tải bài viết...</div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="post in posts" :key="post.id" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ post.title }}</h2>

        <div v-if="post.image" class="mb-4">
          <img
            :src="`http://localhost:8000${post.image}`"
            alt="Hình ảnh bài viết"
            class="w-full h-48 object-cover rounded-lg"
            @error="event => event.target.src = 'http://localhost:8000/image/placeholder.jpg'"
          />
        </div>

        <div class="space-y-2 text-sm">
          <div>
            <h3 class="font-medium text-gray-800">Tác giả</h3>
            <p class="text-gray-600">{{ post.user?.name || 'Ẩn danh' }}</p>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Ngày đăng</h3>
            <p class="text-gray-600">{{ new Date(post.created_at).toLocaleDateString('vi-VN') }}</p>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Nội dung</h3>
            <p class="text-gray-600" v-html="post.content?.substring(0, 100) + '...'"></p>
          </div>
        </div>

        <div class="flex justify-end mt-4">
          <a :href="`/posts/${post.id}`" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
            Xem chi tiết
          </a>
        </div>
      </div>
    </div>

    <p v-if="!posts.length && !loading" class="text-center text-gray-500 mt-6">
      Chưa có bài viết nào!
    </p>
  </div>
  <div class="mt-6">
    <Footer/>
  </div>
</template>
