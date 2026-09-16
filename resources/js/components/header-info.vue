<template >
    <header class="leading-[41px]  ">
        <h1 class="text-[68px] font-black text-header">{{header}}</h1>
        <p  class="px-4 text-[32px] font-bold text-paragraph">{{description}}</p>
    </header>
    
</template>

<script>
    export default {
        props: ['header', 'description']
    };
</script>
