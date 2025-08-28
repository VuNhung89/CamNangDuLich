<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';
import { Link } from '@inertiajs/vue3';

const users = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = 10;

const showForm = ref(true);
const newUser = ref({
  name: '',
  email: '',
  password: '',
  role: 'user'
});
const creating = ref(false);
const errors = ref({});

const selectedUser = ref(null);
const editing = ref(false);
const editErrors = ref({});

const fetchUsers = async () => {
  try {
    loading.value = true;
    const res = await axios.get('/api/admin/users', {
      params: { page: currentPage.value, per_page: perPage }
    });
    users.value = res.data.data;
    currentPage.value = res.data.current_page;
    lastPage.value = res.data.last_page;
  } catch (err) {
    console.error('Lỗi tải người dùng:', err);
  } finally {
    loading.value = false;
  }
};

const createUser = async () => {
  try {
    creating.value = true;
    errors.value = {};
    await axios.post('/api/admin/users', newUser.value);
    await fetchUsers();
    newUser.value = { name: '', email: '', password: '', role: 'user' };
    showForm.value = false;
    alert('Tạo người dùng thành công!');
  } catch (err) {
    errors.value = err.response?.data?.errors || {};
  } finally {
    creating.value = false;
  }
};

const deleteUser = async (id) => {
  if (confirm('Bạn chắc chắn muốn xóa người dùng này?')) {
    try {
      await axios.delete(`/api/admin/users/${id}`);
      users.value = users.value.filter(u => u.id !== id);
    } catch (err) {
      console.error('Lỗi xóa người dùng:', err);
    }
  }
};

const openEdit = (user) => {
  selectedUser.value = { ...user };
  editing.value = true;
};

const updateUser = async () => {
  try {
    editErrors.value = {};
    await axios.put(`/api/admin/users/${selectedUser.value.id}`, selectedUser.value);
    alert('Cập nhật người dùng thành công!');
    selectedUser.value = null;
    await fetchUsers();
  } catch (err) {
    editErrors.value = err.response?.data?.errors || {};
  }
};

const goToPage = async (page) => {
  if (page >= 1 && page <= lastPage.value) {
    currentPage.value = page;
    await fetchUsers();
  }
};

onMounted(fetchUsers);
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="flex-1 bg-slate-100 p-6">
      <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-semibold text-green-800 mb-6">👥 Quản lý người dùng</h1>

        <button
          @click="showForm = !showForm"
          class="mb-6 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold"
        >
          {{ showForm ? 'Đóng form' : '➕ Thêm người dùng mới' }}
        </button>

        <!-- FORM TẠO USER -->
        <div v-if="showForm" class="bg-white shadow rounded-xl p-6 mb-8 space-y-4">
          <div>
            <label class="block text-sm font-medium">Tên</label>
            <input v-model="newUser.name" type="text" class="w-full border rounded px-3 py-2" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium">Email</label>
            <input v-model="newUser.email" type="email" class="w-full border rounded px-3 py-2" />
            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium">Mật khẩu</label>
            <input v-model="newUser.password" type="password" class="w-full border rounded px-3 py-2" />
            <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium">Vai trò</label>
            <select v-model="newUser.role" class="w-full border rounded px-3 py-2">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <button
            @click="createUser"
            :disabled="creating"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold disabled:opacity-50"
          >
            {{ creating ? 'Đang tạo...' : '✅ Tạo người dùng' }}
          </button>
        </div>

        <!-- TABLE NGƯỜI DÙNG -->
        <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải...</div>

        <div v-else>
          <table class="min-w-full bg-white border rounded-xl shadow text-sm">
            <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
              <tr>
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Tên</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-left px-4 py-3">Vai trò</th>
                <th class="text-left px-4 py-3">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2 font-semibold text-gray-700">{{ user.id }}</td>
                <td class="px-4 py-2 flex items-center gap-2">
                  <img :src="user.avatar || '/image/placeholder.jpg'" class="w-8 h-8 rounded-full border" />
                  <span class="text-gray-800">{{ user.name }}</span>
                </td>
                <td class="px-4 py-2 text-gray-600">{{ user.email }}</td>
                <td class="px-4 py-2">
                  <span
                    :class="[user.role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700',
                    'text-xs font-medium px-3 py-1 rounded-full']"
                  >
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-4 py-2 flex gap-2">
                  <Link :href="`/admin/users/${user.id}`" class="text-blue-600 hover:underline">Xem</Link>
                  <button class="text-green-600 hover:underline" @click="openEdit(user)">Sửa</button>
                  <button class="text-red-600 hover:underline" @click="deleteUser(user.id)">Xóa</button>
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
  </div>
  <Footer />

  <!-- MODAL CHỈNH SỬA -->
  <div v-if="selectedUser" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg relative">
      <button class="absolute top-2 right-2 text-gray-500" @click="selectedUser = null">✖</button>
      <h2 class="text-xl font-semibold mb-4 text-blue-800">✏️ Chỉnh sửa người dùng</h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Tên</label>
          <input v-model="selectedUser.name" class="w-full border rounded px-3 py-2" />
          <p v-if="editErrors.name" class="text-red-500 text-sm mt-1">{{ editErrors.name[0] }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input v-model="selectedUser.email" class="w-full border rounded px-3 py-2" />
          <p v-if="editErrors.email" class="text-red-500 text-sm mt-1">{{ editErrors.email[0] }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium">Vai trò</label>
          <select v-model="selectedUser.role" class="w-full border rounded px-3 py-2">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="pt-4 text-right">
          <button
            @click="updateUser"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold"
          >
            💾 Lưu thay đổi
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
