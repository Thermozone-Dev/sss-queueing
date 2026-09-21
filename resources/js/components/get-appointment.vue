<template>
    <main class="min-h-screen space-y-6 p-4 md:p-8 xl:p-12">

        <!-- Header -->
        <AppointmentHeader
            :header="header"
            :description="description"
        />

        <!-- Search -->
        <AppointmentSearch
            v-model="form.appointmentID"
            :loading="loading"
            :error="errors.appointmentID"
            @search="validate"
        />

        <!-- Loading -->
        <div
            v-if="loading"
            class="flex flex-col items-center justify-center gap-3 rounded-xl border border-[#D6E4FF] bg-white py-10"
        >
            <div
                class="h-8 w-8 animate-spin rounded-full border-4 border-[#D6E4FF] border-t-[#3350E8]"
            />

            <p class="text-sm font-semibold text-[#606060]">
                Searching appointment...
            </p>
        </div>

        <!-- Message -->
        <div
            v-if="message"
            class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-center text-sm font-semibold text-red-500"
        >
            {{ message }}
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

            <!-- Details -->
            <AppointmentDetails
                :appointment="appointment"
            />

            <!-- Transactions -->
            <section
                class="space-y-5 rounded-2xl border border-[#D6E4FF] bg-[#D6E4FF]/10 px-4 py-5 md:px-6 xl:col-span-3"
            >
                <h2 class="text-center font-bold text-[#505050]">
                    Select Transaction
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <StationList
                        :stations="stations"
                        :appointment-found="Boolean(appointment)"
                        :selected-station="selectedStation"
                        @select="selectStation"
                    />

                    <TransactionList
                        :selected-station="selectedStation"
                        :transactions="transactions"
                        :selected-transaction-id="selectedTransactionId"
                        :open-transactions="openTransactions"
                        @toggle="toggleTransaction"
                    />

                </div>

                <!-- Queue Button -->
                <div
                    class="flex justify-end border-t border-[#D6E4FF] pt-4"
                >
                    <button
                        type="button"
                        @click="openConfirmModal"
                        :disabled="!selectedTransactionId"
                        class="w-full rounded-lg px-5 py-3 text-sm font-bold text-white transition 
                        disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-500 md:w-auto"
                        :class="
                            selectedTransactionId
                                ? 'bg-[#3350E8] hover:bg-[#2842C7]'
                                : ''
                        "
                    >
                        Get Queue Number
                    </button>
                </div>
            </section>
        </div>

        <!-- Modal -->
        <ConfirmQueueModal
            :show="showConfirmModal"
            @close="closeConfirmModal"
            @confirm="confirmQueue"
        />

    </main>
</template>

<script>
import { route } from 'ziggy-js';
import axios from 'axios';

import AppointmentHeader from './Appointment/Header.vue';
import AppointmentSearch from './Appointment/Search.vue';
import AppointmentDetails from './Appointment/Details.vue';
import StationList from './Appointment/StationList.vue';
import TransactionList from './Appointment/TransactionList.vue';
import ConfirmQueueModal from './Appointment/ConfirmQueueModal.vue';

export default {
    components: {
        AppointmentHeader,
        AppointmentSearch,
        AppointmentDetails,
        StationList,
        TransactionList,
        ConfirmQueueModal,
    },

    props: {
        header: {
            type: String,
            default: 'Verify Your Appointment',
        },

        description: {
            type: String,
            default: 'Enter your appointment ID to retrieve your queue details and service information.',
        },

        type: String,

        id: {
            type: [String, Number],
            default: null,
        },
    },

    data() {
        return {
            theme: window.appTheme || {},

            form: {
                appointmentID: '',
            },

            selectedStation: null,
            selectedTransactionId: null,

            openTransactions: [],

            appointment: null,

            errors: {},
            message: null,
            loading: false,

            stations: [],
            transactions: [],

            showConfirmModal: false,
        };
    },

    methods: {
        // ilagay dito ang API/business logic mo

        validate() {
            this.errors = {};
            this.message = null;
            this.appointment = null;

            if (!this.form.appointmentID) {
                this.errors.appointmentID =
                    'Appointment ID is required';

                return false;
            }

            this.search(this.form.appointmentID);

            return true;
        },

        search(appointmentID) {
            this.loading = true;

            axios
                .get(route('appointment.verify', appointmentID))
                .then((response) => {
                    if (
                        response.data.success &&
                        response.data.data
                    ) {
                        this.appointment =
                            response.data.data;
                    } else {
                        this.message =
                            'Appointment not found.';
                    }
                })
                .catch((error) => {
                    if (
                        error.response &&
                        error.response.status === 404
                    ) {
                        this.message =
                            error.response.data.message;
                    } else {
                        this.message =
                            'Something went wrong. Please try again.';
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        selectStation(stationId) {
            this.selectedStation = stationId;
            this.selectedTransactionId = null;
            this.openTransactions = [];

            this.fetchTransactions(stationId);
        },

        toggleTransaction(transactionId) {
            this.selectedTransactionId = transactionId;

            const index =
                this.openTransactions.indexOf(transactionId);

            if (index >= 0) {
                this.openTransactions.splice(index, 1);
            } else {
                this.openTransactions.push(transactionId);
            }
        },

        getStations() {
            axios
                .get(route('get-stations'))
                .then((response) => {
                    this.stations = response.data.data;
                });
        },

        fetchTransactions(stationId) {
            this.loading = true;

            this.transactions = [];

            axios
                .get(
                    route(
                        'get-stations-transaction',
                        stationId
                    )
                )
                .then((response) => {
                    this.transactions =
                        response.data.data;
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
            this.submitAppointmentQueue();
            this.closeConfirmModal();
        },

        submitAppointmentQueue() {
            const payload = {
                transaction_id:
                    this.selectedTransactionId,

                name: this.appointment.name,

                appointment_id:
                    this.appointment.code,
            };

            axios
                .post(route('queue.post'), payload)
                .then((response) => {
                    this.$router.push({
                        name: 'complete-queue',
                        params: {
                            transaction_name:
                                response.data.data
                                    .transaction_name,

                            queue_number:
                                response.data.data
                                    .queue_number,
                        },
                    });
                });
        },
    },

    mounted() {
        this.getStations();

        if (this.theme?.primary) {
            document.documentElement.style.setProperty(
                '--theme-primary',
                this.theme.primary
            );
        }
    },
};
</script>