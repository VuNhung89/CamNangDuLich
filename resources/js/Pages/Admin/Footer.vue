<template>
  <footer class="bg-white border-t mt-auto py-3 text-center text-gray-500 text-sm shadow-sm">
    <div>
      © {{ currentYear }} <span class="font-semibold text-gray-800">Vu Thi Nhung</span> — All rights reserved.
    </div>
  </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear();
</script>
