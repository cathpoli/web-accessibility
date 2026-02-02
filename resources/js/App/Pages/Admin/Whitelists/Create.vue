<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { useNotificationStore } from '@dafcoe/vue-notification'

const { setNotification } = useNotificationStore()

const form = useForm({
    domain: '',
});

const submit = () => {
    form.post(route('admin.whitelists.store'), {
        onSuccess: (res) => setNotification({type: 'success', message: 'Successfully whitelisted domain.'}),
        onError: (err) => console.log(err)
    });
};

</script>

<template>
    <Head title="New Whitelist" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Whitelist</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Add new domain to be whitelisted</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Domain added will be allowed to use our widget without subscription.
                            </p>
                        </header>

                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name" >
                                    Domain <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="domain"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.domain"
                                    autofocus
                                    autocomplete="domain"
                                />

                                <InputError class="mt-2" :message="form.errors.domain" />
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
