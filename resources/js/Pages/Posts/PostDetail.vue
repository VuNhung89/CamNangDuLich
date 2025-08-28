<template>
  <div class="p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
      <!-- Loading -->
      <div v-if="loading" class="text-center text-gray-400">
        Đang tải chi tiết bài viết...
      </div>

      <!-- Nội dung chính -->
      <div v-else class="space-y-6">
        <!-- Tiêu đề -->
        <h1 class="text-3xl font-bold text-center text-gray-600 mb-6">{{ post.title }}</h1>

        <!-- Hình ảnh -->
        <div v-if="post.image">
          <img
            :src="fullImage(post.image)"
            alt="Hình ảnh bài viết"
            class="w-full h-96 object-cover rounded-lg"
            @error="event => event.target.src = fallbackImg"
          />
        </div>

        <!-- Nội dung -->
        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Nội dung</h2>
          <div v-html="post.content" class="text-gray-600 mt-2"></div>
        </div>

        <!-- Người đăng -->
        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Người đăng</h2>
          <p class="text-gray-600 mt-2">{{ post.user?.name || 'Ẩn danh' }}</p>
        </div>

        <!-- Địa điểm -->
        <div v-if="post.location">
          <h2 class="text-2xl font-semibold text-gray-800">Địa điểm</h2>
          <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200 flex items-start space-x-4">
            <div v-if="post.location.image" class="flex-shrink-0">
              <img
                :src="fullImage(post.location.image)"
                alt="Hình ảnh địa điểm"
                class="w-24 h-24 object-cover rounded-lg border border-gray-200"
                @error="event => event.target.src = fallbackImg"
              />
            </div>
            <div class="flex-1">
              <a
                :href="`/locations/${post.location.id}`"
                class="text-blue-500 hover:underline font-medium text-lg"
              >
                {{ post.location.name }}
              </a>
              <p class="text-gray-600 mt-1 text-sm">
                {{ post.location.description ? post.location.description.substring(0, 100) + '...' : 'Chưa có mô tả' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Ngày đăng -->
        <div>
          <h2 class="text-2xl font-semibold text-gray-800">Ngày đăng</h2>
          <p class="text-gray-600 mt-2">{{ formatDate(post.created_at) }}</p>
        </div>

        <!-- Nút quay lại -->
        <div class="flex justify-center mt-6">
          <a
            href="/posts"
            class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors"
          >
            Quay lại danh sách bài viết
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

export default {
  layout: DefaultLayout,
  props: ['postId'],
  setup(props) {
    const post = ref({});
    const loading = ref(true);
    const fallbackImg = '/image/placeholder.jpg';

    const fetchPost = async () => {
      try {
        const response = await axios.get(`/api/posts/${props.postId}`);
        post.value = response.data;
      } catch (error) {
        console.error('Lỗi khi lấy chi tiết bài viết:', error);
      } finally {
        loading.value = false;
      }
    };

    const fullImage = (path) => {
      if (!path) return fallbackImg;
      return path.startsWith('http') ? path : `http://localhost:8000${path}`;
    };

    const formatDate = (date) => {
      return date ? new Date(date).toLocaleDateString('vi-VN') : 'Không rõ';
    };

    onMounted(fetchPost);

    return { post, loading, fallbackImg, fullImage, formatDate };
  },
};
</script>
