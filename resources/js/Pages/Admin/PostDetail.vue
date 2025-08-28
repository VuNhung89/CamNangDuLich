<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

const props = defineProps({
  postId: Number
});

const user = usePage().props.auth.user;
const post = ref(null);
const loading = ref(true);
const error = ref(null);
const fallbackImg = '/image/placeholder.jpg';

const fetchPost = async () => {
  try {
    const res = await axios.get(`/api/posts/${props.postId}`);
    post.value = res.data;
  } catch (err) {
    error.value = 'Không thể tải bài viết';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const approvePost = async () => {
  if (!confirm('Duyệt bài viết này?')) return;

  try {
    await axios.put(`/api/posts/${props.postId}/approve`);
    alert('Đã duyệt bài viết');
    router.visit('/dashboard'); // quay về dashboard
  } catch (err) {
    alert('Lỗi khi duyệt bài');
    console.error(err);
  }
};

const deletePost = async () => {
  if (!confirm('Xoá bài viết này?')) return;

  try {
    await axios.delete(`/api/posts/${props.postId}`);
    alert('Đã xoá bài viết');
    router.visit('/dashboard');
  } catch (err) {
    alert('Lỗi khi xoá bài');
    console.error(err);
  }
};

onMounted(() => {
  if (!user || user.role !== 'admin') return router.visit('/');
  fetchPost();
});
</script>

<template>
  <DefaultLayout>
    <div class="px-8 py-6">
      <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải bài viết...</div>
      <div v-else-if="error" class="text-red-500">{{ error }}</div>
      <div v-else>
        <h1 class="text-2xl font-bold mb-4 text-gray-800">{{ post.title }}</h1>

        <div class="mb-4 text-gray-600">
          Tác giả: <strong>{{ post.user.name }}</strong>
          – {{ new Date(post.created_at).toLocaleDateString('vi-VN') }}
        </div>

        <img
          :src="post.image || fallbackImg"
          alt="Ảnh bài viết"
          class="w-full max-w-xl rounded-lg shadow mb-6"
        />

        <div class="prose prose-sm max-w-4xl text-gray-700" v-html="post.content"></div>

        <div class="mt-6 text-gray-600">
          Địa điểm: {{ post.location?.name || 'Không rõ' }}
        </div>

        <!-- Nút duyệt/xoá -->
        <div class="mt-8 flex gap-4">
          <button @click="approvePost" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Duyệt bài
          </button>
          <button @click="deletePost" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
            Xoá bài
          </button>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
.prose {
  white-space: pre-line;
}
</style>
