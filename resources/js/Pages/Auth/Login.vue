<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie');
    form.post('/login', {
      onSuccess: () => {
        form.reset('password');
        if (form.remember) {
          localStorage.setItem('remembered_email', form.email);
        } else {
          localStorage.removeItem('remembered_email');
        }
      },
      onError: (errors) => {
        form.errors = errors;
      },
    });
  } catch (e) {
    console.error('Failed to fetch CSRF cookie:', e);
  }
};


onMounted(() => {
  const rememberedEmail = localStorage.getItem('remembered_email');
  if (rememberedEmail) {
    form.email = rememberedEmail;
    form.remember = true;
  }
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="flex w-full max-w-screen-md bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="w-1/2 hidden md:block">
        <img src="/image/image.png" alt="Login background" class="w-full h-full object-cover" />
      </div>
      <div class="w-full md:w-1/2 flex items-center justify-center p-6">
        <div class="w-full">
          <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng Nhập</h2>
          <form @submit.prevent="submit" class="space-y-5">
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
            <button
              type="submit"
              class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-200"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Đang xử lý...' : 'ĐĂNG NHẬP' }}
            </button>
          </form>
          <div class="flex justify-between items-center text-sm text-gray-600 mt-3">
            <div class="flex items-center">
              <input
                v-model="form.remember"
                type="checkbox"
                id="remember"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="remember" class="ml-2">Nhớ mật khẩu</label>
            </div>
            <a href="/forgot-password" class="hover:text-blue-600">Quên mật khẩu</a>
          </div>
          <p class="text-center text-sm text-gray-600 mt-4">
            Bạn chưa có tài khoản?
            <a href="/register" class="text-blue-600 font-semibold hover:underline">Đăng ký</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>