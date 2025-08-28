<template>
  <header class="bg-white shadow-sm h-16 flex items-center px-6 justify-between">
    <h1 class="font-semibold text-xl text-gray-800">Admin Dashboard</h1>
    <div class="flex items-center gap-4">
      <span class="text-sm text-gray-600">Xin chào, {{ user.name }}</span>
      <img :src="user.avatar" class="w-9 h-9 rounded-full border" alt="avatar" />
      <button @click="logout" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded text-sm shadow-sm">
        Đăng xuất
      </button>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const user = computed(() => usePage().props.auth.user);

const logout = () => {
  router.post('/logout');
};
</script>
