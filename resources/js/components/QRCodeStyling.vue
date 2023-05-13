<template>
    <div>
        <div ref="qrCode" class="rounded-2 d-inline-flex overflow-hidden"></div>
    </div>
</template>
<script>
import { defineComponent } from "vue"
import QRCodeStyling from 'qr-code-styling'

export default defineComponent({
    name: 'IntersectionObserver',
    props: {
        size: {
            type: Number,
            required: true,
        },
        margin: {
            type: Number,
            required: false,
            default: 15,
        },
        value: {
            type: String,
            required: true,
        },
        level: {
            type: String,
            required: false,
            default: "Q"
        },
        type: {
            type: String,
            required: false,
            default: "extra-rounded"
        },
    },
    data() {
        const options = {
            "width": this.size,
            "height": this.size,
            "data": this.value,
            "margin": this.margin,
            "qrOptions": {
                "typeNumber": "0",
                "mode": "Byte",
                "errorCorrectionLevel": this.level
            },
            "imageOptions": {
                "hideBackgroundDots": true,
                "imageSize": 0.4,
                "margin": 0
            },
            "dotsOptions": {
                "type": this.type,
                "color": this.type == 'square'? '#000000': '#22115f'
            },
            "backgroundOptions": {
                "color": "#ffffff"
            },
            "image": null,
            "dotsOptionsHelper": {
                "colorType": {
                    "single": true,
                    "gradient": false
                },
                "gradient": {
                    "linear": true,
                    "radial": false,
                    "color1": "#6a1a4c",
                    "color2": "#6a1a4c",
                    "rotation": "0"
                }
            },
            "cornersSquareOptions": {
                "type": this.type,
                "color": "#000000"
            },
            "cornersSquareOptionsHelper": {
                "colorType": {
                    "single": true,
                    "gradient": false
                },
                "gradient": {
                    "linear": true,
                    "radial": false,
                    "color1": "#000000",
                    "color2": "#000000",
                    "rotation": "0"
                }
            },
            "cornersDotOptions": {
                "type": "",
                "color": "#000000"
            },
            "cornersDotOptionsHelper": {
                "colorType": {
                    "single": true,
                    "gradient": false
                },
                "gradient": {
                    "linear": true,
                    "radial": false,
                    "color1": "#000000",
                    "color2": "#000000",
                    "rotation": "0"
                }
            },
            "backgroundOptionsHelper": {
                "colorType": {
                    "single": true,
                    "gradient": false
                },
                "gradient": {
                    "linear": true,
                    "radial": false,
                    "color1": "#ffffff",
                    "color2": "#ffffff",
                    "rotation": "0"
                }
            }
        }

        return {
            options,
            extension: 'svg',
            qrCode: new QRCodeStyling(options)
        }
    },
    mounted() {
        this.qrCode.append(this.$refs["qrCode"])
    },
})
</script>