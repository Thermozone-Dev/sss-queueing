<template>
    <div class="items-center justify-center">
        <div class="text-center mb-8 p-4 flex justify-center items-center rounded-xl bg-gray-200 shadow inset-shadow-lg inset-shadow-black">
            <font-awesome-icon :icon="['fas', 'circle-check']" class="w-12 h-12" :style="{color: theme.primary}"/>
            <span class="pl-4 uppercase text-xl">queue number generated!</span>
        </div>
        <div class="text-center shadow rounded-2xl px-4 py-6" :style="{backgroundColor: theme.primary}">
            <p class="font-bold text-2xl">Your Queue Number:</p>
            <span class="text-white font-black" style="font-size: 6rem">{{queue_number}}</span>
            <p class="font-bold text-2xl captitalize">{{transaction_name}}</p>
        </div>
    </div>
    <div class="text-center my-5">
        <p class="text-xl text-black font-semibold">Please wait for your name or number to be called</p>
        <p class="text-gray-600 text-center">For assistance, please approach our staff</p>
    </div>

    <div class="flex justify-center my-4">
        <button class="flex px-4 py-2 items-center text-white font-bold rounded-lg text-md" :style="{backgroundColor: theme.primary}">
            <router-link :to="{ name: 'home'}" >
                Get Another Number <font-awesome-icon :icon="['fas', 'chevron-right']" class="ml-2 w-4 h-4 m-0 p-0" />
            </router-link>
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                theme: window.appTheme || {},
            };
        },
        props: {
            transaction_name: { type: String, default: null },
            queue_number: { type: String, default: null }
        },
    };
</script>
