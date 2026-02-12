<template>
    <div class="rounded-2xl shadow-lg bg-white overflow-hidden">

        <!-- Header -->
        <div
            class="flex items-center justify-between px-6 py-2 text-white"
            :class="{
            'bg-gradient-to-r from-purple-600 to-blue-500': laneName.toLowerCase() === 'senior',
            'bg-gradient-to-r from-emerald-500 to-teal-400': laneName.toLowerCase() === 'regular',
            'bg-gradient-to-r from-rose-500 to-orange-400': laneName.toLowerCase() === 'appointment',
            'bg-gray-500': !['senior','regular','priority'].includes(laneName.toLowerCase())
            }"
        >
            <h2 class="text-xl font-bold tracking-wider">
            {{ laneName.toUpperCase() }} LANE
            </h2>

            <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">
            {{ queues.length }} in queue
            </span>
        </div>

        <!-- Body -->
        <div class="py-2 px-6 space-y-2">
            <!-- Empty state -->
            <div v-if="queues.length === 0" class="text-center text-gray-400 italic">
            No customers in this lane
            </div>

            <!-- Queue items -->
            <div
                v-for="queue in queues"
                :key="queue.queueNumber"
                class="border-b last:border-none grid grid-cols-3 items-center pb-1"
            >
                <span class="col-span-">
                    <p class="text-3xl font-black scale-x-125 origin-left">
                        {{ queue.queueNumber }}
                    </p>
                </span>
                <span class="col-span-1">
                    <p class="text-xl text-gray-900 font-extrabold">
                        {{ queue.name }}
                    </p>
                </span>
                <span class="col-span-1 text-right">
                    <p class="text-md text-gray-900 ">
                       <b>Station:</b> {{ queue.station }}
                    </p>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "LaneSection",

    props: {
      laneName: {
        type: String,
        required: true
      },
      queues: {
        type: Array,
        required: true
      }
    }
  }
  </script>
