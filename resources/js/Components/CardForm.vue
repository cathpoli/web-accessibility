<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from 'vue'
// import { useToast } from "vue-toastification";
import { useNotificationStore } from '@dafcoe/vue-notification'

const { setNotification } = useNotificationStore()
// const toast = useToast();

const props = defineProps({
    stripe_key: String,
    intent: Object,
});

var stripe, cardNumberElement, cardExpiryElement, cardCvcElement

const form = useForm({
    set_as_default: true,
    payment_method: '',
});

onMounted(() => {
    stripe = Stripe(props.stripe_key);
    var elements = stripe.elements();

    var style = {
        base: {
            lineHeight: '40px',
            fontSize: '15px',
        },
    };

    cardNumberElement = elements.create('cardNumber', {
        showIcon: true,
        style: style,
        placeholder: '**** **** **** ****',
    });

    cardExpiryElement = elements.create('cardExpiry', {
        style: style,
        placeholder: 'MM/YY',
    });

    cardCvcElement = elements.create('cardCvc', {
        style: style,
        placeholder: '***',
    });

    cardNumberElement.mount('#card-number-element');
    cardExpiryElement.mount('#card-expiry-element');
    cardCvcElement.mount('#card-cvc-element');
});

const submit = async (e) => {
    const { setupIntent, error } = await stripe.confirmCardSetup(
        props.intent.client_secret, {
            payment_method: {
                card: cardNumberElement,
                billing_details: {
                    name: document.getElementById('card_name').value
                }
            }
        }
    )

    if (error) {
        /** TODO: show error message */
        // console.error(error)
        // toast.error(error.message);
        setNotification({type: 'alert', message: error.message})
    } else {
        form.payment_method = setupIntent.payment_method
        form.post(route('cards.store'), {
            onSuccess: () => {
                // toast.success('Successfully added payment method.')
                setNotification({type: 'success', message: 'Successfully added payment method.'})
            },
            onError: (error) => {
                // toast.error(error.response.data.message)
                setNotification({type: 'alert', message: error.message})
            }
        });
    }
};

</script>

<template>
    <form @submit.prevent="submit" class="pt-1">
        <div class="flex">
            <div class="flex-1">
                <InputLabel for="card_name" value="Card Name" class="font-semibold" />

                <TextInput
                    id="card_name"
                    type="text"
                    class="mt-1 block w-full stripe-field h-10"
                    required
                    autofocus
                    autocomplete="card_name"
                />

                <InputError class="mt-2" :message="form.errors.card_name" />
            </div>
        </div>

        <div class="flex mt-4">
            <div class="flex-1">
                <InputLabel for="card_number" value="Card Number" class="font-semibold" />
                <div id="card-number-element" class="stripe-field"></div>
            </div>
        </div>

        <div class="flex gap-4 mt-4">
            <div class="flex-1 w-4/6">
                <InputLabel for="card_expiry" value="Card Expiry" class="font-semibold" />
                <div id="card-expiry-element" class="stripe-field input-field"></div>
            </div>
            <div class="w-2/6">
                <InputLabel for="card_cvc" value="Cvc/Cvv" />
                <div id="card-cvc-element" class="stripe-field"></div>
            </div>
        </div>

        <div class="flex items-center mt-4" :class="$page.url === '/billing/cards/create' ? 'justify-between' : 'justify-end'">
            <label class="relative inline-flex items-center cursor-pointer" v-if="$page.url === '/billing/cards/create'">
                <input
                    type="checkbox"
                    class="sr-only peer"
                    v-model="form.set_as_default" :checked="form.set_as_default">
                <div class="w-11 h-6 teal-bg-desaturated peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-400"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Set as default</span>
            </label>

            <PrimaryButton class="ml-4 teal-btn" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save Card
            </PrimaryButton>
        </div>
    </form>
</template>

<style>

.stripe-field {
    border: solid 1px #1ad2c7;;
    padding: 0px 10px 0px 10px; 
    margin-top: 0.25rem;
    border-radius: 0.375rem;

}
</style>