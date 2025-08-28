<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const hotels = ref([]);
const locations = ref([]);
const loading = ref(true);
const imageFile = ref(null);

const form = ref({
  location_id: '',
  name: '',
  address: '',
  price: '',
  description: '',
  image: null
});

const fetchHotels = async () => {
  loading.value = true;
  const res = await axios.get('/api/admin/hotels');
  hotels.value = res.data;
  loading.value = false;
};

const fetchLocations = async () => {
  const res = await axios.get('/api/locations');
  locations.value = res.data;
};

const handleImageUpload = (e) => {
  imageFile.value = e.target.files[0];
};

const createHotel = async () => {
  try {
    const formData = new FormData();
    formData.append('location_id', form.value.location_id);
    formData.append('name', form.value.name);
    formData.append('address', form.value.address);
    formData.append('price', form.value.price);
    formData.append('description', form.value.description);
    if (imageFile.value) {
      formData.append('image', imageFile.value);
    }

    await axios.post('/api/admin/hotels', formData);
    alert('Tạo khách sạn thành công');
    fetchHotels();
    form.value = { location_id: '', name: '', address: '', price: '', description: '', image: null };
    imageFile.value = null;
  } catch (err) {
    console.error('Lỗi tạo khách sạn:', err.response?.data || err.message);
  }
};

const deleteHotel = async (id) => {
  if (confirm('Bạn có chắc muốn xóa khách sạn này?')) {
    await axios.delete(`/api/admin/hotels/${id}`);
    hotels.value = hotels.value.filter(h => h.id !== id);
  }
};

onMounted(() => {
  fetchHotels();
  fetchLocations();
});
</script>

<template>
  <div class="flex">
    <Sidebar/>
    <div class="p-6 flex-1 bg-slate-100">
    <h1 class="text-2xl font-semibold text-green-800 mb-4">🏨 Quản lý Khách sạn</h1>

    <div class="bg-white p-4 rounded shadow mb-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-700">Thêm khách sạn</h2>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Địa điểm</label>
          <select v-model="form.location_id" class="w-full border rounded p-2">
            <option value="" disabled>-- Chọn địa điểm --</option>
            <option v-for="loc in locations" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Tên khách sạn</label>
          <input v-model="form.name" class="w-full border rounded p-2" type="text" />
        </div>
        <div>
          <label class="block text-sm font-medium">Địa chỉ</label>
          <input v-model="form.address" class="w-full border rounded p-2" type="text" />
        </div>
        <div>
          <label class="block text-sm font-medium">Giá</label>
          <input v-model="form.price" class="w-full border rounded p-2" type="number" />
        </div>
        <div>
          <label class="block text-sm font-medium">Ảnh</label>
          <input @change="handleImageUpload" class="w-full border rounded p-2" type="file" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium">Mô tả</label>
          <textarea v-model="form.description" class="w-full border rounded p-2" rows="4"></textarea>
        </div>
      </div>
      <button @click="createHotel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Tạo khách sạn</button>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải...</div>

    <div v-else class="overflow-auto rounded shadow">
      <table class="min-w-full text-sm bg-white">
        <thead class="bg-gradient-to-r  from-emerald-500 to-green-500 text-white">
          <tr>
            <th class="text-left px-4 py-2">Tên</th>
            <th class="text-left px-4 py-2">Địa điểm</th>
            <th class="text-left px-4 py-2">Giá</th>
            <th class="text-left px-4 py-2">Ảnh</th>
            <th class="text-left px-4 py-2">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="hotel in hotels" :key="hotel.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 font-semibold text-gray-800">{{ hotel.name }}</td>
            <td class="px-4 py-2 text-gray-600">{{ hotel.location?.name || hotel.location_id }}</td>
            <td class="px-4 py-2 text-gray-600">{{ hotel.price.toLocaleString() }} đ</td>
            <td class="px-4 py-2">
              <img
                :src="hotel.image.startsWith('http') || hotel.image.startsWith('/image') ? hotel.image : `/storage/${hotel.image}`"
                class="w-16 h-10 object-cover"
              />
            </td>
            <td class="px-4 py-2">
              <button @click="deleteHotel(hotel.id)" class="text-red-600 hover:underline">Xóa</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </div>
  <div>
    <Footer/>
  </div>
  
</template>
