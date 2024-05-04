!function(i){var r={};function a(e){var t;return(r[e]||(t=r[e]={i:e,l:!1,exports:{}},i[e].call(t.exports,t,t.exports,a),t.l=!0,t)).exports}a.m=i,a.c=r,a.d=function(e,t,i){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(a.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)a.d(i,r,function(e){return t[e]}.bind(null,r));return i},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=1)}([function(e,t,i){"use strict";function a(e){return(a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){for(var i=0;i<t.length;i++){var r=t[i];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,function(e){e=function(e,t){if("object"!==a(e)||null===e)return e;var i=e[Symbol.toPrimitive];if(void 0===i)return("string"===t?String:Number)(e);i=i.call(e,t||"default");if("object"!==a(i))return i;throw new TypeError("@@toPrimitive must return a primitive value.")}(e,"string");return"symbol"===a(e)?e:String(e)}(r.key),r)}}var n=function(){function t(){if(!(this instanceof t))throw new TypeError("Cannot call a class as a function");t.msg=t.msg||__("Loading","email-subscribers")}var e,i,r;return e=t,(i=[{key:"view",value:function(e){return m("div",{class:"absolute w-full mt-48 flex flex-col justify-center text-center items-center space-y-4"},m("div",{class:"text-lg text-gray-600"},t.msg||""),m("div",{class:"text-indigo-600"},m("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-16 h-16",stroke:"currentColor",fill:"none",viewBox:"0 0 57 57"},m("g",{transform:"translate(1 1)","stroke-width":"2",fill:"none","fill-rule":"evenodd"},m("circle",{cx:"5",cy:"50",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",values:"50;5;50;50",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",begin:"0s",dur:"2.2s",values:"5;27;49;5",calcMode:"linear",repeatCount:"indefinite"})),m("circle",{cx:"27",cy:"5",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",from:"5",to:"5",values:"5;50;50;5",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",begin:"0s",dur:"2.2s",from:"27",to:"27",values:"27;49;5;27",calcMode:"linear",repeatCount:"indefinite"})),m("circle",{cx:"49",cy:"50",r:"5"},m("animate",{attributeName:"cy",begin:"0s",dur:"2.2s",values:"50;50;5;50",calcMode:"linear",repeatCount:"indefinite"}),m("animate",{attributeName:"cx",from:"49",to:"49",begin:"0s",dur:"2.2s",values:"49;5;27;49",calcMode:"linear",repeatCount:"indefinite"}))))))}}])&&s(e.prototype,i),r&&s(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}(),r={items:[],loadItems:function(){return n.showLoader=!0,m.request({method:"GET",url:ajaxurl,params:{action:"ig_es_get_gallery_items",security:ig_es_js_data.security},withCredentials:!0}).then(function(e){r.items=e.data.items,n.showLoader=!1})},loadTemplatePreviewData:function(e,t){return n.showLoader=!0,m.request({method:"GET",url:ajaxurl,params:{action:"ig_es_preview_template",gallery_type:t,security:ig_es_js_data.security,template_id:e},withCredentials:!0}).then(function(e){return n.showLoader=!1,e})},deleteTemplate:function(e){return m.request({method:"GET",url:ajaxurl,params:{action:"ig_es_delete_template",security:ig_es_js_data.security,template_id:e},withCredentials:!0}).then(function(e){return e})}},l=r,o={view:function(e){var i=e.attrs.item,t=e.attrs.campaignType,r=i.gallery_type,e=i.es_plan,a=i.template_version,e=canUpsellESTemplate(e,a),s=g.manageTemplates,n=!s&&!e,l=s&&"local"===r,s=s&&"remote"===r&&!e;return m("div",null,m("div",{class:"h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden relative"},e?m("span",{class:"absolute top-1 right-2"},m("a",{href:"https://www.icegram.com/documentation/how-to-manage-custom-fields-in-email-subscribers?utm_source=in_app&utm_medium=custom_form_field&utm_campaign=es_upsell",target:"_blank"})):"",m("div",{class:"cursor-pointer",onclick:function(){return g.showPreview(i,t,r)}},i.thumbnail?m("img",{class:"lg:h-48 md:h-36 w-full object-contain object-center",src:i.thumbnail,alt:"{item.title}"}):m("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-40 w-full mb-8 ",fill:"none",viewBox:"0 0 24 24",stroke:"#d2d6dc"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"1",d:"M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"}))),m("div",{class:"p-4 bg-white h-28"},m("div",{class:"flex items-center flex-wrap whitespace-nowrap"},i.categories.map(function(e,t){t=i.categories[t].replace(/_/g," ");return"1.0.0"==a&&("starter"===t?t="pro":"pro"===t&&(t="max")),m("span",{class:"es-tmpl-category capitalize mr-2 inline-flex items-center leading-none py-1 px-1 text-xs rounded"},t)})),m("h4",{onclick:function(){return g.showPreview(i,t,r)},class:"title-font text-lg font-medium text-gray-900 mb-3 mt-2 sm:truncate cursor-pointer hover:underline"},i.title),m("div",{class:"flex items-center flex-wrap "},n&&m("a",{href:"?action=ig_es_import_gallery_item&template-id="+i.ID+"&campaign-type="+t+"&gallery-type="+r+"&_wpnonce="+ig_es_js_data.security,class:"font-semibold text-base text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0"},__("Use this","email-subscribers"),m("svg",{class:"w-4 h-4 ml-2",viewBox:"0 0 24 24",stroke:"currentColor","stroke-width":"2",fill:"none","stroke-linecap":"round","stroke-linejoin":"round"},m("path",{d:"M5 12h14"}),m("path",{d:"M12 5l7 7-7 7"}))),l&&m("a",{href:"admin.php?page=es_template&id="+i.ID+"&action=edit",class:"font-semibold text-small text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0",title:__("Edit this template","email-subscribers")},m("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-4 h-4"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"}))),l&&m("a",{href:"?action=ig_es_duplicate_template&template-id="+i.ID+"&_wpnonce="+ig_es_js_data.security,class:"ml-2  font-semibold text-small text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0",title:__("Duplicate this template","email-subscribers")},m("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-4 h-4"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"}))),l&&m("a",{onclick:function(){return g.deleteGalleryTemplate(i.ID)},class:"cursor-pointer ml-2 font-semibold text-small text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0",title:__("Delete this template","email-subscribers")},m("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-4 h-4"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"}))),s&&m("a",{href:"?action=ig_es_import_remote_gallery_template&template-id="+i.ID+"&_wpnonce="+ig_es_js_data.security,class:"font-semibold text-base text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0",title:__("Import this template","email-subscribers")},m("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-4 h-4"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"})))))))}},c={view:function(e){return m("div",{class:"text-center text-xs font-medium text-green-800"},m("p",{class:"mb-3 text-gray-700 text-sm font-thin"},__("Click on the labels to filter out the templates","email-subscribers")),m("p",{class:"mb-3 pr-2 inline border-r border-gray-300"},m("a",{href:"#",onclick:function(){g.setActiveFilters("type",ig_es_main_js_data.newsletter_campaign_type)},class:(-1<g.activeFilters.type.indexOf(ig_es_main_js_data.newsletter_campaign_type)?"border-green-800 border-solid border ":"")+"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 "},__("Newsletter","email-subscribers")),m("a",{href:"#",onclick:function(){g.setActiveFilters("type",ig_es_main_js_data.post_notification_campaign_type)},class:(-1<g.activeFilters.type.indexOf(ig_es_main_js_data.post_notification_campaign_type)?"border-green-800 border-solid border ":"")+"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 "},__("Post Notification","email-subscribers")),ig_es_js_data.is_pro&&m("a",{href:"#",onclick:function(){g.setActiveFilters("type",ig_es_main_js_data.post_digest_campaign_type)},class:(-1<g.activeFilters.type.indexOf(ig_es_main_js_data.post_digest_campaign_type)?"border-green-800 border-solid border ":"")+"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 "},__("Post Digest","email-subscribers"))),m("p",{class:"inline pl-2 pr-2 border-r border-gray-300"},m("a",{href:"#",onclick:function(){g.setActiveFilters("editor_type",ig_es_main_js_data.classic_editor_slug)},class:(-1<g.activeFilters.editor_type.indexOf(ig_es_main_js_data.classic_editor_slug)?"border-green-800 border-solid border ":"")+"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 "},__("Classic Editor","email-subscribers")),m("a",{href:"#",onclick:function(){g.setActiveFilters("editor_type",ig_es_main_js_data.dnd_editor_slug)},class:(-1<g.activeFilters.editor_type.indexOf(ig_es_main_js_data.dnd_editor_slug)?"border-green-800 border-solid border ":"")+"es-filter-templates border border-green-100 text-green-800 m-1 px-3 py-1 rounded-full cursor-pointer bg-green-50 hover:bg-green-300 "},__("Drag and Drop editor","email-subscribers"))),m("a",{href:"#",class:"text-red-800 m-1 px-3 py-1 cursor-pointer",onclick:function(){g.clearAllActiveFilters()}},__("Clear all filters","email-subscribers")))}},p={previewHTML:"",item:{},campaignType:"",galleryType:"",oncreate:function(){ig_es_load_iframe_preview("#gallery-item-preview-iframe-container",p.previewHTML)},view:function(e){var t=p.item.es_plan,i=p.item.template_version,t=canUpsellESTemplate(t,i),i=g.manageTemplates,r=p.item.gallery_type,a=i&&"local"===r,s=!i&&!t,i=i&&"remote"===r&&!t;return m("div",{id:"campaign-preview-popup"},m("div",{class:"fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full",style:"background-color: rgba(0,0,0,.5);"},m("div",{id:"campaign-preview-main-container",class:"absolute h-auto pt-2 ml-16 mr-4 text-left bg-white rounded shadow-xl z-80 w-1/2 md:max-w-5xl lg:max-w-7xl md:pt-3 lg:pt-2"},m("div",{class:"py-2 px-4"},m("div",{class:"flex border-b border-gray-200 pb-2"},m("h3",{class:"w-full text-2xl text-left"},__("Template Preview","email-subscribers")),m("div",{class:"flex"},m("button",{id:"close-campaign-preview-popup",class:"text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg",onclick:function(){p.previewHTML=""}},m("svg",{class:"h-5 w-5 inline",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor","aria-hidden":"true"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"})))))),m("div",{id:"gallery-item-preview-container"},m("p",{class:"mx-4 mb-2"},__("There could be a slight variation on how your customer will view the email content.","email-subscribers")),m("div",{id:"gallery-item-preview-iframe-container",class:"py-4 list-decimal popup-preview"})),m("div",{class:"flex justify-center"},s&&m("a",{class:"ig-es-primary-button py-1 px-2 mb-5 text-white cursor-pointer",href:"?action=ig_es_import_gallery_item&template-id="+p.item.ID+"&campaign-type="+p.campaignType+"&gallery-type="+p.item.gallery_type+"&_wpnonce="+ig_es_js_data.security},__("Use this template","email-subscribers")),a&&m("a",{class:"ig-es-primary-button py-1 px-2 mb-5 text-white cursor-pointer",href:"?page=es_template&id="+p.item.ID+"&action=edit"},__("Edit this template","email-subscribers")),i&&m("a",{class:"ig-es-primary-button py-1 px-2 mb-5 text-white cursor-pointer",href:"?action=ig_es_import_remote_gallery_template&template-id="+p.item.ID+"&_wpnonce="+ig_es_js_data.security},__("Import this template","email-subscribers")),t&&m("a",{class:"ig-es-primary-button py-1 px-2 mb-5 text-white cursor-pointer capitalize",href:"https://www.icegram.com/email-subscribers-pricing/?utm_source=in_app&utm_medium=remote_gallery_template&utm_campaign=es_upsell",target:"_blank"},__("Upgrade to","email-subscribers")+" "+("starter"===p.item.es_plan?"PRO":"MAX"))))))}},d=p,u={view:function(e){var t="",i="";return g.manageTemplates?(t="?page=es_template",i=__("Template","email-subscribers")):(i=__("Campaign","email-subscribers"),"post_notification"===e.attrs.campaignType?t="?page=es_notifications":"newsletter"===e.attrs.campaignType&&(t="?page=es_newsletters")),t+="&action=new",m("div",{id:"ig-es-campaign-editor-type-popup"},m("div",{class:"fixed top-0 left-0 z-50 flex items-center justify-center w-full h-full",style:"background-color: rgba(0,0,0,.5);"},m("div",{class:"absolute h-auto p-4 ml-16 mr-4 text-left bg-white rounded shadow-xl z-80 md:max-w-5xl md:p-3 lg:p-4"},m("div",{class:"py-2 px-4"},m("div",{class:"flex border-b border-gray-200 pb-2"},m("h3",{class:"text-2xl text-center w-11/12"},__("Create ","email-subscribers")+i),m("button",{id:"close-campaign-editor-type-popup",onclick:function(){g.hideEditorChoicePopup()},class:"text-sm font-medium tracking-wide text-gray-700 select-none no-outline focus:outline-none focus:shadow-outline-red hover:border-red-400 active:shadow-lg"},m("svg",{class:"h-5 w-5 inline",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor","aria-hidden":"true"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"}))))),m("div",{class:"mx-4 my-2 list-decimal"},m("div",{class:"mx-auto flex justify-center pt-2"},m("label",{class:"inline-flex items-center cursor-pointer mr-3 h-22 w-50"},m("div",{class:"px-3 py-1 border border-gray-200 rounded-lg shadow-md es-mailer-logo es-importer-logo h-18 bg-white"},m("a",{href:t+"&editor-type="+ig_es_main_js_data.dnd_editor_slug,class:"campaign-editor-type-choice"},m("div",{class:"border-0 es-logo-wrapper"},m("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-5 w-5",viewBox:"0 0 20 20",fill:"currentColor"},m("path",{"fill-rule":"evenodd",d:"M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 110-2h4a1 1 0 011 1v4a1 1 0 11-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 112 0v1.586l2.293-2.293a1 1 0 011.414 1.414L6.414 15H8a1 1 0 110 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 110-2h1.586l-2.293-2.293a1 1 0 011.414-1.414L15 13.586V12a1 1 0 011-1z","clip-rule":"evenodd"}))),m("p",{class:"mb-2 text-sm inline-block font-medium text-gray-600"},__("Create using new Drag & Drop Editor","email-subscribers"))))),m("label",{class:"inline-flex items-center cursor-pointer mr-3 h-22 w-50"},m("div",{class:"px-3 py-1 border border-gray-200 rounded-lg shadow-md es-mailer-logo es-importer-logo h-18 bg-white"},m("a",{href:t+"&editor-type="+ig_es_main_js_data.classic_editor_slug,class:"campaign-editor-type-choice","data-editor-type":"<?php echo esc_attr( IG_ES_CLASSIC_EDITOR ); ?>"},m("div",{class:"border-0 es-logo-wrapper"},m("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-6 w-6",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"}))),m("p",{class:"mb-2 text-sm inline-block font-medium text-gray-600"},__("Create using Classic Editor","email-subscribers"))))))))))}},_={canShowEditorChoicePopup:!1,activeFilters:[],manageTemplates:!1,oninit:function(e){l.loadItems(),_.manageTemplates="yes"===e.attrs.manageTemplates;var t=!_.manageTemplates,e=e.attrs.campaignType;_.activeFilters.type||(_.activeFilters.type=[]),_.activeFilters.editor_type||(_.activeFilters.editor_type=[ig_es_main_js_data.classic_editor_slug,ig_es_main_js_data.dnd_editor_slug]),_.activeFilters.gallery_type||(_.activeFilters.gallery_type=[ig_es_main_js_data.local_gallery_type,ig_es_main_js_data.remote_gallery_type]),t&&0<=_.activeFilters.type.length&&-1===_.activeFilters.type.indexOf(e)&&(_.activeFilters.type.push(e),e===ig_es_main_js_data.post_notification_campaign_type)&&_.activeFilters.type.push(ig_es_main_js_data.post_digest_campaign_type)},showPreview:function(t,i,e){l.loadTemplatePreviewData(t.ID,e).then(function(e){d.previewHTML=e.data.template_html,d.item=t,d.campaignType=i})},showEditorChoicePopup:function(){_.canShowEditorChoicePopup=!0},hideEditorChoicePopup:function(){_.canShowEditorChoicePopup=!1},deleteGalleryTemplate:function(t){confirm(__("Do you really want to delete this template?","email-subscribers"))&&l.deleteTemplate(t).then(function(e){e.success?l.items=l.items.filter(function(e){return e.ID!==t}):alert(__("An error has occured. Please try again later","email-subscribers"))})},setActiveFilters:function(e,t){_.activeFilters[e]||(_.activeFilters[e]=[]),-1<_.activeFilters[e].indexOf(t)?_.activeFilters[e]=_.activeFilters[e].filter(function(e){return e!==t}):_.activeFilters[e].push(t)},clearAllActiveFilters:function(){_.activeFilters.type=[],_.activeFilters.editor_type=[],_.activeFilters.gallery_type=[]},view:function(i){var r=i.attrs.campaignType,e=l.items;return 0<l.items.length&&(0<Object.keys(_.activeFilters).length||0<Object.keys(_.activeFilters).length)&&(0<_.activeFilters.type.length&&(e=l.items.filter(function(e){return _.activeFilters.type.includes(e.type)})),0<_.activeFilters.editor_type.length&&(e=e.filter(function(e){return _.activeFilters.editor_type.includes(e.editor_type)})),0<_.activeFilters.gallery_type.length)&&(e=e.filter(function(e){return _.activeFilters.gallery_type.includes(e.gallery_type)})),m("section",null,n.showLoader?m(n,null):null,m(c,null),m("section",{class:"overflow-hidden text-gray-700 "},m("div",{class:"container px-5 py-2 mx-auto lg:pt-12 lg:px-24"},m("div",{class:"grid grid-cols-4 gap-4"},m("div",{class:"cursor-pointer",onclick:function(){_.showEditorChoicePopup()}},m("div",{class:"h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden"},m("svg",{alt:"{item.title}",xmlns:"http://www.w3.org/2000/svg",class:"h-40 w-full",fill:"none",viewBox:"0 0 24 24",stroke:"#d2d6dc","stroke-width":"2"},m("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 6v6m0 0v6m0-6h6m-6 0H6"})),m("div",{class:"p-4 bg-white h-28 mt-8"},m("h4",{href:"#",onclick:function(){_.showEditorChoicePopup()},class:"title-font text-lg font-medium text-gray-900 mb-3 sm:truncate cursor-pointer hover:underline mt-6"},__("Create from scratch","email-subscribers"))))),e.map(function(e,t){return ig_es_main_js_data.post_digest_campaign_type!==e.type&&ig_es_main_js_data.post_notification_campaign_type!==e.type||(r=e.type),m(o,{key:t,item:e,campaignType:r,campaignId:i.attrs.campaignId})})))),""!==d.previewHTML?m(d,null):"",_.canShowEditorChoicePopup?m(u,{campaignType:i.attrs.campaignType}):"")}},g=t.a=_},function(e,t,s){"use strict";s.r(t),function(e){s(3);var t=s(0),e=(void 0!==wp.i18n?e.__=wp.i18n.__:e.__=function(e,t){return e},e.canUpsellESTemplate=function(e,t){var i=!1;return"lite"===ig_es_main_js_data.es_plan||"trial"===ig_es_main_js_data.es_plan?i="starter"===e||"pro"===e:"starter"===ig_es_main_js_data.es_plan&&(i="pro"===e),"1.0.1"===t&&("lite"===ig_es_main_js_data.es_plan||"trial"===ig_es_main_js_data.es_plan?i="pro"===e||"max"===e:"starter"===ig_es_main_js_data.es_plan&&(i="max"===e)),i},document.querySelector("#ig-es-campaign-gallery-items-wrapper")),i=location.search.split("campaign-type=")[1],r=location.search.split("campaign-id=")[1],a=location.search.split("manage-templates=")[1];void 0===i&&(i=ig_es_main_js_data.post_notification_campaign_type),void 0===r&&(r=0),void 0===a&&(a="no"),m.mount(e,{view:function(){return m(t.a,{campaignId:r,campaignType:i,manageTemplates:a})}})}.call(this,s(2))},function(e,t){function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var r=function(){return this}();try{r=r||new Function("return this")()}catch(e){"object"===("undefined"==typeof window?"undefined":i(window))&&(r=window)}e.exports=r},function(e,t,i){}]);