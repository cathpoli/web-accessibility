<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { inject } from 'vue'
const swal = inject('$swal')

defineProps({
    status: String,
    members: Object,
});

const form = useForm({
    name: '',
    email: '',
    terms: false,
});

const submit = () => {
    form.post(route('accounts.store'), {
        onSuccess: () => {
            swal({
                title: "Success!",
                text: "Team member has been created successfully! Verification email has been sent.",
                type: "success",
                confirmButtonColor:"#1ad2c7",
            }).then(function() {
                location.reload();
            });
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.error(errors)
        }
    });
};

</script>
<template>
    <section>
        <div id="client-account-header" class="flex justify-between items-center">
            <div class="mx-auto px-5 md:p-12 lg:px-5 xl:p-10">
                <header class="text-center lg:text-start">
                    <h2 class="text-3xl font-semibold text-gray-900">Add Team Members</h2>

                    <p class="mt-1 text-lg text-gray-600">
                        Enter your team members' details and set their status.
                    </p>
                </header>
                <div v-if="status" class="mb-4 font-medium text-md text-green-600">
                    <br>
                    {{ status }}
                </div>
                <form @submit.prevent="submit" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" class="font-semibold">
                            Name <span class="text-red-500">*</span>
                        </InputLabel>

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 teal-border bg-transparent w-full"
                            placeholder="Enter your member's name"
                            v-model="form.name"
                            autofocus
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" class="font-semibold">
                            Email <span class="text-red-500">*</span>
                        </InputLabel>

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 teal-border bg-transparent w-full"
                            placeholder="Enter your member's work email"
                            v-model="form.email"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="flex justify-center items-center mt-4 lg:justify-start">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Submit
                        </PrimaryButton>
                    </div>
                </form>
            </div>

            <div class="hidden lg:flex">
                <img class="relative left-10" src="../../../../../../../public/assets/images/client-account-header.png" alt="Client Account Header Image"> 
            </div>
        </div>
    </section>
</template>

<style>
    #client-account-header {
        height: 420px;
    }
</style>
