<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

defineProps({ bookings: Array })

const selectedBooking = ref(null)
const showModal = ref(false)

const openModal = (booking) => {
  selectedBooking.value = booking
  showModal.value = true
}

const closeModal = () => {
  selectedBooking.value = null
  showModal.value = false
}

const cancelBooking = async (id) => {
  if (!confirm('❗ Bạn có chắc chắn muốn hủy booking này không?')) return

  try {
    await axios.delete(`/api/bookings/${id}`)
    router.reload({ only: ['bookings'] })
  } catch (error) {
    alert('⚠️ Hủy booking thất bại.')
    console.error(error)
  }
}
</script>

<template>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-semibold mb-6 text-indigo-700">🧾 Lịch sử booking của bạn</h1>

    <div v-if="!bookings || bookings.length === 0" class="text-gray-500 text-lg text-center">
      Bạn chưa có booking nào.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="booking in bookings"
        :key="booking.id"
        class="bg-white shadow-md border rounded-xl p-6 transition hover:shadow-xl"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-blue-800">
            🧳 {{ booking.tour?.title || booking.hotel?.name }}
          </h2>
          <span
            class="px-2 py-1 text-sm rounded-full"
            :class="{
              'bg-green-100 text-green-700': booking.status === 'confirmed',
              'bg-yellow-100 text-yellow-700': booking.status === 'pending',
              'bg-red-100 text-red-700': booking.status === 'cancelled'
            }"
          >
            {{ booking.status }}
          </span>
        </div>
        <p class="text-sm text-gray-600 mb-1">
          📅 Ngày đặt: <strong>{{ booking.booking_date }}</strong>
        </p>
        <p class="text-sm text-gray-600 mb-1">
          💰 Tổng tiền: <strong>{{ booking.total_price.toLocaleString() }}₫</strong>
        </p>
        <div class="mt-4 flex gap-2">
          <button
            @click="openModal(booking)"
            class="px-4 py-1.5 text-sm rounded bg-blue-600 hover:bg-blue-700 text-white"
          >
            Xem chi tiết
          </button>
          <button
            @click="cancelBooking(booking.id)"
            class="px-4 py-1.5 text-sm rounded bg-red-600 hover:bg-red-700 text-white"
          >
            Hủy booking
          </button>
        </div>
      </div>
    </div>

    <!-- Modal chi tiết -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative animate-fade-in">
        <button
          @click="closeModal"
          class="absolute top-3 right-3 text-gray-600 hover:text-black text-xl"
        >
          &times;
        </button>

        <h2 class="text-xl font-bold mb-4 text-blue-800">📋 Chi tiết Booking</h2>

        <ul class="space-y-2 text-gray-700 text-sm">
          <li><strong>Ngày đặt:</strong> {{ selectedBooking.booking_date }}</li>
          <li><strong>Trạng thái:</strong> {{ selectedBooking.status }}</li>
          <li v-if="selectedBooking.tour"><strong>Tour:</strong> {{ selectedBooking.tour.title }}</li>
          <li v-if="selectedBooking.hotel"><strong>Khách sạn:</strong> {{ selectedBooking.hotel.name }}</li>
          <li><strong>Tổng tiền:</strong> {{ selectedBooking.total_price.toLocaleString() }}₫</li>
        </ul>

        <div class="mt-6 text-right">
          <button
            @click="closeModal"
            class="px-4 py-1.5 bg-gray-300 hover:bg-gray-400 rounded text-sm"
          >
            Đóng
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>
