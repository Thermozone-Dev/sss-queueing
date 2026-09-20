<template>
    <div class="relative flex items-start justify-center">
        <!-- Back Button -->
        <div class="absolute left-0 top-0">
            <go-back />
        </div>

        <!-- Header Text -->
        <div class="w-full space-y-2 px-10 text-center">
            <h1 class="text-2xl font-extrabold text-[#505050] md:text-4xl">
                {{ header }}
            </h1>

            <p class="mx-auto max-w-2xl text-sm text-[#606060] md:text-base">
                {{ description }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        header: {
            type: String,
            default: 'Verify Your Appointment',
        },

        description: {
            type: String,
            default: 'Search your appointment',
        },
    },
};
</script>