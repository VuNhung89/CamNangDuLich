<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const posts = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = 10;
const editingId = ref(null);

const form = ref({
  title: '',
  content: '',
  location_id: '',
  status: 'pending',
  image: null,
});

const showForm = ref(true);
const locations = ref([]);

// Gọi API lấy danh sách địa điểm
const fetchLocations = async () => {
  try {
    const res = await axios.get('/api/locations');
    locations.value = res.data;
  } catch (err) {
    console.error('Lỗi tải địa điểm:', err);
  }
};

const resetForm = () => {
  form.value = { title: '', content: '', location_id: '', status: 'pending', image: null };
  imageFile.value = null;
  editingId.value = null;
  showForm.value = false;
};

const editPost = (post) => {
  editingId.value = post.id;
  form.value = {
    title: post.title,
    content: post.content,
    location_id: post.location_id,
    status: post.status,
    image: null, // không load lại ảnh cũ vào input type="file"
  };
  showForm.value = true;
};

const fetchPosts = async () => {
  try {
    loading.value = true;
    const res = await axios.get('/api/admin/posts', {
      params: { page: currentPage.value, per_page: perPage }
    });
    posts.value = res.data.data;
    currentPage.value = res.data.current_page;
    lastPage.value = res.data.last_page;
  } catch (err) {
    console.error('Lỗi tải bài viết:', err.response?.data || err.message);
  } finally {
    loading.value = false;
  }
};

const createOrUpdatePost = async () => {
  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('content', form.value.content);
  formData.append('location_id', form.value.location_id);
  formData.append('status', form.value.status);
  if (form.value.image) {
    formData.append('image', form.value.image);
  }

  try {
    if (editingId.value) {
      await axios.post(`/api/admin/posts/${editingId.value}`, formData);
      alert('Cập nhật bài viết thành công!');
    } else {
      await axios.post('/api/admin/posts', formData);
      alert('Tạo bài viết thành công!');
    }
    resetForm();
    await fetchPosts();
  } catch (err) {
    console.error('Lỗi gửi bài viết:', err.response?.data || err.message);
  }
};

const handleFileChange = (e) => {
  form.value.image = e.target.files[0];
};

const goToDetail = (id) => {
  router.visit(`/admin/posts/${id}`);
};

const deletePost = async (id) => {
  if (confirm('Bạn chắc chắn muốn xóa bài viết này?')) {
    try {
      await axios.delete(`/api/posts/${id}`);
      posts.value = posts.value.filter(p => p.id !== id);
    } catch (err) {
      console.error('Lỗi xóa bài viết:', err);
    }
  }
};

const goToPage = async (page) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page;
    await fetchPosts();
  }
};

onMounted(() => {
  fetchPosts();
  fetchLocations();
});
</script>

<template>
  <div class="flex">
    <Sidebar/>
    <div class="flex-1 p-6 bg-slate-100">
    <h1 class="text-3xl font-semibold text-green-800 mb-6">📰 Danh sách bài viết</h1>

    <button @click="showForm = !showForm"
            class="mb-6 bg-green-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
      {{ showForm ? 'Đóng form' : '➕ Thêm bài viết mới' }}
    </button>

    <!-- FORM TẠO BÀI VIẾT -->
    <div v-if="showForm" class="bg-white shadow rounded-xl p-6 mb-8 space-y-4">
      <div>
        <label class="block text-sm font-medium">Tiêu đề</label>
        <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2" />
      </div>
      <div>
        <label class="block text-sm font-medium">Nội dung</label>
        <textarea v-model="form.content" rows="4" class="w-full border rounded px-3 py-2"></textarea>
      </div>
      <div>
        <label class="block text-sm font-medium">Địa điểm</label>
        <select v-model="form.location_id" class="w-full border rounded px-3 py-2">
          <option value="">-- Chọn địa điểm --</option>
          <option v-for="loc in locations" :key="loc.id" :value="loc.id">
            {{ loc.name }}
          </option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Trạng thái</label>
        <select v-model="form.status" class="w-full border rounded px-3 py-2">
          <option value="pending">Chờ duyệt</option>
          <option value="approved">Đã duyệt</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Ảnh (tùy chọn)</label>
        <input type="file" @change="handleFileChange" />
      </div>
      <button @click="createOrUpdatePost"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold">
        {{ editingId ? '💾 Lưu thay đổi' : '✅ Tạo bài viết' }}
      </button>
      <button v-if="editingId" @click="resetForm"
              class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded font-semibold">
        ❌ Hủy
      </button>

    </div>

    <!-- BẢNG DANH SÁCH BÀI VIẾT -->
    <div v-if="loading" class="text-center text-gray-600 py-10">Đang tải...</div>

    <div v-else>
      <table class="min-w-full bg-white border rounded-xl shadow text-sm">
        <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
          <tr>
            <th class="text-left px-4 py-3">Tiêu đề</th>
            <th class="text-left px-4 py-3">Tác giả</th>
            <th class="text-left px-4 py-3">Trạng thái</th>
            <th class="text-left px-4 py-3">Ngày tạo</th>
            <th class="text-left px-4 py-3">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 text-gray-800 font-medium">{{ post.title }}</td>
            <td class="px-4 py-2 text-gray-700">{{ post.user?.name || 'Không rõ' }}</td>
            <td class="px-4 py-2">
              <span :class="[
                post.status === 'approved' ? 'bg-green-100 text-green-700' :
                'bg-yellow-100 text-yellow-700',
                'text-xs font-medium px-2 py-1 rounded-full'
              ]">{{ post.status }}</span>
            </td>
            <td class="px-4 py-2 text-gray-600">
              {{ new Date(post.created_at).toLocaleDateString('vi-VN') }}
            </td>
            <td class="px-4 py-2 flex gap-2">
              <button @click="goToDetail(post.id)" class="text-blue-600 hover:underline">Xem</button>
              <button @click="editPost(post)" class="text-orange-600 hover:underline">Sửa</button>
              <button @click="deletePost(post.id)" class="text-red-600 hover:underline">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center items-center gap-4 text-sm">
        <button
          :disabled="currentPage === 1"
          @click="goToPage(currentPage - 1)"
          class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded disabled:opacity-50"
        >
          ← Trước
        </button>
        <span class="text-gray-700 font-medium">Trang {{ currentPage }} / {{ lastPage }}</span>
        <button
          :disabled="currentPage === lastPage"
          @click="goToPage(currentPage + 1)"
          class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded disabled:opacity-50"
        >
          Sau →
        </button>
      </div>
    </div>
  </div>
  </div>
  <div>
    <Footer/>
  </div>
</template>
