<template>
  <div class="p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
      <!-- Loading -->
      <div v-if="loading" class="text-center text-gray-500">
        Đang tải chi tiết tour...
      </div>

      <!-- Nội dung chính -->
      <div v-else class="space-y-6">
        <!-- Tiêu đề -->
        <h1 class="text-3xl font-semibold text-center text-gray-600 mb-6">{{ tour.title }}</h1>

        <!-- Hình ảnh -->
        <div v-if="tour.image">
          <img
            :src="fullImage(tour.image)"
            alt="Hình ảnh tour"
            class="w-full h-96 object-cover rounded-lg"
            @error="event => event.target.src = fallbackImg"
          />
        </div>

        <!-- Thông tin chi tiết -->
        <div class="space-y-4">
          <!-- Mô tả -->
          <div>
            <h2 class="text-2xl font-semibold text-gray-800">Mô tả</h2>
            <div v-html="tour.description" class="text-gray-600 mt-2"></div>
          </div>

          <!-- Giá -->
          <div>
            <h2 class="text-2xl font-semibold text-gray-800">Giá</h2>
            <p class="text-gray-600 mt-2">
              {{ tour.price ? tour.price.toLocaleString('vi-VN') + ' VND' : 'Chưa rõ' }}
            </p>
          </div>

          <!-- Thời gian -->
          <div>
            <h2 class="text-2xl font-semibold text-gray-800">Thời gian</h2>
            <p class="text-gray-600 mt-2">
              Từ {{ formatDate(tour.start_date) }} đến {{ formatDate(tour.end_date) }}
            </p>
          </div>

          <!-- Địa điểm -->
          <div v-if="tour.location">
            <h2 class="text-2xl font-semibold text-gray-800">Địa điểm</h2>
            <p class="text-gray-600 mt-2">
              <a
                :href="`/locations/${tour.location.id}`"
                class="text-blue-500 hover:underline"
              >
                {{ tour.location.name }}
              </a>
            </p>
          </div>
        </div>

        <!-- Nút quay lại -->
        <div class="flex justify-end mt-6">
          <a
            href="/tours"
            class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors"
          >
            Quay lại danh sách tour
          </a>
        </div>
      </div>
    </div>

    <!-- Đặt tour -->
    <div class="max-w-2xl mx-auto mt-8">
    <!-- Nút mở form -->
    <button
      @click="showBookingForm = !showBookingForm"
      class="flex items-center gap-2 text-white bg-green-600 hover:bg-green-700 px-5 py-2 rounded-lg shadow transition"
    >
      <span>📅 Đặt tour ngay</span>
      <svg
        :class="{ 'rotate-180': showBookingForm }"
        class="w-4 h-4 transition-transform duration-300"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Form đặt tour -->
    <transition name="fade-slide">
      <div
        v-if="showBookingForm"
        class="bg-white border mt-4 p-6 rounded-xl shadow-md space-y-4"
      >
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📝 Điền thông tin đặt tour</h2>

        <form @submit.prevent="submitBooking" class="space-y-4">
          <div>
            <label class="block text-gray-600 mb-1">Chọn ngày đặt</label>
            <input
              type="date"
              v-model="booking.booking_date"
              class="border border-gray-300 p-2 rounded-lg w-full focus:ring focus:ring-green-200"
              required
            />
          </div>

          <div>
            <label class="block text-gray-600 mb-1">Số lượng người</label>
            <input
              type="number"
              v-model="booking.quantity"
              min="1"
              class="border border-gray-300 p-2 rounded-lg w-full focus:ring focus:ring-green-200"
              required
            />
          </div>

          <button
            type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Xác nhận đặt tour
          </button>
        </form>
      </div>
    </transition>
  </div>
</div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

export default {
  layout: DefaultLayout,
  props: ['tourId'],
  setup(props) {
    const tour = ref({});
    const loading = ref(true);
    const fallbackImg = '/images/placeholder.jpg';
    const showBookingForm = ref(false);

    const fetchTour = async () => {
      try {
        const response = await axios.get(`/api/tours/${props.tourId}`);
        tour.value = response.data;
      } catch (error) {
        console.error('Lỗi khi lấy chi tiết tour:', error);
      } finally {
        loading.value = false;
      }
    };

    const booking = ref({
      booking_date: '',
      quantity: 1
    });

    const submitBooking = async () => {
      try {
      const total = tour.value.price * booking.value.quantity;
        await axios.post(`/api/bookings`, {
          tour_id: props.tourId,
          booking_date: booking.value.booking_date,
          quantity: booking.value.quantity,
          total_price: total,
        });
        alert('✅ Đặt tour thành công!');
        booking.value.booking_date = '';
        booking.value.quantity = 1;
        showBookingForm.value = false;
      } catch (error) {
        console.error(error);
        alert('❌ Lỗi khi đặt tour. Vui lòng thử lại sau.');
      }
    };

    const fullImage = (path) => {
      if (!path) return fallbackImg;
      return path.startsWith('http') ? path : `http://localhost:8000${path}`;
    };

    const formatDate = (date) => {
      return date ? new Date(date).toLocaleDateString('vi-VN') : 'Không rõ';
    };

    onMounted(fetchTour);

    return {
      tour,
      loading,
      fallbackImg,
      fullImage,
      formatDate,
      booking,
      submitBooking,
      showBookingForm
    };
  }
};
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.4s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>