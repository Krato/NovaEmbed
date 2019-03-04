Nova.booting(Vue => {
    Vue.component('index-nova-embed', require('./components/IndexField'));
    Vue.component('detail-nova-embed', require('./components/DetailField'));
    Vue.component('form-nova-embed', require('./components/FormField'));
});
