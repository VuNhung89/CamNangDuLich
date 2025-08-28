<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';

const tours = ref([]);
const locations = ref([]);
const editingId = ref(null);
const loading = ref(true);
const form = ref({
  location_id: '',
  title: '',
  description: '',
  price: '',
  start_date: '',
  end_date: '',
  image: ''
});

const resetForm = () => {
  form.value = {
    location_id: '',
    title: '',
    description: '',
    price: '',
    start_date: '',
    end_date: '',
    image: ''
  };
  imageFile.value = null;
  editingId.value = null;
};

const fetchTours = async () => {
  loading.value = true;
  const res = await axios.get('/api/admin/tours');
  tours.value = res.data.data;
  loading.value = false;
};

const fetchLocations = async () => {
  const res = await axios.get('/api/locations');
  locations.value = res.data;
};

const imageFile = ref(null);

const handleImageUpload = (event) => {
  imageFile.value = event.target.files[0];
};

const createOrUpdateTour = async () => {
  try {
    const formData = new FormData();
    formData.append('location_id', form.value.location_id);
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    formData.append('start_date', form.value.start_date);
    formData.append('end_date', form.value.end_date);
    if (imageFile.value) {
      formData.append('image', imageFile.value);
    }

    if (editingId.value) {
      await axios.post(`/api/admin/tours/${editingId.value}`, formData);
      alert('Cập nhật tour thành công');
    } else {
      await axios.post('/api/admin/tours', formData);
      alert('Tạo tour thành công');
    }

    await fetchTours();
    resetForm();
  } catch (err) {
    console.error('Lỗi:', err.response?.data || err.message);
  }
};

const editTour = (tour) => {
  editingId.value = tour.id;
  form.value = {
    location_id: tour.location_id,
    title: tour.title,
    description: tour.description,
    price: tour.price,
    start_date: tour.start_date,
    end_date: tour.end_date,
    image: tour.image, // giữ tên để hiển thị nhưng không upload lại trừ khi người dùng chọn ảnh mới
  };
  imageFile.value = null;
};

const deleteTour = async (id) => {
  if (confirm('Bạn có chắc muốn xóa tour này?')) {
    await axios.delete(`/api/admin/tours/${id}`);
    tours.value = tours.value.filter(t => t.id !== id);
  }
};

onMounted(() => {
  fetchTours();
  fetchLocations();
});
</script>

<template>
  <div class="flex">
    <Sidebar/>
    <div class="flex-1 p-6 bg-slate-100">
        <h1 class="text-2xl font-semibold text-green-800 mb-4">🗺️ Quản lý Tour</h1>

        <!-- Form tạo tour -->
        <div class="bg-white p-4 rounded shadow mb-6 space-y-4">
          <h2 class="text-lg font-semibold text-gray-700">Thêm tour mới</h2>

          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium">Địa điểm</label>
              <select v-model="form.location_id" class="w-full border rounded p-2">
                <option value="" disabled>-- Chọn địa điểm --</option>
                <option v-for="loc in locations" :key="loc.id" :value="loc.id">{{ loc.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Tiêu đề</label>
              <input v-model="form.title" class="w-full border rounded p-2" type="text" />
            </div>
            <div>
              <label class="block text-sm font-medium">Giá</label>
              <input v-model="form.price" class="w-full border rounded p-2" type="number" />
            </div>
            <div>
              <label class="block text-sm font-medium">Ảnh</label>
              <input @change="handleImageUpload" class="w-full border rounded p-2" type="file" />
            </div>
            <div>
              <label class="block text-sm font-medium">Ngày bắt đầu</label>
              <input v-model="form.start_date" class="w-full border rounded p-2" type="date" />
            </div>
            <div>
              <label class="block text-sm font-medium">Ngày kết thúc</label>
              <input v-model="form.end_date" class="w-full border rounded p-2" type="date" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium">Mô tả</label>
              <textarea v-model="form.description" class="w-full border rounded p-2" rows="4"></textarea>
            </div>
          </div>

          <button @click="createOrUpdateTour" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            {{ editingId ? 'Lưu thay đổi' : 'Tạo tour' }}
          </button>
          <button v-if="editingId" @click="resetForm" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Hủy
          </button>
        </div>

        <!-- Danh sách tour -->
        <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải...</div>

        <div v-else class="overflow-auto rounded shadow">
          <table class="min-w-full text-sm bg-white">
            <thead class="bg-gradient-to-r  from-emerald-500 to-green-500 text-white">
              <tr>
                <th class="text-left px-4 py-2">Tiêu đề</th>
                <th class="text-left px-4 py-2">Địa điểm</th>
                <th class="text-left px-4 py-2">Giá</th>
                <th class="text-left px-4 py-2">Ngày</th>
                <th class="text-left px-4 py-2">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="tour in tours" :key="tour.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2 font-semibold text-gray-800">{{ tour.title }}</td>
                <td class="px-4 py-2 text-gray-600">{{ tour.location?.name || 'Không rõ' }}</td>
                <td class="px-4 py-2 text-gray-600">{{ tour.price.toLocaleString() }} đ</td>
                <td class="px-4 py-2 text-gray-600">{{ tour.start_date }} - {{ tour.end_date }}</td>
                <td class="px-4 py-2">
                  <img
                    :src="tour.image.startsWith('http') || tour.image.startsWith('/image') ? tour.image : `/storage/${tour.image}`"
                    class="w-16 h-10 object-cover"
                  />
                </td>
                <td class="px-4 py-2">
                  <button @click="editTour(tour)" class="text-blue-600 hover:underline mr-2">Sửa</button>
                  <button @click="deleteTour(tour.id)" class="text-red-600 hover:underline">Xóa</button>
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
