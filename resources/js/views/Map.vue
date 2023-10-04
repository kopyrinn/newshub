<template>
    <div class="card">
        <div class="card-body p-lg-17">
            <div class="text-gray-800 fw-semibold fs-5">
                <p>{{ $t("The map shows the actual number of people registered on the NewsHUB.kz portal. The use of data is prohibited.") }}</p>

                <p class="medium">
                    {{ $t("Total number of registered journalists") }} - {{ usersCount }}
                </p>
            </div>

            
            <div class="row">
                <div class="col-sm-8">
                    <figure ref="imapc" id="imapc" class="w-100">
                        <object data="/assets/media/kazakhstan.svg" type="image/svg+xml" ref="imap" id="imap" class="w-100">
                            <p>{{ $t("Sorry, you are using an outdated browser version that does not support the interactive map.") }}</p>
                        </object>
                    </figure>
                </div>

                <div class="col-sm-4">
                    <table id="areas" class="table align-middle">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ $t("Regions") }}</th>
                                <th id="journalists">{{ $t("Journalists") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="region in regions" :id="'pathKZ-' + region.code">
                                <td class="">
                                    <div class="form-check ms-2">
                                        <input type="checkbox" :id="'c' + region.code" class="form-check-input">
                                    </div>
                                </td>
                                <td><label :for="'c' + region.code">{{ region['region_name_' + $root.locale] }}</label></td>
                                <td><label :for="'c' + region.code">{{ region.journalists_count }}</label></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <SchemaOrgWebPage :name="$root.meta.title" />
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import Sidebar from "@/components/Sidebar.vue"
import $ from "jquery"

export default defineComponent({
    name: "Map",
    components: {
        Sidebar
    },
    data() {
        return {
            loading: true,
            regions: [],
            usersCount: 0,
        }
    },
    head() {
        return {
            title: this.$root.meta.title
        }
    },
    created() {
        if (import.meta.env.SSR) return

        $.fn.myAddClass = function (classTitle) {
            return this.each(function () {
                var oldClass = $(this).attr("class");
                oldClass = oldClass ? oldClass : '';
                $(this).attr("class", (oldClass + " " + classTitle).trim());
            });
        }

        $.fn.myRemoveClass = function (classTitle) {
            return this.each(function () {
                var oldClassString = ' ' + $(this).attr("class") + ' ';
                var newClassString = oldClassString.replace(new RegExp(' ' + classTitle + ' ', 'g'), ' ').trim()
                if (!newClassString)
                    $(this).removeAttr("class");
                else
                    $(this).attr("class", newClassString);
            });
        }

        var Tooltip = function (element, options) {
            this.init('tooltip', element, options)
        }

        Tooltip.prototype = {

            constructor: Tooltip

        , init: function (type, element, options) {
            var eventIn
                , eventOut

            this.type = type
            this.$element = $(element)
            this.options = this.getOptions(options)
            this.enabled = true

            if (this.options.trigger == 'click') {
                this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
            } else if (this.options.trigger != 'manual') {
                eventIn = this.options.trigger == 'hover' ? 'mouseenter' : 'focus'
                eventOut = this.options.trigger == 'hover' ? 'mouseleave' : 'blur'
                this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
                this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
            }

            this.options.selector ?
                (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
                this.fixTitle()
            }

        , getOptions: function (options) {
            options = $.extend({}, $.fn[this.type].defaults, options, this.$element.data())

            if (options.delay && typeof options.delay == 'number') {
                options.delay = {
                show: options.delay
                , hide: options.delay
                }
            }

            return options
            }

        , enter: function (e) {
            var self = $(e.currentTarget)[this.type](this._options).data(this.type)

            if (!self.options.delay || !self.options.delay.show) return self.show()

            clearTimeout(this.timeout)
            self.hoverState = 'in'
            this.timeout = setTimeout(function() {
                if (self.hoverState == 'in') self.show()
            }, self.options.delay.show)
            }

        , leave: function (e) {
            var self = $(e.currentTarget)[this.type](this._options).data(this.type)

            if (this.timeout) clearTimeout(this.timeout)
            if (!self.options.delay || !self.options.delay.hide) return self.hide()

            self.hoverState = 'out'
            this.timeout = setTimeout(function() {
                if (self.hoverState == 'out') self.hide()
            }, self.options.delay.hide)
            }

        , show: function () {
            var $tip
                , inside
                , pos
                , actualWidth
                , actualHeight
                , placement
                , tp

            if (this.hasContent() && this.enabled) {
                $tip = this.tip()
                this.setContent()

                if (this.options.animation) {
                $tip.addClass('fade')
                }

                placement = typeof this.options.placement == 'function' ?
                this.options.placement.call(this, $tip[0], this.$element[0]) :
                this.options.placement

                inside = /in/.test(placement)

                $tip
                .detach()
                .css({ top: 0, left: 0, display: 'block' })
                .insertAfter(this.$element)

                pos = this.getPosition(inside)

                actualWidth = $tip[0].offsetWidth
                actualHeight = $tip[0].offsetHeight

                switch (inside ? placement.split(' ')[1] : placement) {
                case 'bottom':
                    tp = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}
                    break
                case 'top':
                    tp = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}
                    break
                case 'left':
                    tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}
                    break
                case 'right':
                    tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}
                    break
                }

                $tip
                .offset(tp)
                .addClass(placement)
                .addClass('show')
            }
            }

        , setContent: function () {
            var $tip = this.tip()
                , title = this.getTitle()

            $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
            $tip.removeClass('fade in top bottom left right')
            }

        , hide: function () {
            var that = this
                , $tip = this.tip()

            $tip.removeClass('in')

            function removeWithAnimation() {
                var timeout = setTimeout(function () {
                $tip.off($.support.transition.end).detach()
                }, 500)

                $tip.one($.support.transition.end, function () {
                clearTimeout(timeout)
                $tip.detach()
                })
            }

            $.support.transition && this.$tip.hasClass('fade') ?
                removeWithAnimation() :
                $tip.detach()

            return this
            }

        , fixTitle: function () {
            var $e = this.$element
            if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
                $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
            }
            }

        , hasContent: function () {
            return this.getTitle()
            }

        , getPosition: function (inside) {
            return $.extend({}, (inside ? {top: 0, left: 0} : this.$element.offset()), {
                width: this.$element[0].offsetWidth
            , height: this.$element[0].offsetHeight
            })
            }

        , getTitle: function () {
            var title
                , $e = this.$element
                , o = this.options

            title = $e.attr('data-original-title')
                || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

            return title
            }

        , tip: function () {
            return this.$tip = this.$tip || $(this.options.template)
            }

        , validate: function () {
            if (!this.$element[0].parentNode) {
                this.hide()
                this.$element = null
                this.options = null
            }
            }

        , enable: function () {
            this.enabled = true
            }

        , disable: function () {
            this.enabled = false
            }

        , toggleEnabled: function () {
            this.enabled = !this.enabled
            }

        , toggle: function (e) {
            var self = $(e.currentTarget)[this.type](this._options).data(this.type)
            self[self.tip().hasClass('in') ? 'hide' : 'show']()
            }

        , destroy: function () {
            this.hide().$element.off('.' + this.type).removeData(this.type)
            }

        }


        /* TOOLTIP PLUGIN DEFINITION
        * ========================= */

        var old = $.fn.tooltip

        $.fn.tooltip = function ( option ) {
            return this.each(function () {
            var $this = $(this)
                , data = $this.data('tooltip')
                , options = typeof option == 'object' && option
            if (!data) $this.data('tooltip', (data = new Tooltip(this, options)))
            if (typeof option == 'string') data[option]()
            })
        }

        $.fn.tooltip.Constructor = Tooltip

        $.fn.tooltip.defaults = {
            animation: true
        , placement: 'top'
        , selector: false
        , template: '<div class="tooltip bs-tooltip-auto"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
        , trigger: 'hover'
        , title: ''
        , delay: 0
        , html: false
        }

        this.fetchData()
    },
    mounted() {
        var svgobject = this.$refs.imap

        const timeout = (ms) => {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        svgobject.onload = async () => {
            await timeout(500);

            if (!'contentDocument' in svgobject) return

            const svgdom = svgobject.contentDocument

            if (!svgdom.rootElement) return

            var viewBox = svgdom.rootElement.getAttribute("viewBox").split(" ");
            var aspectRatio = viewBox[2] / viewBox[3];
            svgobject.height = parseInt(svgobject.offsetWidth / aspectRatio);

            $("#areas input[type=checkbox]").change(function () {
                var row = $(this).parent().parent().parent();
                var id = row.attr("id");
                if (id == 'pathKZ-ALM1') {
                    id = 'pathKZ-ALM';
                }
                if (id == 'pathKZ-AKM1') {
                    id = 'pathKZ-AKM';
                }
                // console.log(id);
                if (this.checked) {
                    row.addClass("selected");
                    $("#" + id, svgdom).myAddClass("selected");
                } else {
                    row.removeClass("selected");
                    $("#" + id, svgdom).myRemoveClass("selected");
                }
            });

            // Скрытие названий по чекбоксу
            $("#titleswitch").change(function () {
                var elements = $(svgdom.getElementsByClassName("areatitle"))
                    .add($(svgdom.getElementsByClassName("citytitle")))
                    .add($(svgdom.getElementsByClassName("titlebox")))
                    .add($(svgdom.getElementsByClassName("titleline")));
                if (this.checked) {
                    elements.myAddClass("hidden");
                } else {
                    elements.myRemoveClass("hidden");
                }
            });

            // Подсвечиваем регион на карте при наведении мыши на соотв. строку таблицы.
            $("#areas tr").hover(
                function () {
                    var id = $(this).attr("id");
                    if (id == 'pathKZ-ALM1') {
                        id = 'pathKZ-ALM';
                    }
                    if (id == 'pathKZ-AKM1') {
                        id = 'pathKZ-AKM';
                    }
                    $("#" + id, svgdom).myAddClass("bg-light-primary");
                },
                function () {
                    var id = $(this).attr("id");
                    if (id == 'pathKZ-ALM1') {
                        id = 'pathKZ-ALM';
                    }
                    if (id == 'pathKZ-AKM1') {
                        id = 'pathKZ-AKM';
                    }
                    $("#" + id, svgdom).myRemoveClass("bg-light-primary");
                }
            );
            // Подсвечиваем строку в таблице при наведении мыши на соотв. регион на карте
            $(svgdom.getElementsByClassName("area")).hover(
                function () {
                    var id = $(this).attr("id");
                    $("#areas #" + id).addClass("bg-light-primary");
                },
                function () {
                    var id = $(this).attr("id");
                    $("#areas #" + id).removeClass("bg-light-primary");
                }
            );

            // Меняем значения на карте значениями из таблицы
            var descnum = $("#journalists").prevAll().length + 1;
            $("#areas tbody tr").each(function () {
                var id = $(this).attr("id").substring(4);
                var value = $(this).children(":nth-child(" + descnum + ")").text();
                if (id == 'KZ-ALM1') {
                    value = parseInt($("#textKZ-ALM", svgdom).text()) + parseInt(value);
                    id = 'KZ-ALM';
                } else if (id == 'KZ-AKM1') {
                    value = parseInt($("#textKZ-AKM", svgdom).text()) + parseInt(value);
                    id = 'KZ-AKM';
                }
                $("#text" + id, svgdom).text(value);
            });
            $("#resetswitch").change(function () {
                $("#areas tbody tr").each(function () {
                    var id = $(this).attr("id").substring(4);
                    $("#text" + id, svgdom).text("");
                });
            });

            // Всплывающие подсказки
            // $(svgdom.getElementsByClassName("area")).tooltip({
            //     track: true,
            //     delay: 0,
            //     showURL: false,
            //     fade: 250,
            //     bodyHandler: function () {
            //         var id = $(this).attr("id");
            //         var area = $("#areas #" + id + " td:nth-child(2)").text();
            //         var result = $("<p>").append($("<strong>").text(area));
            //         $("#areas #" + id + " td:nth-child(2)").nextAll().each(function () {
            //             var pos = $(this).prevAll().length + 1;
            //             var title = $("#areas thead th:nth-child(" + pos + ")").text();
            //             var value = $(this).text();
            //             result.append($("<p>").text(title + ": " + value));
            //         });
            //         return result;
            //     }
            // });
        }
    },
    methods: {
        fetchData() {
            this.loading = true

            this.$api('map').then(({data}) => {
                this.loading = false

                if (!data.ok) return

                this.regions = data.regions
                this.usersCount = data.usersCount
            })
        },
    },
});
</script>
<style>
#text {
    float: right;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: 0 1em;
}

#imapc {
    display: block;
    float: right;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    margin: 0;
    padding: 1em;
    border: 0;
}

#imap {
    margin: 0;
    padding: 0;
}

@media (max-width: 576px) {
    #imap {
        width: 100%;
        margin: 0;
        padding: 0;
    }

}

#areas {
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}

figcaption {
    text-align: center;
}

.tooltip {
    position: absolute;
    z-index: 3000;
    border: 1px solid #111;
    background-color: #eee;
    padding: 5px;
    opacity: 0.85;
}

.tooltip p {
    margin: 0;
}
</style>