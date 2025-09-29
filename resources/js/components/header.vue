<template>
    <h1 class="text-3xl font-extrabold text-black">{{header}}</h1>
    <p class="text-gray-600">{{description}}</p>
</template>

<script>
    export default {
        props: ['header', 'description']
    };
</script>
