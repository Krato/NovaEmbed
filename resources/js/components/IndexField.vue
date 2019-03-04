<template>
    <p v-if="field.value">
        <img v-if="loaded && image" :src="image" style="object-fit: cover;" class="rounded-full w-8 h-8" />
        <span v-else>&mdash;</span>
    </p>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    data: () => ({
        loaded: false,
        image: null,
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
