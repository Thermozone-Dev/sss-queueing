<template>
    <button  @click="goBack" class="flex items-center px-4 my-4 py-1 rounded-lg mb-5"
        :style="{
            border: `2px solid ${theme.primary}`,
            backgroundColor: 'transparent',
            color: theme.primary
        }"
    >
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
    },

    data() {
        return {
            theme: window.appTheme || {},
        };
    },
};
</script>
