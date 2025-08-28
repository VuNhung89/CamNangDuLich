<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register', {
    onSuccess: () => {
      form.reset('password', 'password_confirmation');
    },
    onError: (errors) => {
      form.errors = errors;
    },
  });
};

</script>

<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="flex w-full max-w-screen-md bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Bên trái: Ảnh nền -->
      <div class="w-1/2 hidden md:block">
        <img src="/image/image.png" alt="Register background" class="w-full h-full object-cover" />
      </div>

      <!-- Bên phải: Form -->
      <div class="w-full md:w-1/2 flex items-center justify-center p-6">
        <div class="w-full">
          <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng Ký</h2>
          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <input
                v-model="form.name"
                type="text"
                placeholder="Tên của bạn"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
              <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
              <input
                v-model="form.email"
                type="email"
                placeholder="Email của bạn"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
              <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
            </div>
            <div>
              <input
                v-model="form.password"
                type="password"
                placeholder="Mật khẩu"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
              <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
            </div>
            <div>
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Xác nhận mật khẩu"
                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <button
              type="submit"
              class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-200"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Đang xử lý...' : 'ĐĂNG KÝ' }}
            </button>
          </form>
          <p class="text-center text-sm text-gray-600 mt-4">
            Đã có tài khoản?
            <a href="/login" class="text-blue-600 font-semibold hover:underline">Đăng nhập</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>