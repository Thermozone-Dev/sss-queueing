<template>
<go-back/>
<div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">
    <div class="pt-5">
        <h1 class="text-3xl font-extrabold text-black capitalize">{{selected_transaction.name}}</h1>
        <p class="text-black-400 ">Please read the guidelines and ensure you have the required documents before proceeding.</p>
    </div>

    <div id="step-section" class="mt-3">
        <h4 class="text-black font-bold text-lg my-4">Steps</h4>
        <div class="bg-gray-200 h-48 max-h-48 overflow-auto rounded-xl p-4">
            <div v-if="selected_transaction.steps && selected_transaction.steps.length">
                <ol>
                    <li v-for="(step, index) in selected_transaction.steps" :key="step.id">
                        <span class="font-semibold mr-2">{{index + 1}}. {{step.title}}</span>
                        <span class=""> - {{step.description }}</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <div id="requirements-section" class="mt-3 mb-4">
        <h4 class="text-black font-bold text-lg my-4">Requirements</h4>
        <div class="bg-gray-200 h-48 max-h-48 overflow-auto rounded-xl p-4">
            {{selected_transaction.description}}
        </div>
    </div>
    <div class="w-full flex justify-end my-2">
        <button @click="proceedNextStep()" class="rounded-lg px-4 py-1 text-white self-end text-lg mr-2" style="background-color: green;">Proceed</button>
    </div>
</div>

</template>

<script>


    import {route} from 'ziggy-js';
    import axios from 'axios';
    export default {
        props: {
            id: {
                type: [String, Number],
                default: 'id' || null
            }
        },
        data() {
            return {
                selected_transaction: {
                    name: '',
                    description: '',
                    steps: [],
                },
            };
        },
        methods: {
            proceedNextStep() {
                this.$router.push({ name: 'get-queue', params: { id: this.id } });
            }
        },

        mounted() {
            axios.get(route('get-transaction', { id: this.id }))
                .then(response => {
                    this.selected_transaction = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the stations:", error.message);
                });
        },
    };

</script>
