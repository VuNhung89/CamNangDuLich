<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const events = ref([]);
const loading = ref(true);
const imageFile = ref(null);
const editingId = ref(null);

const form = ref({
  title: '',
  location: '',
  date: '',
  description: '',
  image: null
});

const fetchEvents = async () => {
  loading.value = true;
  const res = await axios.get('/api/admin/events');
  events.value = res.data;
  loading.value = false;
};

const handleImageUpload = (e) => {
  imageFile.value = e.target.files[0];
};

const resetForm = () => {
  form.value = {
    title: '',
    location: '',
    date: '',
    description: '',
    image: null,
  };
  imageFile.value = null;
  editingId.value = null;
};

const createOrUpdateEvent = async () => {
  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('location', form.value.location);
  formData.append('date', form.value.date);
  formData.append('description', form.value.description);
  if (imageFile.value) {
    formData.append('image', imageFile.value);
  }

  try {
    if (editingId.value) {
      await axios.post(`/api/admin/events/${editingId.value}`, formData);
      alert('Cập nhật sự kiện thành công');
    } else {
      await axios.post('/api/admin/events', formData);
      alert('Tạo sự kiện thành công');
    }
    resetForm();
    fetchEvents();
  } catch (err) {
    console.error('Lỗi:', err.response?.data || err.message);
  }
};

const editEvent = (event) => {
  editingId.value = event.id;
  form.value = {
    title: event.title,
    location: event.location,
    date: event.date,
    description: event.description,
    image: event.image,
  };
};

const deleteEvent = async (id) => {
  if (confirm('Bạn có chắc muốn xóa sự kiện này?')) {
    await axios.delete(`/api/admin/events/${id}`);
    events.value = events.value.filter(e => e.id !== id);
  }
};

onMounted(() => {
  fetchEvents();
});
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="p-6 flex-1 bg-slate-100">
      <h1 class="text-2xl font-semibold text-green-800 mb-4">🎉 Quản lý Sự kiện</h1>

      <!-- Form -->
      <div class="bg-white p-4 rounded shadow mb-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-700">
          {{ editingId ? '✏️ Cập nhật sự kiện' : '➕ Thêm sự kiện' }}
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Tiêu đề</label>
            <input v-model="form.title" class="w-full border rounded p-2" type="text" />
          </div>
          <div>
            <label class="block text-sm font-medium">Địa điểm</label>
            <input v-model="form.location" class="w-full border rounded p-2" type="text" />
          </div>
          <div>
            <label class="block text-sm font-medium">Ngày tổ chức</label>
            <input v-model="form.date" class="w-full border rounded p-2" type="date" />
          </div>
          <div>
            <label class="block text-sm font-medium">Ảnh</label>
            <input @change="handleImageUpload" class="w-full border rounded p-2" type="file" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Mô tả</label>
            <textarea v-model="form.description" class="w-full border rounded p-2" rows="3" />
          </div>
        </div>
        <div class="flex gap-3">
          <button @click="createOrUpdateEvent" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            {{ editingId ? 'Lưu thay đổi' : 'Tạo sự kiện' }}
          </button>
          <button v-if="editingId" @click="resetForm" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Hủy
          </button>
        </div>
      </div>

      <!-- Table -->
      <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải...</div>
      <div v-else class="overflow-auto rounded shadow">
        <table class="min-w-full text-sm bg-white">
          <thead class="bg-gradient-to-r  from-emerald-500 to-green-500 text-white">
            <tr>
              <th class="text-left px-4 py-2">Tiêu đề</th>
              <th class="text-left px-4 py-2">Địa điểm</th>
              <th class="text-left px-4 py-2">Ngày</th>
              <th class="text-left px-4 py-2">Ảnh</th>
              <th class="text-left px-4 py-2">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="event in events" :key="event.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2 font-semibold text-gray-800">{{ event.title }}</td>
              <td class="px-4 py-2 text-gray-600">{{ event.location }}</td>
              <td class="px-4 py-2 text-gray-600">{{ event.date }}</td>
              <td class="px-4 py-2">
                <img :src="event.image?.startsWith('http') || event.image?.startsWith('/image') ? event.image : `/storage/${event.image}`" class="w-16 h-10 object-cover" />
              </td>
              <td class="px-4 py-2">
                <button @click="editEvent(event)" class="text-blue-600 hover:underline mr-2">Sửa</button>
                <button @click="deleteEvent(event.id)" class="text-red-600 hover:underline">Xóa</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <Footer />
</template>
