<template>
    <panel-item :field="field">
        <template slot="value" v-if="loaded">
            <div class="w-full" v-html="code"></div>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
        loaded: false,
        code: null,
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
                        this.code = response.data.code;
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
            this.code = this.field.embed.code;
            this.loaded = true;
        } else {
            this.loadImage();
        }
    },
};
</script>
