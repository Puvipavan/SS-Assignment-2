import Vue from "vue";
import MediaContainer from "./components/MediaContainer.vue";

new Vue({
    render: (h) => h(MediaContainer, {type_editor: 'classic'}),
}).$mount("#ba-cheetah-media-container");
