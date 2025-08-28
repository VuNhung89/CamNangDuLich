<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'

const props = defineProps({
  userId: [Number, String]
})

const user = ref(null)
const loading = ref(true)
const updating = ref(false)
const errors = ref({})
const avatarFile = ref(null)
const avatarPreview = ref(null)

const fetchUser = async () => {
  try {
    const res = await axios.get(`/api/admin/users/${props.userId}`)
    user.value = res.data
    avatarPreview.value = null // reset preview khi load lại
  } catch (err) {
    console.error('Lỗi tải thông tin người dùng:', err)
  } finally {
    loading.value = false
  }
}

const onAvatarChange = (e) => {
  const file = e.target.files[0]
  avatarFile.value = file
  if (file) {
    avatarPreview.value = URL.createObjectURL(file)
  }
}

const updateUser = async () => {
  try {
    updating.value = true
    errors.value = {}

    const formData = new FormData()
    formData.append('name', user.value.name)
    formData.append('email', user.value.email)
    formData.append('role', user.value.role)
    formData.append('bio', user.value.bio || '')
    formData.append('dob', user.value.dob || '')
    formData.append('is_active', user.value.is_active ? 1 : 0)
    if (avatarFile.value) {
      formData.append('avatar', avatarFile.value)
    }

    await axios.post(`/api/admin/users/${props.userId}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    alert('✅ Cập nhật thành công')
    await fetchUser() // gọi lại để cập nhật avatar mới
  } catch (err) {
    errors.value = err.response?.data?.errors || {}
    console.error('Lỗi cập nhật:', err)
  } finally {
    updating.value = false
  }
}

onMounted(fetchUser)
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="flex-1 bg-slate-100 p-6">
      <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-semibold text-blue-800 mb-4">👤 Thông tin người dùng</h1>

        <div v-if="loading" class="text-center text-gray-500 py-6">Đang tải...</div>

        <div v-else>
          <div class="space-y-4">
            <!-- Avatar -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
              <div class="flex items-center gap-4 mt-2">
                <img
                  v-if="user.avatar || avatarPreview"
                  :src="avatarPreview || `${user.avatar}?t=${Date.now()}`"
                  alt="Avatar"
                  class="w-16 h-16 rounded-full border object-cover"
                />
                <input type="file" @change="onAvatarChange" />
              </div>
              <p v-if="errors.avatar" class="text-red-500 text-sm mt-1">{{ errors.avatar[0] }}</p>
            </div>

            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Tên</label>
              <input v-model="user.name" type="text" class="w-full border px-3 py-2 rounded mt-1" />
              <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="user.email" type="email" class="w-full border px-3 py-2 rounded mt-1" />
              <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
            </div>

            <!-- Email verified -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Email xác thực lúc</label>
              <input
                :value="user.email_verified_at || 'Chưa xác thực'"
                type="text"
                class="w-full border px-3 py-2 rounded mt-1 bg-gray-100"
                disabled
              />
            </div>

            <!-- Bio -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Giới thiệu</label>
              <textarea v-model="user.bio" class="w-full border px-3 py-2 rounded mt-1" rows="3"></textarea>
            </div>

            <!-- Ngày sinh -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Ngày sinh</label>
              <input v-model="user.dob" type="date" class="w-full border px-3 py-2 rounded mt-1" />
            </div>

            <!-- Vai trò -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Vai trò</label>
              <select v-model="user.role" class="w-full border px-3 py-2 rounded mt-1">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>

            <!-- Trạng thái -->
            <div class="flex items-center gap-2">
              <input v-model="user.is_active" type="checkbox" class="form-checkbox" />
              <label class="text-sm font-medium text-gray-700">Tài khoản đang hoạt động</label>
            </div>

            <!-- Nút cập nhật -->
            <div class="pt-4">
              <button
                @click="updateUser"
                :disabled="updating"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded disabled:opacity-50"
              >
                {{ updating ? 'Đang cập nhật...' : '💾 Lưu thay đổi' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>
