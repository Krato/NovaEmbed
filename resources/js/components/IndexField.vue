<template>
    <p v-if="field.value">

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
        showModal: false
    }),

    methods: {
        loadImage() {
            if (!this.validURL(this.field.value)) {
                this.loaded = true;
                return;
            }

            Nova.request()
                .get('/nova-vendor/ericlagarda/nova-embed/embed', {
                    params: { url: this.field.value, cache: this.cacheTime() },
                })
                .then(response => {
                    if (response.data) {
                        this.image = response.data.image;
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
            var pattern = new RegExp(
                '^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                    '(\\#[-a-z\\d_]*)?$',
                'i'
            ); // fragment locator
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
