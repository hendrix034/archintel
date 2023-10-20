<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-6">Login</h2>
        <form @submit.prevent="login">
          <div class="mb-4">
            <label for="email" class="block text-gray-600 text-sm font-medium mb-2">Email</label>
            <input
              v-model="email"
              type="email"
              id="email"
              name="email"
              class="w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label for="password" class="block text-gray-600 text-sm font-medium mb-2">Password</label>
            <input
              v-model="password"
              type="password"
              id="password"
              name="password"
              class="w-full p-2 border border-gray-300 rounded"
              required
            />
          </div>
          <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Log In
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
     async login() {
      try {
          const response = await this.$axios.post('login.php', {email: this.email, password: this.password});
          if(response.data.success){
            localStorage.setItem('user',JSON.stringify(response.data.data))
            this.$router.push('/dashboard');
          } else {
            alert('invalid')
          }   
        } catch (error) {

          console.error('Error:', error);

        }
      },
    },
  };
  </script>
  