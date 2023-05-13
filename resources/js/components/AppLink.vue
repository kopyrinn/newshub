<template>
  <a v-if="isExternalLink" v-bind="$attrs" :href="toLink" target="_blank">
    <slot />
  </a>
  <router-link
    v-else
    v-bind="$props"
    :to="toLink"
    custom
    v-slot="{ isActive, href, navigate }"
  >
    <a
      v-bind="$attrs"
      :href="href"
      @click="navigate"
      :class="isActive ? activeClass : inactiveClass"
    >
      <slot />
    </a>
  </router-link>
</template>

<script>
import { RouterLink } from 'vue-router'

export default {
  name: 'AppLink',
  inheritAttrs: false,

  props: {
    // add @ts-ignore if using TypeScript
    ...RouterLink.props,
    inactiveClass: String,
  },

  computed: {
    toLink() {
        if (!this.to || this.$root.locale == 'ru') return this.to

        if (typeof this.to === 'string') {
            return `/${this.$root.locale}${this.to}`
        } else {
            let link = {...this.to}

            if (!link.params) {
                link.params = {}
            }

            link.params.locale = this.$root.locale

            return link
        }
    },
    isExternalLink() {
      return typeof this.to === 'string' && this.to.startsWith('http')
    },
  },
}
</script>