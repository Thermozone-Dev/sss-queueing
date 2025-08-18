
<style scoped>
    .queue-form-input{
        font-weight:bold; !important;
        background-color: #F3F4F6; !important;
    }

    .queue-form-input:focus{
        font-size:1.3rem; !important;
        border-color: #84CC16; !important;
    }

    .queue-placeholder{
        font-weight:bold; !important;
    }

    .fi-fo-field-wrp-label span {
        color: #007236;
        font-weight: bold;
        font-size: 1.3rem;
    }

</style>

<div>
    <h1 class="text-3xl font-black text-black capitalize">enter your details</h1>
    <p class="text-gray-600 capitalize">ilagay ang iyong impormasyon</p>
</div>
<div class="items-center justify-center bg-white bg-white shadow rounded-xl p-5 mb-3">
    <form wire:submit.prevent="proceedConfirmation">
        {{ $this->form }}

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

