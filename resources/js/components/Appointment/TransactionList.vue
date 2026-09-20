<template>
    <div class="space-y-2">
        <h3 class="text-sm font-semibold text-[#99A1AF]">
            TRANSACTION
        </h3>

        <div
            class="min-h-[280px] rounded-lg border border-[#C0BBBB]/60 bg-white p-3"
        >
            <div
                v-if="selectedStation && transactions.length"
            >
                <ul class="space-y-2">
                    <li
                        v-for="transaction in transactions"
                        :key="transaction.id"
                        class="overflow-hidden rounded-lg border border-[#D6E4FF]"
                    >
                        <button
                            type="button"
                            @click="$emit('toggle', transaction.id)"
                            class="flex w-full items-center justify-between gap-2 p-3 text-left text-sm transition"
                            :class="
                                selectedTransactionId === transaction.id
                                    ? 'bg-[#3350E8] font-semibold text-white'
                                    : 'bg-[#F8FAFF] text-[#505050] hover:bg-[#EEF4FF]'
                            "
                        >
                            <span>{{ transaction.name }}</span>

                            <ChevronDown
                                class="h-[18px] w-[18px] shrink-0 transition-transform duration-200"
                                :class="{
                                    'rotate-180': openTransactions.includes(transaction.id)
                                }"
                            />
                        </button>

                        <div
                            v-show="openTransactions.includes(transaction.id)"
                            class="space-y-4 border-t border-[#D6E4FF] bg-white p-3"
                        >
                            <div
                                v-if="transaction.required_documents"
                                class="space-y-1"
                            >
                                <h4 class="text-sm font-semibold text-[#505050]">
                                    Required Documents:
                                </h4>

                                <div
                                    class="text-sm text-[#606060]"
                                    v-html="transaction.required_documents"
                                />
                            </div>

                            <div
                                v-if="transaction.transaction_steps?.length"
                                class="space-y-1"
                            >
                                <h4 class="text-sm font-semibold text-[#505050]">
                                    Steps:
                                </h4>

                                <ol
                                    class="list-inside list-decimal space-y-1 text-sm text-[#606060]"
                                >
                                    <li
                                        v-for="step in transaction.transaction_steps"
                                        :key="step.id"
                                    >
                                        <strong>{{ step.title }}</strong>
                                    </li>
                                </ol>
                            </div>

                            <p
                                v-else
                                class="text-sm text-gray-400"
                            >
                                No steps available.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>

            <div
                v-else
                class="flex min-h-[250px] flex-col items-center justify-center gap-4 px-3"
            >
                <div
                    class="flex size-20 items-center justify-center rounded-full border border-[#D6E4FF] bg-[#D6E4FF]/20"
                >
                    <Layers3
                        class="h-8 w-8 text-[#1E50A1]"
                        :stroke-width="1.5"
                    />
                </div>

                <div class="max-w-48 space-y-1 text-center">
                    <h3 class="text-sm font-semibold text-[#505050]">
                        {{
                            selectedStation
                                ? 'No transactions available'
                                : 'Select a station'
                        }}
                    </h3>

                    <p class="text-sm leading-5 text-[#606060]">
                        {{
                            selectedStation
                                ? 'No transactions are currently available for this station.'
                                : 'Choose a station on the left to view available transactions.'
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    ChevronDown,
    Layers3,
} from 'lucide-vue-next';

export default {
    components: {
        ChevronDown,
        Layers3,
    },

    props: {
        selectedStation: {
            type: [Number, String],
            default: null,
        },

        transactions: {
            type: Array,
            default: () => [],
        },

        selectedTransactionId: {
            type: [Number, String],
            default: null,
        },

        openTransactions: {
            type: Array,
            default: () => [],
        },
    },

    emits: ['toggle'],
};
</script>