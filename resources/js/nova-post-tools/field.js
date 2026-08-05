import tinymce from 'tinymce/tinymce'
import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/models/dom'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/autosave'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/image'
import 'tinymce/plugins/media'
import 'tinymce/plugins/table'
import 'tinymce/plugins/code'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/autoresize'
import FormField from './FormField.vue'

tinymce.baseURL = '/assets/nova-post-tools'
tinymce.suffix = '.min'
window.tinymce = tinymce

Nova.booting((app) => {
    app.component('form-news-hub-post-tools', FormField)
})
