<template>
  <div :class="class">
    <div
      class="image-input h-100 w-100 border border-1 border-gray-300"
      :class="{ 'image-input-empty': !image, 'image-input-circle': circle }"
      data-kt-image-input="true"
    >
      <div
        :id="'i' + uid"
        class="image-input-wrapper bgi-position-center h-100 w-100"
        v-bind:style="{ backgroundImage: (image? 'url(' + uploadedImage + ')': ''), zIndex: 1 }"
      >
        <label v-if="!image" :for="'f' + uid" class="btn btn-light-primary btn-sm position-absolute top-50 start-50 translate-middle">{{ $t('Browse') }}</label>
      </div>

      <label
        class="
          btn btn-icon btn-circle btn-active-color-primary
          w-25px
          h-25px
          bg-light
          shadow
        "
        data-kt-image-input-action="change"
        data-bs-toggle="tooltip"
        title="Change image"
        style="z-index:1"
      >
        <i class="bi bi-pencil fs-7"></i>

        <input :id="'f' + uid" type="file" @input="pickFile" accept=".png, .jpg, .jpeg, .webp" />
      </label>

      <span
        class="
          btn btn-icon btn-circle btn-active-color-primary
          w-25px
          h-25px
          bg-light
          shadow
        "
        data-kt-image-input-action="remove"
        data-bs-toggle="tooltip"
        @click="removeImage"
        title="Remove image"
        style="z-index:1"
      >
        <i class="bi bi-x fs-2"></i>
      </span>
    </div>
  </div>
  <span class="form-text">{{ $t('Extensions') }}: .png, .jpg, .jpeg, .webp</span>
</template>

<script>
import { defineComponent } from "vue"
import showErrors from "@/helpers/notify"
import { ElLoading } from 'element-plus'

export default defineComponent({
    name: "VImageUpload",
    emits: ["uploaded"],
    props: {
        circle: {
            type: Boolean,
            default: false,
        },
        image: {
            type: String,
            default: '',
        },
        format: {
            type: String,
            default: 'original',
        },
        eventFallback: {
            type: Boolean,
            default: false,
        },
        class: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            uid: Date.now().toString(36) + Math.random().toString(36).substr(2),
        }
    },
    computed: {
      uploadedImage() {
        return this.eventFallback? this.$eventImage(this.image): this.$storage(this.image)
      }
    },
    methods: {
        async pickFile(e) {
            if (!e.target.files.length) return

            let loadingInstance = ElLoading.service({
                target: `#i${this.uid}`,
            })

            const response = await this.$upload(e.target.files[0], this.format)

            e.target.value = ''

            if (response.ok) {
                this.$emit('uploaded', response.images);
            } else {
                showErrors(response);
            }

            loadingInstance.close()
        },
        removeImage() {
            this.$emit('uploaded', {});
        }
    },
});
</script>
