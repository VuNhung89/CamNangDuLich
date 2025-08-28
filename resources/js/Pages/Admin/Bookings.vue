<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'

const bookings = ref([])
const loading = ref(true)

const fetchBookings = async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/admin/bookings')
    bookings.value = res.data
  } catch (err) {
    console.error('Lỗi tải bookings:', err.response?.data || err.message)
  }
  loading.value = false
}

const approveBooking = async (id) => {
  if (confirm('Bạn có chắc muốn duyệt booking này?')) {
    await axios.post(`/api/admin/bookings/${id}/approve`)
    fetchBookings()
  }
}

const deleteBooking = async (id) => {
  if (confirm('Bạn có chắc muốn xóa booking này?')) {
    await axios.delete(`/api/admin/bookings/${id}`)
    bookings.value = bookings.value.filter(b => b.id !== id)
  }
}

onMounted(() => {
  fetchBookings()
})
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="p-6 flex-1 bg-slate-100">
      <h1 class="text-2xl font-semibold text-green-800 mb-4">📦 Quản lý Booking</h1>

      <div v-if="loading" class="text-center py-10 text-gray-500">Đang tải...</div>

      <div v-else class="overflow-auto rounded shadow">
        <table class="min-w-full text-sm bg-white">
          <thead class="bg-gradient-to-r from-emerald-500 to-green-500 text-white">
            <tr>
              <th class="text-left px-4 py-2">Tên khách</th>
              <th class="text-left px-4 py-2">Khách sạn</th>
              <th class="text-left px-4 py-2">Ngày nhận</th>
              <th class="text-left px-4 py-2">Ngày trả</th>
              <th class="text-left px-4 py-2">Trạng thái</th>
              <th class="text-left px-4 py-2">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="booking in bookings" :key="booking.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-2">{{ booking.user?.name || 'Không rõ' }}</td>
              <td class="px-4 py-2">{{ booking.hotel?.name || 'Không rõ' }}</td>
              <td class="px-4 py-2">{{ booking.check_in }}</td>
              <td class="px-4 py-2">{{ booking.check_out }}</td>
              <td class="px-4 py-2">
                <span :class="booking.status === 'pending' ? 'text-yellow-600' : 'text-green-600'">
                  {{ booking.status === 'pending' ? 'Chờ duyệt' : 'Đã duyệt' }}
                </span>
              </td>
              <td class="px-4 py-2 space-x-2">
                <button v-if="booking.status === 'pending'" @click="approveBooking(booking.id)" class="text-blue-600 hover:underline">Duyệt</button>
                <button @click="deleteBooking(booking.id)" class="text-red-600 hover:underline">Xóa</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <Footer />
</template>
