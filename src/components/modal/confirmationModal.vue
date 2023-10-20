<template>
    <div
      class="fixed top-0 left-0 flex items-center justify-center w-full h-full bg-gray-800 bg-opacity-50"
      v-if="visible"
    >
      <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-6">Confirmation</h2>
        <p class="mb-4">{{ message }}</p>
        <div class="flex justify-end">
          <button @click="cancel" class="mr-4 text-gray-600 hover:text-red-500">Cancel</button>
          <button @click="confirm" class="text-blue-500 hover:text-blue-700">Confirm</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      visible: Boolean,
      message: String,
    },
    methods: {
      confirm() {
        this.$emit('confirmed');
      },
      cancel() {
        this.$emit('canceled');
      },
    },
  };
  </script>
  