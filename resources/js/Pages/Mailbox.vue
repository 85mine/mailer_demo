<script setup>

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {useForm} from "@inertiajs/inertia-vue3";
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref} from "vue";

const to = ref(null);
const subject = ref(null);
const message = ref(null);

const form = useForm({
    to: '',
    subject: '',
    message: '',
});

const submit = () => {
    form.post(route('mailbox.send'));
};

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                                <div>
                                    <ApplicationLogo class="block h-12 w-auto"/>
                                </div>

                                <div class="mt-8 text-2xl">
                                    Welcome to mailbox
                                </div>

                                <div class="mt-6 text-gray-500">
                                    <form class="w-full max-w-lg" @submit.prevent="submit">
                                        <div class="col-span-6 sm:col-span-4 mb-6">
                                            <InputLabel for="to" value="To"/>
                                            <TextInput
                                                id="to"
                                                ref="to"
                                                v-model="form.to"
                                                type="text"
                                                class="mt-1 block w-full"
                                                autocomplete="to"
                                            />
                                            <InputError :message="form.errors.to" class="mt-2"/>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 mb-6">
                                            <InputLabel for="subject" value="Subject"/>
                                            <TextInput
                                                id="subject"
                                                ref="subject"
                                                v-model="form.subject"
                                                type="text"
                                                class="mt-1 block w-full"
                                                autocomplete="subject"
                                            />
                                            <InputError :message="form.errors.subject" class="mt-2"/>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 mb-6">
                                            <InputLabel for="message" value="Message"/>
                                            <TextInput
                                                id="message"
                                                ref="message"
                                                v-model="form.message"
                                                type="text"
                                                class="mt-1 block w-full"
                                                autocomplete="message"
                                            />
                                            <InputError :message="form.errors.message" class="mt-2"/>
                                        </div>
                                        <div class="flex items-center justify-end mt-4">
                                            <div v-if="$page.props.errors.flash_message" class="text-red-600">
                                                {{ $page.props.errors.flash_message }}
                                            </div>
                                            <div class="alert">
                                                <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                                                    <div v-if="$page.props.flash.message" class="text-green-600">
                                                        {{ $page.props.flash.message }}
                                                    </div>
                                                </ActionMessage>
                                            </div>
                                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                                           :disabled="form.processing">
                                                Send
                                            </PrimaryButton>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
