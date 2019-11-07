<template>
    <panel-item :field="field">
        <template slot="value" v-if="loaded">
            <template v-if="failed">
                <span class="pl-2">&mdash;</span>
            </template>
            <div class="w-full" v-html="code" v-else></div>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
        loaded: false,
        code: null,
        failed: false
    }),

    methods: {
        loadImage() {
            if (!this.validURL(this.field.value)) {
                this.loaded = true;
                this.failed = true;
                return;
            }

            Nova.request()
                .get('/nova-vendor/ericlagarda/nova-embed/embed', {
                    params: { url: this.field.value, cache: this.cacheTime() },
                })
                .then(response => {
                    if (response.data) {
                        if (response.data.code != null) {
                            this.code = response.data.code;    
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
