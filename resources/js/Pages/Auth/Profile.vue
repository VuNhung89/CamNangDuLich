<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const user = computed(() => page.props?.auth?.user || {});
const posts = ref([]);
const locations = ref([]);
const loadingUser = ref(false);
const loadingPosts = ref(true);
const loadingLocations = ref(true);
const activeTab = ref('profile');
const editMode = ref(false);

const postForm = useForm({ title: '', content: '', location_id: '', image: null });
const form = useForm({ name: '', email: '', avatar: null, bio: '', dob: '' });
const passwordForm = useForm({ current_password: '', new_password: '', new_password_confirmation: '' });

const errors = ref({
  posts: null,
  locations: null,
  createPost: null,
  deletePost: null,
  updateProfile: null,
  changePassword: null,
});
const success = ref(null);
const baseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000';

const fetchPosts = async () => {
  try {
    const res = await axios.get('/user/posts');
    posts.value = res.data;
    errors.value.posts = null;
  } catch (err) {
    errors.value.posts = 'Lỗi khi tải bài viết';
    console.error('Fetch posts error:', err);
  }
};

onMounted(async () => {
  try {
    await fetchPosts();
    form.name = user.value.name;
    form.email = user.value.email;
    form.avatar = null;
    form.bio = user.value.bio || '';
    form.dob = user.value.dob || '';
  } catch (err) {
    console.error('Lỗi user:', err);
  }

  try {
    const locationsRes = await axios.get('/api/locations');
    locations.value = locationsRes.data.data || locationsRes.data;
  } catch (err) {
    errors.value.locations = 'Lỗi khi tải địa điểm';
    console.error(err);
  } finally {
    loadingLocations.value = false;
  }

  loadingPosts.value = false;
});

const createPost = () => {
  if (!postForm.title || !postForm.content) {
    errors.value.createPost = 'Vui lòng nhập tiêu đề và nội dung';
    return;
  }
  postForm.post('/posts', {
    forceFormData: true,
    onSuccess: async () => {
    postForm.reset();
    success.value = 'Đăng bài thành công!';
    await fetchPosts();
    activeTab.value = 'posts';
},
    onError: (err) => {
      errors.value.createPost = err.message || 'Lỗi khi đăng bài';
      console.error(err);
    },
  });
};

const deletePost = (postId) => {
  if (!confirm('Bạn có chắc muốn xóa bài viết này?')) return;
  router.delete(`/posts/${postId}`, {
    onSuccess: () => {
      posts.value = posts.value.filter((post) => post.id !== postId);
      errors.value.deletePost = null;
      success.value = 'Xóa bài viết thành công!';
      setTimeout(() => (success.value = null), 3000);
    },
    onError: (err) => {
      errors.value.deletePost = err.response?.data?.message || 'Lỗi khi xóa bài viết';
      console.error(err);
    },
  });
};

const updateProfile = () => {
  if (!form.name || !form.email) {
    errors.value.updateProfile = 'Vui lòng nhập tên và email';
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.value.updateProfile = 'Email không hợp lệ';
    return;
  }
  form.post('/profile', {
    forceFormData: true,
    onSuccess: () => {
      editMode.value = false;
      errors.value.updateProfile = null;
      success.value = 'Cập nhật hồ sơ thành công!';
      setTimeout(() => (success.value = null), 3000);
    },
    onError: (err) => {
      errors.value.updateProfile = err.message || 'Lỗi khi cập nhật';
      console.error(err);
    },
  });
};

const changePassword = () => {
  if (!passwordForm.current_password || !passwordForm.new_password) {
    errors.value.changePassword = 'Vui lòng nhập mật khẩu hiện tại và mật khẩu mới';
    return;
  }
  if (passwordForm.new_password.length < 8) {
    errors.value.changePassword = 'Mật khẩu mới phải dài ít nhất 8 ký tự';
    return;
  }
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    errors.value.changePassword = 'Mật khẩu mới và xác nhận không khớp';
    return;
  }
  passwordForm.post('/change-password', {
    onSuccess: () => {
      passwordForm.reset();
      errors.value.changePassword = null;
      success.value = 'Đổi mật khẩu thành công!';
      setTimeout(() => (success.value = null), 3000);
    },
    onError: (err) => {
      errors.value.changePassword = err.message || 'Lỗi khi đổi mật khẩu';
      console.error(err);
    },
  });
};

const handleAvatarChange = (event) => {
  const file = event.target.files[0];
  if (file && !file.type.startsWith('image/')) {
    errors.value.updateProfile = 'Vui lòng chọn file ảnh cho avatar';
    return;
  }
  if (file && file.size > 2 * 1024 * 1024) {
    errors.value.updateProfile = 'Ảnh avatar không được vượt quá 2MB';
    return;
  }
  form.avatar = file;
};

const handlePostImageChange = (event) => {
  const file = event.target.files[0];
  if (file && !file.type.startsWith('image/')) {
    errors.value.createPost = 'Vui lòng chọn file ảnh cho bài viết';
    return;
  }
  if (file && file.size > 2 * 1024 * 1024) {
    errors.value.createPost = 'Ảnh bài viết không được vượt quá 2MB';
    return;
  }
  postForm.image = file;
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-100 to-teal-100 p-6 animate-fade-in">
    <div class="max-w-5xl mx-auto">
      <!-- Loading -->
      <div v-if="loadingUser || loadingPosts || loadingLocations" class="text-center text-gray-500 text-xl animate-pulse">
        Đang tải...
      </div>

      <!-- Nội dung -->
      <div v-else class="space-y-8">
        <!-- Header -->
        <div class="bg-blue-400 p-8 rounded-2xl shadow-xl text-center transform hover:scale-105 transition-transform duration-300">
          <div class="relative inline-block">
            <img
              :src="user.avatar ? `${baseUrl}${user.avatar}` : '/image/placeholder.jpg'"
              alt="User Avatar"
              class="w-40 h-40 rounded-full object-cover border-4 border-blue-300 shadow-lg"
            />
            <span
              class="absolute bottom-0 right-0 px-2 py-1 text-xs font-semibold text-white rounded-full"
              :class="user.role === 'admin' ? 'bg-red-500' : 'bg-teal-500'"
            >
              {{ user.role.toUpperCase() }}
            </span>
          </div>
          <h1 class="text-4xl text-gray-600 mt-6 font-semibold">{{ user.name }}</h1>
          <p class="text-lg text-gray-200 mt-2 font-serif italic animate-fade-in">
            {{ user.bio || 'Chưa có giới thiệu' }}
          </p>
          <button
            v-if="!editMode"
            @click="editMode = true"
            class="mt-6 bg-gradient-to-r from-yellow-400 to-pink-500 text-white px-8 py-3 rounded-full hover:from-pink-500 hover:to-yellow-400 transition-all duration-300 transform hover:scale-110"
          >
            Chỉnh sửa hồ sơ
          </button>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 bg-white p-4 rounded-2xl shadow-md">
          <button
            @click="activeTab = 'profile'"
            class="flex items-center px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="activeTab === 'profile' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-blue-100'"
          >
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-6 2.67-6 6v2h12v-2c0-3.33-2.67-6-6-6z"/>
            </svg>
            Hồ sơ
          </button>
          <button
            @click="activeTab = 'posts'"
            class="flex items-center px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="activeTab === 'posts' ? 'bg-teal-500 text-white' : 'text-gray-600 hover:bg-teal-100'"
          >
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 4h16v16H4V4zm2 2v12h12V6H6z"/>
            </svg>
            Bài viết
          </button>
          <button
            @click="activeTab = 'create-post'"
            class="flex items-center px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="activeTab === 'create-post' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-yellow-100'"
          >
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
            </svg>
            Đăng bài
          </button>
          <button
            @click="activeTab = 'password'"
            class="flex items-center px-6 py-3 rounded-full font-semibold transition-all duration-300"
            :class="activeTab === 'password' ? 'bg-pink-500 text-white' : 'text-gray-600 hover:bg-pink-100'"
          >
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C8.14 2 5 5.14 5 9v3H3v8h18v-8h-2V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v3H7V9c0-2.76 2.24-5 5-5zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
            </svg>
            Đổi mật khẩu
          </button>
        </div>

        <!-- Nội dung tab -->
        <div class="bg-white p-8 rounded-2xl shadow-xl animate-slide-in">
          <!-- Thông báo -->
          <p v-if="errors[activeTab]" class="text-red-500 mb-6 text-center font-semibold">{{ errors[activeTab] }}</p>
          <div v-if="success" class="text-green-500 mb-6 text-center font-semibold">{{ success }}</div>

          <!-- Tab Hồ sơ -->
          <div v-if="activeTab === 'profile'" class="space-y-6">
            <div v-if="editMode" class="space-y-4">
              <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-6 2.67-6 6v2h12v-2c0-3.33-2.67-6-6-6z"/>
                </svg>
                <div class="flex-1">
                  <label class="block text-gray-700 font-semibold">Tên</label>
                  <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-teal-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2v.01L12 12 4 6.01V6h16zM4 18V8l8 5 8-5v10H4z"/>
                </svg>
                <div class="flex-1">
                  <label class="block text-gray-700 font-semibold">Email</label>
                  <input
                    v-model="form.email"
                    type="email"
                    class="w-full px-4 py-3 border border-teal-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                  />
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                <div class="flex-1">
                  <label class="block text-gray-700 font-semibold">Giới thiệu</label>
                  <textarea
                    v-model="form.bio"
                    class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    rows="3"
                    maxlength="200"
                    placeholder="Viết vài dòng về bạn..."
                  ></textarea>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V7h14v12zm-7-9h2v2h-2v-2zm0 3h2v2h-2v-2zm-3-3h2v2H9v-2zm0 3h2v2H9v-2z"/>
                </svg>
                <div class="flex-1">
                  <label class="block text-gray-700 font-semibold">Ngày sinh</label>
                  <input
                    v-model="form.dob"
                    type="date"
                    class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
                <div class="flex-1">
                  <label class="block text-gray-700 font-semibold">Ảnh đại diện</label>
                  <input
                    type="file"
                    accept="image/*"
                    @change="handleAvatarChange"
                    class="w-full px-4 py-3 border border-yellow-300 rounded-lg"
                  />
                </div>
              </div>
              <div class="flex justify-center space-x-4">
                <button
                  @click="updateProfile"
                  class="bg-gradient-to-r from-blue-500 to-teal-500 text-white px-8 py-3 rounded-full hover:from-teal-500 hover:to-blue-500 transition-all duration-300 transform hover:scale-105"
                >
                  Lưu
                </button>
                <button
                  @click="editMode = false"
                  class="bg-gray-200 text-gray-700 px-8 py-3 rounded-full hover:bg-gray-300 transition-all duration-300 transform hover:scale-105"
                >
                  Hủy
                </button>
              </div>
            </div>
            <div v-else class="space-y-6">
              <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg">
                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-6 2.67-6 6v2h12v-2c0-3.33-2.67-6-6-6z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Tên</h2>
                  <p class="text-gray-600">{{ user.name }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-teal-50 rounded-lg">
                <svg class="w-8 h-8 text-teal-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2v.01L12 12 4 6.01V6h16zM4 18V8l8 5 8-5v10H4z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Email</h2>
                  <p class="text-gray-600">{{ user.email }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-yellow-50 rounded-lg">
                <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 7v2h-2V7h2zm0 4v6h-2v-6h2zm-8-7h2v13h-2V4zm-4 7h2v6H7v-6z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Ngày tham gia</h2>
                  <p class="text-gray-600">{{ new Date(user.created_at).toLocaleDateString('vi-VN') }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-pink-50 rounded-lg">
                <svg class="w-8 h-8 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Vai trò</h2>
                  <p class="text-gray-600">{{ user.role.toUpperCase() }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-purple-50 rounded-lg">
                <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Giới thiệu</h2>
                  <p class="text-gray-600">{{ user.bio || 'Chưa có giới thiệu' }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-blue-100 rounded-lg">
                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V7h14v12zm-7-9h2v2h-2v-2zm0 3h2v2h-2v-2zm-3-3h2v2H9v-2zm0 3h2v2H9v-2z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Ngày sinh</h2>
                  <p class="text-gray-600">{{ user.dob ? new Date(user.dob).toLocaleDateString('vi-VN') : 'Chưa cập nhật' }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-orange-50 rounded-lg">
                <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L1 21h22L12 2zm0 3.83L19.82 19H4.18L12 5.83zM11 8h2v6h-2V8zm0 7h2v2h-2v-2z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Huy hiệu</h2>
                  <div class="flex flex-wrap gap-2 mt-2">
                    <span
                      v-for="badge in user.badges || []"
                      :key="badge.name"
                      class="inline-flex items-center px-3 py-1 text-sm font-semibold text-white rounded-full"
                      :class="badge.icon === 'trophy' ? 'bg-yellow-500' : badge.icon === 'compass' ? 'bg-blue-500' : 'bg-green-500'"
                      :title="badge.name"
                    >
                      <svg v-if="badge.icon === 'star'" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                      </svg>
                      <svg v-else-if="badge.icon === 'compass'" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm1-13l-4 4 4 4 4-4-4-4z"/>
                      </svg>
                      <svg v-else-if="badge.icon === 'trophy'" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 2h-2V1H8v1H6v3h2v6l-2 2v1h2v7h6v-7h2v-1l-2-2V5h2V2zm-2 9.24l2 2V15H6v-1.76l2-2V5h8v6.24zM10 19v1h4v-1h-4z"/>
                      </svg>
                      {{ badge.name }}
                    </span>
                    <span v-if="!user.badges?.length" class="text-gray-600">Chưa có huy hiệu</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-lg cursor-pointer hover:bg-green-100 transition-colors duration-300" @click="activeTab = 'posts'">
                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Địa điểm đã ghé</h2>
                  <p class="text-gray-600">{{ user.visited_locations_count }} địa điểm</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab Bài viết -->
          <div v-if="activeTab === 'posts'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="post in posts"
              :key="post.id"
              class="relative p-4 bg-white rounded-2xl shadow-md border border-gray-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105"
            >
              <div class="relative overflow-hidden rounded-xl">
                <img
                  v-if="post.image"
                  :src="`${baseUrl}/${post.image.replace(/^\/+/, '')}`"
                  alt="Post Image"
                  class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110"
                  @error="event => event.target.src = '/image/placeholder.jpg'"
                />
                <div
                  class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"
                ></div>
              </div>
              <h2 class="text-xl font-semibold text-gray-800 mt-4">{{ post.title }}</h2>
              <div v-if="post.location" class="mt-2">
                <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-teal-500 rounded-full">
                  {{ post.location.name }}
                </span>
              </div>
              <div class="mt-4" v-html="post.content"></div>
              <div class="mt-4 flex justify-between items-center">
                <a
                  :href="`/posts/${post.id}`"
                  class="text-blue-600 font-semibold hover:text-blue-800 transition-colors"
                >
                  Xem chi tiết
                </a>
                <button
                  @click="deletePost(post.id)"
                  class="text-red-600 font-semibold hover:text-red-800 transition-colors flex items-center"
                >
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                  </svg>
                  Xóa
                </button>
              </div>
            </div>
            <p v-if="!posts.length" class="text-gray-500 col-span-full text-center text-lg">Chưa có bài viết nào!</p>
          </div>

          <!-- Tab Đăng bài -->
          <div v-if="activeTab === 'create-post'" class="space-y-6">
            <div>
              <label class="block text-gray-700 font-semibold">Tiêu đề</label>
              <input
                v-model="postForm.title"
                type="text"
                class="w-full px-4 py-3 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
              />
            </div>
            <div>
              <label class="block text-gray-700 font-semibold">Nội dung</label>
              <div class="flex flex-wrap gap-2 mb-4">
                <button
                  @click="postForm.content += '<h1>' + (postForm.content ? postForm.content : 'Tiêu đề lớn') + '</h1>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  H1
                </button>
                <button
                  @click="postForm.content += '<h2>' + (postForm.content ? postForm.content : 'Tiêu đề phụ') + '</h2>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  H2
                </button>
                <button
                  @click="postForm.content += '<p>' + (postForm.content ? postForm.content : 'Đoạn văn') + '</p>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  P
                </button>
                <button
                  @click="postForm.content += '<b>' + (postForm.content ? postForm.content : 'Chữ đậm') + '</b>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  Đậm
                </button>
                <button
                  @click="postForm.content += '<i>' + (postForm.content ? postForm.content : 'Chữ nghiêng') + '</i>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  Nghiêng
                </button>
                <button
                  @click="postForm.content += '<u>' + (postForm.content ? postForm.content : 'Chữ gạch chân') + '</u>'"
                  class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                >
                  Gạch chân
                </button>
              </div>
              <textarea
                v-model="postForm.content"
                class="w-full px-4 py-3 border border-teal-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                rows="6"
                placeholder="Nhập nội dung, có thể dùng HTML như <h1>, <p>, <b>, v.v."
              ></textarea>
              <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-gray-700 font-semibold mb-2">Xem trước:</h3>
                <div v-html="postForm.content" class="prose max-w-none"></div>
              </div>
            </div>
            <div>
              <label class="block text-gray-700 font-semibold">Địa điểm</label>
              <select
                v-model="postForm.location_id"
                class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">-- Chọn địa điểm --</option>
                <option
                  v-for="location in locations"
                  :key="location.id"
                  :value="location.id"
                >
                  {{ location.name }}
                </option>
              </select>

            </div>
            <div>
              <label class="block text-gray-700 font-semibold">Hình ảnh</label>
              <input
                type="file"
                accept="image/*"
                @change="handlePostImageChange"
                class="w-full px-4 py-3 border border-pink-300 rounded-lg"
              />
            </div>
            <button
              @click="createPost"
              class="w-full bg-gradient-to-r from-yellow-400 to-pink-500 text-white px-8 py-4 rounded-full hover:from-pink-500 hover:to-yellow-400 transition-all duration-300 transform hover:scale-105 animate-pulse"
            >
              Đăng bài
            </button>
          </div>

          <!-- Tab Đổi mật khẩu -->
          <div v-if="activeTab === 'password'" class="space-y-6">
            <div class="flex items-center space-x-4">
              <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.14 2 5 5.14 5 9v3H3v8h18v-8h-2V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v3H7V9c0-2.76 2.24-5 5-5zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
              </svg>
              <div class="flex-1">
                <label class="block text-gray-700 font-semibold">Mật khẩu hiện tại</label>
                <input
                  v-model="passwordForm.current_password"
                  type="password"
                  class="w-full px-4 py-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                />
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.14 2 5 5.14 5 9v3H3v8h18v-8h-2V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v3H7V9c0-2.76 2.24-5 5-5zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
              </svg>
              <div class="flex-1">
                <label class="block text-gray-700 font-semibold">Mật khẩu mới</label>
                <input
                  v-model="passwordForm.new_password"
                  type="password"
                  class="w-full px-4 py-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                />
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.14 2 5 5.14 5 9v3H3v8h18v-8h-2V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v3H7V9c0-2.76 2.24-5 5-5zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
              </svg>
              <div class="flex-1">
                <label class="block text-gray-700 font-semibold">Xác nhận mật khẩu mới</label>
                <input
                  v-model="passwordForm.new_password_confirmation"
                  type="password"
                  class="w-full px-4 py-3 border border-pink-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                />
              </div>
            </div>
            <button
              @click="changePassword"
              class="w-full bg-gradient-to-r from-pink-500 to-yellow-400 text-white px-8 py-4 rounded-full hover:from-yellow-400 hover:to-pink-500 transition-all duration-300 transform hover:scale-105"
            >
              Đổi mật khẩu
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
body {
  font-family: 'Poppins', sans-serif;
}
.animate-fade-in {
  animation: fadeIn 0.5s ease-in;
}
.animate-slide-in {
  animation: slideIn 0.5s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideIn {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.prose h1 {
  font-size: 2.25rem;
  font-weight: 700;
  line-height: 1.2;
  margin: 0.5rem 0;
}
.prose h2 {
  font-size: 1.875rem;
  font-weight: 600;
  line-height: 1.25;
  margin: 0.5rem 0;
}
.prose p {
  margin: 0.5rem 0;
}
.prose b {
  font-weight: 700;
}
.prose i {
  font-style: italic;
}
.prose u {
  text-decoration: underline;
}
</style>