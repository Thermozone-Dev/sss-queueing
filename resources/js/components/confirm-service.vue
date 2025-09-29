<template>


<div class="items-center justify-center">
    <go-back/>
    <div>
        <h1 class="font-black text-black capitalize text-center" style="font-size: 3rem">Confirm Service</h1>
    </div>
    <div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">
        <div class="text-center">
            <h2 class="uppercase font-extrabold" style="font-size: 2rem; color:#00411F">{{selected_transaction}}</h2>
            <h4 class="uppercase font-bold text-xl">{{selected_station}}</h4>
        </div>
        <span class="flex mt-5">
            <span class="mx-auto bg-gray-300 items-center rounded-lg px-5 py-3">
                <p class="flex"><span class="font-extrabold mr-2">Name: </span>{{form.name}}</p>
                <p class="flex" v-if="form.mobile"><span class="font-extrabold mr-2">Number: </span>{{form.mobile}}</p>
            </span>
        </span>
        <div class="flex justify-center mt-4">
            <button @click="submit()" class="px-6 py-4 text-white font-bold rounded-lg text-md" :style="{backgroundColor: theme.primary}">
                Get Queue Number
            </button>
        </div>
    </div>
</div>
<span class="my-2">
    <p class="text-gray-600 text-center py-5 capitalize">by proceeding, you confirm that you have all required documents ready</p>
</span>

</template>
<script>
    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        props: {
            id: {
                type: [String, Number],
                default: 'id' || null
            },
        },
        data() {
            return {
                theme: window.appTheme || {},
                form: {
                    name: '',
                    mobile: null,
                    priority_type: null
                },
                errors: {},
                selected_transaction: null,
                selected_station: null,
            };
        },
        methods: {
            submit() {
                let payload = {
                    ...this.form,
                    transaction_id: this.id
                };
                axios.post(route('queue.post'), payload)
                .then(
                    response => {
                        this.$router.push({ name: 'complete-queue', params: { transaction_name: response.data.data.transaction_name, queue_number: response.data.data.queue_number } });
                    }
                )
                .catch( error => {
                    if (error.response && error.response.status === 422) {
                        this.$router.push({
                            name: 'get-queue',
                            query: {
                                form: JSON.stringify(this.form),
                                errors: JSON.stringify(error.response.data.errors)
                            },
                            params: { id: this.id },

                        });
                    } else {
                        console.error("Unexpected error:", error);
                    }
                });

            }
        },

        mounted() {
            axios.get(route('get-transaction', this.id))
                .then(response => {
                    this.selected_transaction = response.data.data.name;
                    this.selected_station = response.data.data.station;

                    console.log(response.data.name,response.data.station)
                })
                .catch(error => {
                    console.error("There was an error fetching the priority:", error.message);
                });

             if (this.$route.query.form) {
                try {
                    const formData = JSON.parse(this.$route.query.form);
                    this.form = {
                        name: formData.name || '',
                        mobile: formData.mobile || null,
                        priority_type: formData.priority_type || null
                    };
                } catch (e) {
                    console.error("Invalid form data", e);
                }
            }


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
