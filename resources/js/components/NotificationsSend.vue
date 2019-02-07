<script>
import NotificationParamForm from './NotificationParamForm'
import tinymce from 'vue-tinymce-editor'

export default {
    components: {
        NotificationParamForm,
        tinymce,
    },
    data: () => ({
        notificationClasses: [],
        initialLoading: true,
        error: false,
        formObj: {
            notifiable: {
                name: 'App.User',
                value: '',
            },
            notification_body: '',
        },

        tinyOptions: {
            height: 300,
        },

        notifiables: [],
    }),
    created() {
        // this.getNotifiables()
    },
    mounted() {},
    methods: {
        sendNotification() {
            Nova.request()
                .post('/nova-vendor/nova-notifications/notifications/send', this.formObj)
                .then(response => {
                    this.$toasted.show('Notification has been sent!', {
                        type: 'success',
                    })
                    this.$router.push({ name: 'nova-notifications' })
                })
                .catch(error => {
                    console.log(error)
                    this.$toasted.show('There has been an error!', { type: 'error' })
                })
        },

        getNotifiableItems(name) {
            return _.find(this.notifiables.data, { name: name }).options
        },

        getNotifiables() {
            Nova.request()
                .get('/nova-vendor/nova-notifications/notifiables')
                .then(response => {
                    this.notifiables = response.data
                })
        },
    },
}
</script>

<template>
    <div>
        <heading class="mb-4">{{ __('Send Announcement') }}</heading>
        <card class="flex-col items-center justify-center" style="min-height: 300px">
            <!--div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block" for="subject">
                            Subject
                        </label>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <input v-model="subject" id="subject" type="text" class="w-full form-control form-input form-input-bordered">
                    </div>
                </div>
            </div-->
            <!-- START Notifiable Item Select -->
            <div
                class="md:flex md:items-center mb-6"
                v-if="false && formObj.notifiable.name"
            >
                <div class="md:w-1/3">
                    <label
                        class="block text-grey font-bold md:text-right mb-1 md:mb-0 pr-4"
                        for="notifiable"
                    >
                        {{ __('User') }}
                    </label>
                </div>

                <div class="md:w-2/3">
                    <div class="relative">
                        <select
                            id="notifiable"
                            v-model="formObj.notifiable.value"
                            class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                        >
                            <option
                                v-for="option in getNotifiableItems(
                                    formObj.notifiable.name
                                )"
                                :value="option.id"
                            >
                                {{ option.name }} (id:{{ option.id }})
                            </option>
                        </select>
                        <div
                            class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker"
                        >
                            <svg
                                class="fill-current h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Notifiable Item Select -->
            <div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label class="inline-block text-80 h-9 pt-2">Message</label>
                        <p class="text-sm leading-normal text-80 italic"></p>
                    </div>
                    <div class="w-1/2 px-8 py-6">
                        <tinymce
                            id="editor"
                            v-model="formObj.notification_body"
                            :other_options="tinyOptions"
                            @change="change"
                            :content="content"
                        ></tinymce>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4" align="center">
                <button
                    class="ml-auto btn btn-default btn-primary mr-3"
                    @click="sendNotification"
                >
                    Send
                </button>
            </div>
        </card>
    </div>
</template>

<style>
/* Scoped Styles */
</style>
