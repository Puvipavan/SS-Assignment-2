import Vue from 'vue';
import MediaContainer from './components/MediaContainer';

let BACheetahActiveFrameId = '';
let BACheetahActiveFrame = '';

const BACheetahOldMediaFrame = wp.media.view.MediaFrame.Post;
const BACheetahOldMediaFrameSelect = wp.media.view.MediaFrame.Select;

wp.media.view.MediaFrame.Select = BACheetahOldMediaFrameSelect.extend({
    browseRouter(routerView) {
        BACheetahOldMediaFrameSelect.prototype.browseRouter.apply(this, arguments);
        routerView.set({
            builderallimages: {
                text: builderall_media_localize.builderall_images,
                priority: 120,
            },
        });
    },

    bindHandlers() {
        BACheetahOldMediaFrameSelect.prototype.bindHandlers.apply(this, arguments);
        this.on('content:create:builderallimages', this.frameContent, this);
    },
});

wp.media.view.MediaFrame.Post = BACheetahOldMediaFrame.extend({
    browseRouter(routerView) {
        BACheetahOldMediaFrameSelect.prototype.browseRouter.apply(this, arguments);
        routerView.set({
            builderallimages: {
                text: builderall_media_localize.builderall_images,
                priority: 120,
            },
        });
    },

    bindHandlers() {
        BACheetahOldMediaFrame.prototype.bindHandlers.apply(this, arguments);
        this.on('content:create:builderallimages', this.frameContent, this);
    },
});

const builderallImagesMediaTab = () => {

    if (
        wp.media &&
        wp.media.frame &&
        wp.media.frame.el
    ) {
        let mediaModal = wp.media.frame.el;

        let selectedTab = mediaModal.querySelector(
            '.media-router button.media-menu-item.active'
        );

        if (!selectedTab) {
            return false;
        }

        if (selectedTab.id !== 'menu-item-builderallimages') {
            return;
        }

        let state = wp.media.frame.state();

        if (!state) {
            return false;
        }

        BACheetahActiveFrame = wp.media.frame.el;
        BACheetahActiveFrameId = wp.media.frame.state().id;
    }

    let html = createMediaWrapper();

    if (!BACheetahActiveFrame) {
        return false;
    }

    let modal = wp.media.frame.el.querySelector('.media-frame-content');

    if (!modal) {
        return false;
    }

    modal.innerHTML = ''; // Clear Modal
    modal.appendChild(html); // Append Builderall Images

    let element = modal.querySelector('#builderall-images-media-router-' + BACheetahActiveFrameId);
    if (!element) {
        return false;
    }

    new Vue({
        render: (h) => h(MediaContainer),
    }).$mount('#builderall-images-media-router-' + BACheetahActiveFrameId);
};

// Create HTML markup
const createMediaWrapper = () => {
    let wrapper = document.createElement('div');
    wrapper.classList.add('builderall-img-container');
    let container = document.createElement('div');
    container.classList.add('builderall-images-wrapper');
    let frame = document.createElement('div');
    frame.setAttribute('id', 'builderall-images-media-router-' + BACheetahActiveFrameId);

    container.appendChild(frame);
    wrapper.appendChild(frame);

    return wrapper;
};

// Document Ready
jQuery(document).ready(function ($) {
    if (wp.media) {
        // Open Media Frame
        wp.media.view.Modal.prototype.on('open', function () {
            builderallImagesMediaTab();
        });

        // Click Handler
        $(document).on('click', '.media-router button.media-menu-item, .media-menu button.media-menu-item[id="menu-item-gallery-library"]', function () {
            builderallImagesMediaTab();
        });
    }
});
