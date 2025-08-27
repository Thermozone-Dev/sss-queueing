<template>
    <div>
        <h1 class="text-3xl font-black text-black capitalize">enter your details</h1>
        <p class="text-gray-600 capitalize">ilagay ang iyong impormasyon</p>
    </div>
    <div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">
        <form @submit.prevent="nextStep" class="p-3">
            <!-- {{ $this->form }} -->
            <!-- Name -->
            <div class="grid grid-cols-1 mb-4">
                <label class="queue-form-label" for="name">Name *</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="queue-form-input"
                    placeholder="Input Name"
                    maxlength="8"
                    required
                />
                <span v-if="errors.name" class="text-red-500 text-xs">{{ errors.name }}</span>
            </div>

            <!-- Mobile -->
            <div class="grid grid-cols-1 mb-4">
                <label class="queue-form-label" for="mobile">Number (optional)</label>
                <input
                    id="mobile"
                    type="tel"
                    v-model="form.mobile"
                    class="queue-form-input"
                    placeholder="09XX XXX XXXX"
                    minlength="11"
                    maxlength="12"
                />
                <small class="text-gray-500">Mobile number must start with (09)</small>
                <span v-if="errors.mobile" class="text-red-500 text-xs">{{ errors.mobile }}</span>
            </div>

            <!-- Priority Lane -->
            <div v-if="priorityType && priorityType.length" class="grid grid-cols-1 mb-4">
                <label class="queue-form-label">For Priority Lane (Check One)</label>
                <div class="mt-2 space-y-2">
                    <label v-for="(priority) in priorityType" :key="priority.id" class="flex items-center">
                        <input
                            type="radio"
                            class="queue-form-input"
                            v-model="form.priority_type"
                            value={{priority.id}}
                        />
                        <span class="ml-2">{{priority.name}}</span>
                    </label>
                    <p class="text-xs" style="color: #FF0000;">
                        <em>
                            Select this option only if you qualify for the priority lane. Ineligible selections will
                            result in returning to the regular queue and being placed last.
                        </em>
                    </p>
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <button type="submit" class="px-4 py-1 text-white font-bold rounded-lg" style="background-color: #00411F">
                    Submit
                </button>
            </div>
        </form>
    </div>
    <span class="my-4">
        <p class="text-gray-600 text-center py-5">For assistance, please approach our staff</p>
    </span>
</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        props: {
            type: [String, Number],
            id: 'id' || null,
        },
        data() {
            return {
                form: {
                    name: "",
                    mobile: "",
                    priority_type: null,
                },
                errors: {},
                priorityType: [],
            };
        },
        methods: {
            validate() {
                this.errors = {};

                if (!this.form.name) {
                    this.errors.name = "Name is required";
                } else if (this.form.name.length > 8) {
                    this.errors.name = "Name must be at most 8 characters";
                }

                if (this.form.mobile) {
                    if (!/^09\d{9}$/.test(this.form.mobile)) {
                    this.errors.mobile = "Mobile number must start with 09 and be 11 digits";
                    }
                }
                return Object.keys(this.errors).length === 0;
            },
            nextStep() {
                if (this.validate()) {
                    // this.$router.push({ name: 'step2', params: { formData: this.form } });
                }
            }
        },

        mounted() {
            axios.get(route('get-priority'))
                .then(response => {
                    this.priorityType = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the priority:", error.message);
                });
        },
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
    }

    .queue-form-input:focus{
        font-size:1.3rem;
        border-color: #84CC16;
    }

    .queue-placeholder{
        font-weight:bold;;
    }

    .queue-form-label{
        color: #007236;
        font-weight: bold;
        font-size: 1.3rem;
    }

</style>
