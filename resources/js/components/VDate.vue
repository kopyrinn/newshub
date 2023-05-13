<template>
    <!-- <timeago v-if="type === 'diff' && !dateOnly" :datetime="datetime" v-once/> -->
    <span v-if="type === 'diff' && !dateOnly" v-once>{{ date.fromNow() }}</span>
    <span v-else-if="type === 'td'" v-once>{{ $t('Today') }}<span v-if="!dateOnly">, {{ date.format('HH:mm') }}</span></span>
    <span v-else-if="type === 'ytd'" v-once>{{ $t('Yesterday') }}<span v-if="!dateOnly">, {{ date.format('HH:mm') }}</span></span>
    <span v-else-if="type === 'tm'" v-once>{{ $t('Tomorrow') }}<span v-if="!dateOnly">, {{ date.format('HH:mm') }}</span></span>
    <span v-else-if="type === 'short'" v-once>{{ dateOnly? date.format('DD MMMM'): date.format('DD MMM, HH:mm') }}</span>
    <span v-else v-once>{{ dateOnly? date.format('DD MMM YYYY'): date.format('DD MMM YYYY, HH:mm') }}</span>
</template>
<script>
import { defineComponent } from "vue"
// import timeago from 'vue-timeago3'
// import fnsLocaleRu from 'date-fns/locale/ru'
// import fnsLocaleKk from 'date-fns/locale/kk'

export default defineComponent({
    name: 'VDate',
    components: {
        // timeago,
    },
    props: {
        datetime: {
            type: Date,
            required: true,
        },
        dateOnly: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        let date = this.$dayjs(this.datetime)

        return {
            type: 'diff',
            date: date,
            locale: false,
            isToday: date.isToday(),
            isYesterday: date.isYesterday(),
            isTomorrow: date.isTomorrow(),
            hAgo: this.$dayjs().diff(this.datetime, 'hours'),
        }
    },
    created() {
        // if (this.$root.locale == 'kk') {
        //     this.locale = fnsLocaleKk
        // } else if (this.$root.locale == 'ru') {
        //     this.locale = fnsLocaleRu
        // } else {
        //     this.locale = null
        // }

        if (this.hAgo >= 0 && this.hAgo < 5) {
            this.type = 'diff'
        } else if (this.isToday) {
            this.type = 'td'
        } else if (this.isYesterday) {
            this.type = 'ytd'
        } else if (this.isTomorrow) {
            this.type = 'tm'
        } else if (this.date.year() == this.$dayjs().year()) {
            this.type = 'short'
        } else {
            this.type = 'full'
        }
    }
})
</script>