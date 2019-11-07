<template>
    <p v-if="field.value">

        <template v-if="failed">
            <span class="pl-2">&mdash;</span>
        </template>

        <template v-else>

            <template v-if="field.viewInIndex">

                <img v-if="loaded && image" :src="image" style="object-fit: cover;" class="rounded-full w-8 h-8 cursor-pointer" @click="showPreview" :title="this.__('Click to Preview')" :alt="this.__('Click to Preview')" />

                <span v-else>
                    <loader width="30" class="ml-0" />
                </span> 

            </template>

            <template v-else>
                 <img v-if="loaded && image" :src="image" style="object-fit: cover;" class="rounded-full w-8 h-8" />

                <span v-else>
                    <loader width="30" class="ml-0" />
                </span> 

            </template>
        </template>


        <modal-show :active="showModal" :field="field" v-on:closePreviewModal="closeModal"></modal-show>
    </p>
</template>

<script>

import ModalShow from './ModalShow';

export default {
    props: ['resourceName', 'field'],

    components: {
        'modal-show':ModalShow
    },

    data: () => ({
        loaded: false,
        image: null,
        showModal: false,
        failed: false
    }),

    methods: {
        loadImage() {
            if (!this.validURL(this.field.value)) {
                this.failed = true;
                this.loaded = true;
                return;
            }

            Nova.request()
                .get('/nova-vendor/ericlagarda/nova-embed/embed', {
                    params: { url: this.field.value, cache: this.cacheTime() },
                })
                .then(response => {
                    if (response.data) {
                        if (response.data.image != null) {
                            this.image = response.data.image;
                        } else {
                            this.failed = true;
                        }
                    } else {
                        this.failed = true;
                    }
                    this.loaded = true;
                });
        },

        cacheTime() {
            if (this.field.time != null) {
                return this.field.time;
            }

            return null;
        },

        validURL(str) {
            var regex = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/;

            var pattern = new RegExp(regex)

            return !!pattern.test(str);
        },

        showPreview() {
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    },

    mounted() {
        if (!this.field.ajax) {
            this.image = this.field.embed.image;
            this.loaded = true;
        } else {
            this.loadImage();
        }
    },
};
</script>
