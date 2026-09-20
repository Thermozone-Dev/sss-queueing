<template>
    <button  @click="goBack" class="rounded-full border border-[#D6E4FF] p-2 shadow-[0_8px_24px_0_rgba(30,80,161,0.078)]"
    >
        <font-awesome-icon :icon="['fas', 'chevron-left']" class="w-5 h-5 cursor-pointer text-[#1E50A1]" />
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
