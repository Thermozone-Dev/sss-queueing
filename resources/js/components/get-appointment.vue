<template>

    <h1 class="text-3xl font-extrabold text-black text-center">{{header}}</h1>
    <p class="text-gray-600 text-center">{{description}}</p>

    <div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">

        <div class="p3">
            <go-back/>

            <div class="text-left">
                <label class="queue-form-label" for="name">Appointment ID *</label>
            </div>

            <div class="grid grid-cols-[1fr_20%] items-center mt-2">
                <div>
                    <input
                        id="name"
                        type="text"
                        v-model="form.appointmentID"
                        class="queue-form-input"
                        placeholder="APP-2026-01-0001"
                        maxlength="20"
                        required
                    />
                    <span v-if="errors.appointmentID" class="text-red-500 text-xs mt-1">{{ errors.appointmentID }}</span>
                </div>
                <div class="flex justify-center">
                    <button
                        @click="validate"
                        class="px-4 py-2 text-white font-bold rounded-lg"
                        :style="{backgroundColor: theme.primary, color: theme.secondary}">
                        Search
                    </button>
                </div>

            </div>

        </div>
        <div class="mt-6">

            <div v-if="loading" class="text-center font-semibold">
                Searching appointment...
            </div>

            <div v-if="message" class="text-center text-red-500 font-semibold">
                {{ message }}
            </div>

            <div v-if="appointment" class="bg-gray-50 p-5 rounded-lg border mt-4">

                <h2 class="text-xl font-bold mb-4 text-center">Appointment Details</h2>

                <p><strong>Code:</strong> {{ appointment.code }}</p>
                <p><strong>Name:</strong> {{ appointment.name }}</p>
                <p><strong>Date:</strong> {{ appointment.date }}</p>
                <p><strong>Time:</strong> {{ formatTime(appointment.time) }}</p>

                <p class="mt-2">
                    <strong>Status:</strong>
                    <span
                        class="px-2 py-1 text-black text-sm rounded ml-1 font-bold"
                        :class="{
                                'bg-yellow-500': appointment.status === 'waiting',
                                'bg-green-600': appointment.status === 'completed' || appointment.status === 'done',
                                'bg-red-600': appointment.status === 'cancelled',
                                'bg-gray-500': !['waiting','completed','done','cancelled'].includes(appointment.status)
                            }"
                     >

                        {{ appointment.status.toUpperCase() }}
                    </span>
                </p>
            </div>

            <div v-if="appointment" class="bg-gray-50 p-5 rounded-lg border mt-4">

                <h2 class="text-xl font-bold mb-4 text-center">Select Transaction</h2>

                <div class="mt-6 grid grid-cols-[30%_1fr] gap-6">

                    <!-- Left Column: Stations -->
                    <div>
                        <h3 class="font-bold mb-2">STATION</h3>
                        <ul class="space-y-2">
                            <li
                                v-for="station in stations"
                                :key="station.id"
                                class="cursor-pointer p-2 rounded hover:bg-gray-100"
                                :class="{'bg-gray-200': selectedStation === station.id}"
                                :style="selectedStation === station.id
                                    ? { backgroundColor: theme.primary, color: theme.secondary }
                                    : {}"

                                @click="selectStation(station.id)"
                            >
                                {{ station.name }}
                            </li>
                        </ul>
                    </div>

                    <!-- Right Column: Transactions -->
                    <div>
                        <h3 class="font-bold mb-2">TRANSACTION</h3>

                        <div v-if="selectedStation && transactions.length">
                            <ul class="space-y-2">

                                <li
                                    v-for="transaction in transactions"
                                    :key="transaction.id"
                                    class="border rounded shadow-sm overflow-hidden"
                                >
                                    <!-- Accordion Header -->
                                    <div
                                        class="flex justify-between items-center p-2 bg-gray-100 hover:bg-gray-200 cursor-pointer"
                                        :style="selectedTransactionId === transaction.id
                                            ? { backgroundColor: theme.primary, color: theme.secondary }
                                            : {}"
                                        @click="toggleTransaction(transaction.id)"
                                    >
                                        <span>{{ transaction.name }}</span>

                                        <svg
                                            :class="{'rotate-180': isTransactionOpen(transaction.id)}"
                                            class="w-4 h-4 transition-transform duration-200"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    <!-- Accordion Body -->
                                    <div
                                        v-show="isTransactionOpen(transaction.id)"
                                        class="p-3 bg-white border-t"
                                    >
                                        <!-- Required Documents (HTML safe render) -->
                                        <div v-if="transaction.required_documents" class="mb-3">
                                            <h4 class="font-semibold text-sm mb-1">Required Documents:</h4>
                                            <div
                                                class="text-sm text-gray-700"
                                                v-html="transaction.required_documents"
                                            ></div>
                                        </div>

                                        <!-- Steps -->
                                        <div v-if="transaction.transaction_steps.length">
                                            <h4 class="font-semibold text-sm mb-1">Steps:</h4>
                                            <ol class="list-decimal list-inside text-gray-700 text-sm space-y-1">
                                                <li
                                                    v-for="step in transaction.transaction_steps"
                                                    :key="step.id"
                                                >
                                                    <strong>{{ step.title }}</strong>
                                                </li>
                                            </ol>
                                        </div>

                                        <div v-else class="text-gray-400 text-sm">
                                            No steps available.
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>

                        <div v-else class="text-gray-500">
                            Select a station to see available transactions.
                        </div>
                    </div>
                </div>

                <!-- Proceed Button -->
                <div class="flex mt-6 justify-end">
                    <button class="px-4 py-3 text-white  text-sm font-bold rounded-lg  hover:bg-gray-700"
                        :class="selectedTransactionId
                            ? 'bg-blue-600 hover:bg-blue-700'
                            : 'bg-gray-400 cursor-not-allowed'"
                        :disabled="!selectedTransactionId"
                        @click="openConfirmModal()">
                        Get Queue Number
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div
        v-if="showConfirmModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">

            <!-- Header -->
            <h2 class="text-xl font-bold text-red-600 mb-3">
                Confirm Queue Action
            </h2>

            <!-- Body -->
            <div class="text-sm text-gray-700 space-y-3">
                <p>
                    You are about to proceed with this appointment.
                </p>

                <p class="font-semibold text-yellow-600">
                    Once confirmed:
                </p>

                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>This appointment will be marked as <strong>Processing</strong>.</li>
                    <li>You cannot queue again using this appointment.</li>
                    <li>To re-queue, you must contact the administrator.</li>
                </ul>

                <p class="text-gray-500 text-xs mt-2">
                    Please confirm if you wish to continue.
                </p>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button
                    @click="closeConfirmModal"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700"
                >
                    Cancel
                </button>

                <button
                    @click="confirmQueue"
                    class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white font-semibold"
                >
                    Confirm & Proceed
                </button>
            </div>
        </div>
    </div>


</template>
<script>
    import { route } from 'ziggy-js';
    import axios from 'axios';

    export default {
        props: {
            header: { type: String, default: 'Verify Your Appointment' },
            description: { type: String, default: 'Search your appointment' },
            type: [String],
            id: 'id' || null,
        },

        data() {
            return {
                theme: window.appTheme || {},
                form: {
                    appointmentID: "APP-2026-01-0001",
                },
                selectedStation: null,
                openTransactions: [], // tracks open accordions
                appointment: null,
                errors: {},
                message: null,
                loading: false,
                stations: [],
                transactions: [],

                showConfirmModal: false,
                selectedTransactionId: null,


            };
        },

        computed: {
            statusColor() {
                if (!this.appointment) return '';
                console.log(this.appointment.status)
                switch (this.appointment.status) {
                    case 'waiting':
                        return 'bg-yellow-500';
                    case 'completed':
                    case 'done':
                        return 'bg-green-600';
                    case 'cancelled':
                        return 'text-red-600';
                    default:
                        return 'text-gray-500';
                }
            }
        },

        methods: {

            validate() {
                this.errors = {};
                this.message = null;
                this.appointment = null;

                if (!this.form.appointmentID) {
                    this.errors.appointmentID = "Appointment ID is required";
                }

                if (Object.keys(this.errors).length === 0) {
                    this.search(this.form.appointmentID);
                    return true;
                }

                return false;
            },

            search(appointmentID) {

                this.loading = true;
                this.message = null;
                this.appointment = null;

                axios.get(route('appointment.verify', appointmentID))
                    .then(response => {
                        // Correct handling of API structure
                        if (response.data.success && response.data.data) {
                            this.appointment = response.data.data;
                        } else {
                            this.message = "Appointment not found.";
                        }

                    })
                    .catch(error => {

                        if (error.response && error.response.status === 404) {
                            this.message = "Appointment not found.";
                        } else {
                            this.message = "Something went wrong. Please try again.";
                        }

                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            formatTime(time) {
                if (!time) return '';

                const [hour, minute] = time.split(':');
                const h = parseInt(hour);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const formattedHour = h % 12 || 12;

                return `${formattedHour}:${minute} ${ampm}`;
            },

            selectStation(stationId) {
                this.selectedStation = stationId;
                this.fetchTransactions(stationId);
                this.selectedTransactionId = null;
                this.openTransactions = []; // reset when changing station
            },
            toggleTransaction(transactionId) {
                this.selectedTransactionId = transactionId;
                const index = this.openTransactions.indexOf(transactionId);
                if (index >= 0) {
                    this.openTransactions.splice(index, 1); // close if already open
                } else {
                    this.openTransactions.push(transactionId); // open if closed
                }
            },
            isTransactionOpen(transactionId) {
                return this.openTransactions.includes(transactionId);
            },

            getStations() {

                this.loading = true;
                this.message = null;
                this.stations = [];

                axios.get(route('get-stations'))
                    .then(response => {
                        this.stations = response.data.data;
                    })
                    .finally(() => {
                        this.loading = false;
                });
            },


            fetchTransactions(station_id) {
                console.log(station_id)
                this.loading = true;
                this.message = null;
                this.transactions = [];

                axios.get(route('get-stations-transaction',station_id))
                    .then(response => {
                        this.transactions = response.data.data;
                    })
                    .finally(() => {
                        this.loading = false;
                });
            },

            openConfirmModal() {
                this.showConfirmModal = true;
            },

            closeConfirmModal() {
                this.showConfirmModal = false;
            },

            confirmQueue() {
                // Here you will call your queue API
                // Example:
                // this.createQueue(this.selectedTransactionId);

                console.log("Confirmed transaction:", this.selectedTransactionId);

                this.closeConfirmModal();
            }
        },

        mounted() {
            this.getStations();
            if (this.theme?.primary) {
                document.documentElement.style.setProperty("--theme-primary", this.theme.primary);
            }
        }
    };
</script>



<style scoped>
    .text-red-500 {
        color: #FF0000;
    }
    .queue-form-input{
        border-radius: 10px;
        font-weight:bold;
        background-color: #F3F4F6;
        width: 100%;
    }

    .queue-form-input:focus{
        font-size:1.3rem;
        border-color:  var(--theme-primary);
    }

    .queue-placeholder{
        font-weight:bold;;
    }

    .queue-form-label{
        /* color: var(--theme-primary); */
        font-weight: bold;
        font-size: 1.3rem;
    }

</style>