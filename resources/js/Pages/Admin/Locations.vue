<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const locations = ref([]);
const loading = ref(true);
const imageFile = ref(null);
const editingId = ref(null); // để kiểm soát khi đang sửa

const form = ref({
  name: '',
  description: '',
  transportation: '',
  booking_info: '',
  image: null,
});

const fetchLocations = async () => {
  loading.value = true;
  const res = await axios.get('/api/admin/locations');
  locations.value = res.data;
  loading.value = false;
};

const handleImageUpload = (e) => {
  imageFile.value = e.target.files[0];
};

const resetForm = () => {
  form.value = {
    name: '',
    description: '',
    transportation: '',
    booking_info: '',
    image: null,
  };
  imageFile.value = null;
  editingId.value = null;
};

const createOrUpdateLocation = async () => {
  const formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('description', form.value.description);
  formData.append('transportation', form.value.transportation);
  formData.append('booking_info', form.value.booking_info);
  if (imageFile.value) {
    formData.append('image', imageFile.value);
  }

  try {
    if (editingId.value) {
      await axios.post(`/api/admin/locations/${editingId.value}`, formData);
      alert('Cập nhật địa điểm thành công');
    } else {
      await axios.post('/api/admin/locations', formData);
      alert('Tạo địa điểm thành công');
    }
    resetForm();
    fetchLocations();
  } catch (err) {
    console.error('Lỗi:', err.response?.data || err.message);
  }
};

const editLocation = (loc) => {
  editingId.value = loc.id;
  form.value = {
    name: loc.name,
    description: loc.description,
    transportation: loc.transportation,
    booking_info: loc.booking_info,
    image: loc.image,
  };
};

const deleteLocation = async (id) => {
  if (confirm('Bạn có chắc muốn xóa địa điểm này?')) {
    await axios.delete(`/api/admin/locations/${id}`);
    locations.value = locations.value.filter(l => l.id !== id);
  }
};

onMounted(() => {
  fetchLocations();
});
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="p-6 flex-1 bg-slate-100">
      <h1 class="text-2xl font-semibold text-green-800 mb-4">🗺️ Quản lý Địa điểm</h1>

      <!-- Form -->
      <div class="bg-white p-4 rounded shadow mb-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-700">
          {{ editingId ? '✏️ Cập nhật địa điểm' : '➕ Thêm địa điểm' }}
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Tên địa điểm</label>
            <input v-model="form.name" class="w-full border rounded p-2" type="text" />
          </div>
          <div>
            <label class="block text-sm font-medium">Ảnh</label>
            <input @change="handleImageUpload" class="w-full border rounded p-2" type="file" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Mô tả</label>
            <textarea v-model="form.description" class="w-full border rounded p-2" rows="3" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Phương tiện di chuyển</label>
            <textarea v-model="form.transportation" class="w-full border rounded p-2" rows="2" />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">Thông tin đặt chỗ</label>
            <textarea v-model="form.booking_info" class="w-full border rounded p-2" rows="2" />
          </div>
        </div>
        <div class="flex gap-3">
          <button @click="createOrUpdateLocation" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            {{ editingId ? 'Lưu thay đổi' : 'Tạo địa điểm' }}
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
          <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
            <tr>
              <th class="text-left px-4 py-2">Tên</th>
              <th class="text-left px-4 py-2">Ảnh</th>
              <th class="text-left px-4 py-2">Mô tả</th>
              <th class="text-left px-4 py-2">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="loc in locations" :key="loc.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2 font-semibold text-gray-800">{{ loc.name }}</td>
              <td class="px-4 py-2">
                <img :src="loc.image?.startsWith('http') || loc.image?.startsWith('/image') ? loc.image : `/storage/${loc.image}`" class="w-16 h-10 object-cover" />
              </td>
              <td class="px-4 py-2 text-gray-600 truncate max-w-sm">{{ loc.description }}</td>
              <td class="px-4 py-2">
                <button @click="editLocation(loc)" class="text-blue-600 hover:underline mr-2">Sửa</button>
                <button @click="deleteLocation(loc.id)" class="text-red-600 hover:underline">Xóa</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <Footer />
</template>
