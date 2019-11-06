<template>
    <portal to="modals" name="Show Embed" transition="fade-transition">
        <modal v-if="active"  @modal-close="handleClose" class="z-100">
            <div class="bg-white rounded-lg shadow-lg " style="width: 720px;">
                <div class="p-8">
                    <!-- <heading :level="2" class="mb-6">{{ __('Show embed') }}</heading> -->
                    <div v-if="source" v-html="source" class="responsive" :style="paddingBottom">

                    </div>

                    <loader v-else width="30" class="ml-0" />
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button dusk="cancel-upload-delete-button" type="button" data-testid="cancel-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </modal>
    </portal>
</template>

<script>

export default {
    props: {
        active: {
            type: Boolean,
            default: false,
            required: true,
        },
        field: {
            required: true
        }
    },

    data: () => ({
       source: null,
       aspectRatio: null,
    }),

    methods: {
        
        loadSource() {

            Nova.request()
                .get('/nova-vendor/ericlagarda/nova-embed/embed', {
                    params: { url: this.field.value, cache: this.cacheTime() },
                })
                .then(response => {
                    if (response.data) {
                        this.source = response.data.code;
                        this.aspectRatio = response.data.aspect;
                        console.log(response.data)
                    }
                });

        },

        cacheTime() {
            if (this.field.time != null) {
                return this.field.time;
            }

            return null;
        },

        handleClose() {
            this.$emit('closePreviewModal', true);
        },
    },

    watch: {

        active: function (value) {
            console.log(value)
            if (value == true) {
                this.loadSource();
            }
        },
    },

    computed: {

        paddingBottom() {
            return {
                'padding-bottom': + this.aspectRatio + 'px',
                'height': '405px'
            };
        }
        
    }
};
</script>

<style>
.responsive {
  position: relative;
  overflow: hidden;
  width: 100%;
  height: auto;
}
.responsive iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style>
