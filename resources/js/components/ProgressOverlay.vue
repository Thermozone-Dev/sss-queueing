<template>
    <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="w-40 h-40 rounded-full border-8 border-lime-400 border-t-transparent relative flex items-center justify-center">
        <span class="absolute text-white font-bold text-xl">{{ progress }}%</span>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        loading: false,
        progress: 0,
        interval: null,
      };
    },
    methods: {
      start() {
        this.loading = true;
        this.progress = 0;
        this.interval = setInterval(() => {
          if (this.progress < 90) this.progress += 5;
        }, 200);
      },
      finish() {
        this.progress = 100;
        setTimeout(() => {
          this.loading = false;
          clearInterval(this.interval);
        }, 300);
      },
      fail() {
        this.loading = false;
        clearInterval(this.interval);
        this.progress = 0;
      }
    }
  };
  </script>
