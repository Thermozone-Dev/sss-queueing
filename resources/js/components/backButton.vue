<template>
    <button  @click="goBack" class="flex pill-btn items-center px-4 my-4 py-1 rounded-lg mb-5">
        <font-awesome-icon :icon="['fas', 'chevron-left']" class="w-4 h-4 mr-2" />Back
    </button>
</template>

<script>

export default {
   name: "BackButton",
    methods: {
        goBack() {
        this.$router.back();
        }
    }
};
</script>
<style scoped>
    .pill-btn {
        border: 2px solid #84CC16;    /* lime-500 border */
        background-color: transparent; /* transparent background */
        color: #84CC16;               /* text color same as border */
    }
</style>
