<script setup>
import { ref } from 'vue'
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3';
import { useNotificationStore } from '@dafcoe/vue-notification'

const { setNotification } = useNotificationStore()

const type_selected = ref(null)
const duration_selected = ref(null)
const applies_to_checked = ref(false)

const props = defineProps({
    products: Array
})

const form = useForm({
    id: '',
    name: '',
    type: '',
    percent_off: '',
    discount_amount: '',
    period_off: '',
    duration: '',
    number_of_months: '',
    applies_to_checkbox: false,
    applies_to: '',
});

const changetype = (event) => type_selected.value = event.target.value
const changeDuration = (event) => duration_selected.value = event.target.value
const changeAppliesToCheckbox = (event) => applies_to_checked.value = event.target.checked

const submit = () => {
    let endpoint = type_selected.value !== 'period_off' ? 'store' : 'store-period-off'

    form.post(route(`admin.coupons.${endpoint}`), {
        onSuccess: (res) => setNotification({type: 'success', message: 'Successfull created coupon.'}),
        onError: (err) => console.log(err)
    });
};

</script>

<template>
    <Head title="New Coupon" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Coupon</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Coupon for subscription discounts</h2>

                            <!-- <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information and email address.
                            </p> -->
                        </header>

                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name" >
                                    <span class="font-semibold">Code </span> (Optional)
                                </InputLabel>

                                <TextInput
                                    id="id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.id"
                                    autofocus
                                    autocomplete="id"
                                />

                                <InputError class="mt-2" :message="form.errors.id" />
                            </div>

                            <div>
                                <InputLabel for="name" class="font-semibold">
                                    Name <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autocomplete="name"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="type" class="font-semibold">
                                    Type <span class="text-red-500">*</span>
                                </InputLabel>

                                <select id="type" name="type" v-model="form.type" class="select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" @change="changetype($event)">
                                    <option value="percent_off">Percentage Amount</option>
                                    <option value="amount_off">Fixed Amount Discount</option>
                                    <option value="period_off">Period Off</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div v-if="type_selected === 'period_off'">
                                <small class="text-gray-500">
                                    NOTE: Period off discounts (subscription's first few months are free) do not work the same as <a class="text-blue-500" href="https://stripe.com/docs/billing/subscriptions/coupons" target="_blank" rel="noopener noreferrer">Stripe Coupons</a>, thus they are not saved on the Stripe Dashboard. To achieve this coupon type we have used a
                                    combination of <a class="text-blue-500" href="https://stripe.com/docs/billing/subscriptions/prorations" target="_blank" rel="noopener noreferrer">Prorations</a> and <a class="text-blue-500" href="https://stripe.com/docs/billing/subscriptions/billing-cycle" target="_blank" rel="noopener noreferrer">Billing Cycle</a>.
                                </small>
                            </div>

                            <div v-if="type_selected === 'percent_off'">
                                <InputLabel for="percent_off" class="font-semibold">
                                    Percentage off <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="percent_off"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.percent_off"
                                    autocomplete="percent_off"
                                />

                                <InputError class="mt-2" :message="form.errors.percent_off" />
                            </div>

                            <div v-if="type_selected === 'amount_off'">
                                <InputLabel for="discount_amount" class="font-semibold">
                                    Discount amount <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="discount_amount"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.discount_amount"
                                    autocomplete="discount_amount"
                                />

                                <InputError class="mt-2" :message="form.errors.discount_amount" />
                            </div>

                            <div v-if="type_selected === 'period_off'">
                                <InputLabel for="period_off" class="font-semibold">
                                    Period Off <span class="text-red-500">*</span>
                                </InputLabel>

                                <select id="period_off" v-model="form.period_off" class="select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option :value="n" v-for="(n, index) in 12" :key="index">{{ n }} months</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.period_off" />
                            </div>

                            <div v-if="type_selected !== 'period_off'">
                                <InputLabel for="duration" class="font-semibold">
                                    Duration <span class="text-red-500">*</span>
                                </InputLabel>

                                <select id="duration" name="duration"  v-model="form.duration" class="select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" @change="changeDuration($event)">
                                    <option value="forever">Forever</option>
                                    <option value="once">Once</option>
                                    <option value="repeating">Multiple Months</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.duration" />
                            </div>

                            <div v-if="duration_selected === 'repeating'">
                                <InputLabel for="number_of_months" class="font-semibold">
                                    Number of months <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="number_of_months"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.number_of_months"
                                    autocomplete="number_of_months"
                                />

                                <InputError class="mt-2" :message="form.errors.number_of_months" />
                            </div>

                            <div v-if="type_selected !== null && type_selected !== 'period_off'">
                                <InputLabel for="applies_to_checkbox" class="font-semibold">
                                    <input type="checkbox" class="toggle" v-model="form.applies_to_checkbox" @change="changeAppliesToCheckbox($event)" /> Apply to specific product
                                </InputLabel>

                                <InputError class="mt-2" :message="form.errors.applies_to_checkbox" />
                            </div>

                            <div v-if="applies_to_checked && type_selected !== 'period_off'">
                                <select id="applies_to" name="applies_to"  v-model="form.applies_to" class="select mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option :value="product.stripe_product" v-for="(product, index) in products" :key="index">{{ product.name }} - ${{ product.price }} / {{ product.interval }}</option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.applies_to" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
