<template>
  <div class="ba-cheetah-media-container" :class="`ba-cheetah-media-container-${type_editor}`">
    <header class="ba-cheetah-media-container__header-wrapper">
      <h1 class="wp-heading-inline">{{ builderall_localize.builderall_images }}</h1>
      <div class="wp-filter">
        <div class="filter-items">
          <input type="hidden" name="mode" value="list">
          <div class="view-switch">
            <a href="#" class="view-list current" id="view-switch-list" aria-current="page"><span
                class="screen-reader-text">{{ builderall_localize.view_list }}</span></a>
            <a href="#" class="view-grid" id="view-switch-grid"><span
                class="screen-reader-text">{{ builderall_localize.view_grid }}</span></a>
          </div>

          <label for="attachment-ba-filter" class="screen-reader-text"> {{
              builderall_localize.filter_by_directories
            }}</label>
          <select class="attachment-filters" name="attachment-ba-filter" id="attachment-ba-filter"
                  v-model="filters.directory">
            <option value="all">{{ builderall_localize.all_directories }}</option>
            <option v-for="(dir, i) in directories" :key="i" :value="dir.id">{{ dir.name }}</option>
          </select>
        </div>

        <div class="search-form">
          <label for="media-ba-search-input" >{{builderall_localize.search}}</label>
          <input type="search" id="media-ba-search-input" :placeholder="builderall_localize.search" v-model="filters.search">
        </div>
      </div>
    </header>

    <div class="ba-cheetah-media-container__loading" v-if="loading">Loading</div>
    <template v-else>
      <template v-if="filters.directory && !this.filters.search && files_filtered.length === 0">
        <p class="ba-cheetah-media-container__no-media">{{ builderall_localize.search_no_image_directory }}</p>
      </template>
      <template v-else-if="filters.directory && this.filters.search && files_filtered.length === 0">
        <p class="ba-cheetah-media-container__no-media">{{ builderall_localize.search_no_image_directory_found }}</p>
      </template>
      <div v-else-if="files_filtered.length > 0">
        <div class="ba-cheetah-media-container__media-content">
          <article class="ba-cheetah-media" v-for="(file, index) in files_filtered" :id="file.id" :ref="`ba-cheetah-media-content-${file.id}`"
                  :key="index"
                  v-if="index < files_filtered_show_max">
            <div>
              <img :src="`https://storage.builderall.com${file.path}/${file.file}`" alt="" width="100%" @click="download(file)"/>
              <div class="ba-cheetah-media-message" data-status="loading">
                <img :src="`${builderall_localize.plugin_url}/img/ajax-loader-small.svg`">
                <span>{{ builderall_localize.loading }}</span>
              </div>
              <div class="ba-cheetah-media-message" data-status="success">
                  <img :src="`${builderall_localize.plugin_url}/img/svg/check.svg`">
                <span>{{ builderall_localize.success }}</span>
              </div>
              <div class="ba-cheetah-media-message" data-status="error">
                  <img :src="`${builderall_localize.plugin_url}/img/svg/error.svg`">
                <span>{{ builderall_localize.error }}</span>
              </div>
            </div>
            <span class="ba-cheetah-media-filename">{{ file.name }}</span>
          </article>
        </div>
        <div class="load-more-wrapper">
            <p class="load-more-count"> 
                {{builderall_localize.showing}}: {{ files_filtered_show_max > files_filtered.length ? files_filtered.length : files_filtered_show_max }}.
                {{builderall_localize.total}}: {{files.length}} 
            </p>
            <button v-if="files_filtered_show_max < files.length" class="button button-primary" @click="loadMore">
                {{ builderall_localize.load_more }}
            </button>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import BaImagesService from "./image-service";
import {localize} from './api';

export default {
  props: {
    type_editor: {
      type: String,
      default: 'media-router'
    }
  },
  data() {
    return {
      loading: true,
      files_filtered: [],
      files_filtered_show_max: 30,
      filters: {
        directory: 'all',
        search: '',
      },
      builderall_localize: {},
      files: [],
      directories: []
    }
  },
  watch: {
    'filters.directory': function () {
      this.filter();
    },

    'filters.search': function () {
      this.filter();
    },
  },
  methods: {
    filter() {
      const files = JSON.parse(JSON.stringify(this.files));
      let search = this.filters.search;
      let directory = this.filters.directory;

      if (!directory) {
        return;
      }

      if (directory === 'all') {

        this.files_filtered = search ?
            files.filter((file) => file.name.indexOf(search) > -1) :
            files;
      } else {

        this.files_filtered = search ?
            files.filter((file) => directory === file.id_directory && file.name.indexOf(search) > -1) :
            files.filter((file) => directory === file.id_directory);
      }
    },

    loadMore() {
      if (this.files_filtered_show_max < this.files.length) {
        this.files_filtered_show_max += 20;
      }
    },

    activeStatus(el, status, show = false) {
      if (show) {
        el.querySelector(`[data-status="${status}"]`).classList.add('active')
      } else {
        el.querySelector(`[data-status="${status}"]`).classList.remove('active')
      }
    },

    mediaRouter(id) {
      if (this.type_editor === 'media-router' && wp.media && wp.media.frame && wp.media.frame.el) {
        let mediaModal = wp.media.frame.el;
        let mediaTab = mediaModal.querySelector("#menu-item-browse");
        if (mediaTab) {
          mediaTab.click();
        }

        setTimeout(function () {
          if (wp.media.frame.content.get() !== null) {
            wp.media.frame.content.get().collection._requery(true);
          }

          var selection = wp.media.frame.state().get("selection");
          var selected = parseInt(id);
          selection.reset(selected ? [wp.media.attachment(selected)] : []);
        }, 150);
      }
    },

    download(file) {
      let data = {
        id: file.id,
        filename: file.file,
        custom_filename: file.name,
        title: file.name,
        alt: file.name,
        image_url: `https://storage.builderall.com${file.path}/${file.file}`,
      };

      var vm = this;
      let el = this.$refs[`ba-cheetah-media-content-${file.id}`];

      if (!el) {
        return;
      }

      this.activeStatus(el[0], 'loading', true);

      BaImagesService.download(JSON.stringify(data))
          .then(function (res) {

            let response = res.data;
            let attachment = response.attachment;

            vm.mediaRouter(attachment.id);

            if (response) {
              if (response.success) {
                vm.activeStatus(el[0], 'success', true);

                setTimeout(function () {
                  vm.activeStatus(el[0], 'success');
                }, 3000);
              }else{
                vm.activeStatus(el[0], 'error', true);

                setTimeout(function () {
                  vm.activeStatus(el[0], 'error');
                }, 3000);
              }
            }
          })
          .finally(() => vm.activeStatus(el[0], 'loading'))
          .catch(function (error) {
            console.log(error);
          });
    },

  },
  created() {
    this.builderall_localize = localize();
    document.addEventListener('scroll', this.handleScroll);

    BaImagesService.index()
        .then(({data}) => {

          const {files, directories} = data;

          this.files = files;
          this.files_filtered = JSON.parse(JSON.stringify(this.files));
          this.directories = directories;
        })
        .finally(() => this.loading = false);
  }
};
</script>

<style lang="scss">
.media-frame-content {
  .ba-cheetah-media-container {

    .ba-cheetah-media-container__header-wrapper {
      height: 60px;
      margin: 0 15px;

      .wp-heading-inline, .view-switch {
        display: none;
      }

      .wp-filter {
        display: flex;
        height: 100%;
        justify-content: space-between;
        align-items: center;
      }

      .search-form, .filter-items {
        height: 40px;

        select {
          margin-top: 0;
        }

        input {
          width: auto;
        }
      }
    }
  }
}

.ba-cheetah-media-container {
  margin: 15px 20px 15px 5px;

  &-media-router {
    margin: 10px 5px;
  }

  .ba-cheetah-media-container__loading, .ba-cheetah-media-container__no-media {
    display: block;
    color: #646970;
    font-size: 18px;
    font-style: normal;
    margin: 0;
    padding: 100px 0 0;
    text-align: center;
  }

  .ba-cheetah-media-container__header-wrapper {

    h1 {
      font-size: 23px;
      font-weight: 400;
      margin: 0;
      padding: 9px 0 4px 0;
      line-height: 1.3;
    }

    .search-form {
      display: flex;
      align-items: center;

      label {
        margin-right: 5px;
      }
    }
  }

  .ba-cheetah-media-container__media-content {
    display: grid;
    grid-template-columns: 1fr;
    grid-column-gap: 10px;
    grid-row-gap: 10px;

    @media (min-width: 576px) {
      grid-template-columns: repeat(2, 1fr);
    }

    @media (min-width: 768px) {
      grid-template-columns: repeat(3, 1fr);
    }

    @media (min-width: 992px) {
      grid-template-columns: repeat(4, 1fr);
    }

    @media (min-width: 1200px) {
      grid-template-columns: repeat(5, 1fr);
    }
  }

  .ba-cheetah-media {
    display: flex;
    flex-direction: column;
    cursor: pointer;

    div {
      display: inline-flex;
      width: 100%;
      position: relative;

      img {
        width: 100%;
        object-fit: contain;
        height: 210px;
      }
    }

    .ba-cheetah-media-filename {
      display: block;
      font-size: 14px;
      color: #1D2327;
      text-align: center;
      max-height: 24px;
      padding: 10px 5px;
      opacity: .8;
    }

    .ba-cheetah-media-message {
      position: absolute;
      top: 0;
      background-color: #1D2327;
      opacity: 0.8;
      color: #FFF;
      width: 100%;
      height: 100%;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      display: none;

      img {
        width: 28px;
        height: 28px;
        margin-bottom: 4px;
      }

      &.active {
        display: flex;
      }
    }

    &:hover span {
      opacity: 1;
    }
  }
}
</style>
