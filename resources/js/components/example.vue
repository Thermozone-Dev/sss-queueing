<template>
    <!-- <KioskHeader /> -->

    <main class="min-h-screen font-[Inter] p-6 space-y-5 mt-9 ">
            <KioskInfoHeader
                    :header="header"
                    :description="description"
                />
        <!-- =========================
             MAIN GRID
        ========================== -->
        <section class="mt-8 grid grid-cols-5 gap-7">

            <!-- =========================
                 LEFT : APPOINTMENT
            ========================== -->
            <div
                class="col-span-3 flex h-[390px] w-full flex-col justify-between rounded-[25px] bg-gradient-to-r from-[#3873D9] to-[#142E73] p-8 text-white"
            >

                <!-- TOP -->
                <div class="flex items-start justify-between">

                    <!-- User Icon -->
                    <div class="rounded-[8.5px] bg-white p-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-[#142E73]"
                        >
                            <path d="M2 21a8 8 0 0 1 13.292-6" />
                            <circle cx="10" cy="8" r="5" />
                            <path d="m16 19 2 2 4-4" />
                        </svg>
                    </div>

                    <!-- Priority -->
                    <div
                        class="flex h-6 items-center rounded-[18px] bg-white px-4 text-xs font-extrabold text-[#142E73]"
                    >
                        <h3 class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-[#142E73]"
                            ></span>

                            PRIORITY
                        </h3>
                    </div>

                </div>


                <!-- MIDDLE -->
                <div class="space-y-3">

                    <div class="text-5xl font-bold leading-10">
                        <h2>I HAVE AN</h2>
                        <h2>APPOINTMENT</h2>
                    </div>

                    <p
                        class="max-w-[420px] text-lg font-medium leading-5.5 text-[#CAD5E2]"
                    >
                        Already booked? Tap here to check in and get your
                        priority queue number instantly.
                    </p>

                </div>


                <!-- BOTTOM ACTION -->
                <router-link
                    :to="{ name: 'get-appointment' }"
                    class="flex w-fit items-center gap-3 font-bold transition-opacity hover:opacity-90"
                >

                    <span
                        class="flex h-[50px] w-[50px] items-center justify-center rounded-full bg-white"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-[#142E73]"
                        >
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </span>

                    <span>Tap to check in</span>

                </router-link>

            </div>


            <!-- =========================
                 RIGHT : SERVICES
            ========================== -->
            <div
                class="custom-scrollbar col-span-2 h-[390px] space-y-6 overflow-y-auto pr-2"
            >

                <!-- =========================
                     SEARCH
                ========================== -->
                <div
                    class="sticky top-0 z-10 flex items-center justify-between rounded-[12px] border border-[#D6E4FF] bg-white p-2 shadow-[0_8px_24px_0_rgb(30_80_161_/_7.8%)]"
                >

                    <!-- Search -->
                    <div class="flex w-full items-center gap-5 pr-5">

                        <div class="rounded-full bg-[#EEF4FF] p-1">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#142E73"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                        </div>

                        <input
                            v-model="search"
                            type="search"
                            placeholder="Search service..."
                            class="w-full border-0 bg-transparent text-sm text-[#505050] outline-none focus:ring-0"
                        />

                    </div>

                    <!-- Search Button -->
                    <button
                        type="button"
                        class="cursor-pointer rounded-[10px] bg-[#3350E8] p-1.5 transition-opacity hover:opacity-90"
                        @click="search = search.trim()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="white"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-4 w-4"
                        >
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>

                </div>


                <!-- =========================
                     SERVICE LIST
                ========================== -->
                <div class="space-y-8">

                    <!-- NO RESULTS -->
                    <div
                        v-if="filteredStations.length === 0"
                        class="py-10 text-center text-sm text-paragraph w-full "
                    >
                       <p>No services found.</p>
                    </div>


                    <!-- SERVICES -->
                    <div
                        v-for="station in filteredStations"
                        :key="station.id"
                        class="flex items-center gap-8"
                    >

                        <!-- ICON -->
                        <div class="shrink-0">

                            <component
                                :is="getIconComponent(station.icon)"
                                class="h-11 w-11"
                                :class="station.status == 1
                                    ? 'text-[#1E50A1]'
                                    : 'text-[#1E50A1] opacity-40'"
                            />

                        </div>


                        <!-- CONTENT -->
                        <div class="space-y-5">

                            <div>

                                <h2
                                    class="text-lg font-bold"
                                    :class="station.status == 1
                                        ? 'text-header'
                                        : 'text-header opacity-50'"
                                >
                                    {{ station.name }}
                                </h2>

                                <p
                                    class="mt-1 text-sm leading-5"
                                    :class="station.status == 1
                                        ? 'text-paragraph'
                                        : 'text-paragraph opacity-50'"
                                >
                                    {{ station.description }}
                                </p>

                            </div>


                            <!-- ONLINE -->
                            <router-link
                                v-if="station.status == 1"
                                :to="{
                                    name: 'view-station',
                                    params: { id: station.id }
                                }"
                                class="flex items-center gap-5 text-sm font-bold text-[#1E50A1] transition-opacity hover:opacity-70"
                            >

                                GET TICKET

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#1E50A1"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="m9 18 6-6-6-6" />
                                </svg>

                            </router-link>


                            <!-- OFFLINE -->
                            <div
                                v-else
                                class="flex items-center gap-3"
                            >

                                <span
                                    class="rounded-full bg-[#F1F1F1] px-3 py-1 text-xs font-bold text-[#777777]"
                                >
                                    OFFLINE
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =========================
             FOOTER INFORMATION
        ========================== -->
        <div class=" mt-8 text-center">

            <p class="mb-1 text-xl text-header font-bold uppercase pt-8 ">
                TOUCH ANY BUTTON ABOVE TO BEGIN
            </p>
<!-- 
            <p class="text-gray-600">
                For assistance, please approach our staff
            </p> -->

            <span class="my-4 flex">

                <span
                    class="mx-auto flex items-center gap-6 rounded-lg text-paragraph font-semibold text-sm"
                >
                <span class="flex justify-center items-center bg-black text-white p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg"
                       width="24" height="24" viewBox="0 0 24 24" 
                       fill="none" stroke="currentColor"
                       stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round" class="lucide lucide-clock-4">
                     <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </span>
                  
                        Operating Hours: 8:00 AM - 5:00 PM • Monday to Friday 

                     <span class="border border-[#1E50A1] px-2 p-0.5 rounded-2xl text-sm text-[#1E50A1]">• OPEN NOW</span>
                </span>
            </span>

        </div>

    </main>
</template>


<script>
import axios from "axios";
import * as Icons from "@heroicons/vue/24/solid";
import { route } from "ziggy-js";

import KioskHeader from "../components/kioskHeader.vue";
import KioskInfoHeader from "../components/header-info.vue";

export default {
    components: {
        KioskHeader,
        KioskInfoHeader,
    },

    props: {
        header: {
            type: String,
            default: "Welcome.",
        },

        description: {
            type: String,
            default: "Please Select a service to begin",
        },
    },

    data() {
        return {
            theme: window.appTheme || {},

            stations: [],

            search: "",
        };
    },

    computed: {
        filteredStations() {
            const keyword = this.search.toLowerCase().trim();

            if (!keyword) {
                return this.stations;
            }

            return this.stations.filter((station) => {
                return (
                    station.name?.toLowerCase().includes(keyword) ||
                    station.description?.toLowerCase().includes(keyword)
                );
            });
        },
    },

    methods: {
        getIconComponent(iconName) {
            if (!iconName) {
                return Icons.QuestionMarkCircleIcon;
            }

            const nameWithoutPrefix = iconName.replace(
                /^heroicon-[csom]-/,
                ""
            );

            const pascalCase =
                nameWithoutPrefix
                    .split("-")
                    .map(
                        (word) =>
                            word.charAt(0).toUpperCase() + word.slice(1)
                    )
                    .join("") + "Icon";

            return (
                Icons[pascalCase] ||
                Icons.QuestionMarkCircleIcon
            );
        },
    },

    mounted() {
        axios
            .get(route("get-stations"))
            .then((response) => {
                this.stations = response.data.data;
            })
            .catch((error) => {
                console.error(
                    "There was an error fetching the stations:",
                    error.message
                );
            });
    },
};
</script>
