/**
 * @author zjh
 */
(function ($) {

    var ErrorMsgs = {
        formatInvalidInputErrorMsg: function (input) {
            return "你输入的:" + input + "不是合法的值";
        },
        callingContextNotSliderInstance: "滑动条并未初始化"
    };

    /**
     * 滑动条组件
     * @class Slider
     * @constructor
     */
    var Slider = function (element, options) {
        var el = this.element = $(element).hide();
        var origWidth = $(element)[0].style.width;

        var updateSlider = false;
        var parent = this.element.parent();


        if (parent.hasClass('slider') === true) {
            updateSlider = true;
            this.picker = parent;
        } else {
            this.picker = $('<div class="slider">' +
                '<div class="slider-track">' +
                '<div class="slider-selection"></div>' +
                '<div class="slider-handle"></div>' +
                '<div class="slider-handle"></div>' +
                '</div>' +
                '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>' +
                '</div>')
                .insertBefore(this.element)
                .append(this.element);
        }

        this.id = this.element.data('slider-id') || options.id;
        if (this.id) {
            this.picker[0].id = this.id;
        }

        if (typeof Modernizr !== 'undefined' && Modernizr.touch) {
            this.touchCapable = true;
        }

        var tooltip = this.element.data('slider-tooltip') || options.tooltip;

        this.tooltip = this.picker.find('.tooltip');
        this.tooltipInner = this.tooltip.find('div.tooltip-inner');

        this.orientation = this.element.data('slider-orientation') || options.orientation;
        switch (this.orientation) {
            case 'vertical':
                this.picker.addClass('slider-vertical');
                this.stylePos = 'top';
                this.mousePos = 'pageY';
                this.sizePos = 'offsetHeight';
                this.tooltip.addClass('right')[0].style.left = '100%';
                break;
            default:
                this.picker
                    .addClass('slider-horizontal')
                    .css('width', origWidth);
                this.orientation = 'horizontal';
                this.stylePos = 'left';
                this.mousePos = 'pageX';
                this.sizePos = 'offsetWidth';
                this.tooltip.addClass('top')[0].style.top = -this.tooltip.outerHeight() - 14 + 'px';
                break;
        }

        var self = this;
        $.each(['min', 'max', 'step', 'value'], function (i, attr) {
            if (typeof el.data('slider-' + attr) !== 'undefined') {
                self[attr] = el.data('slider-' + attr);
            } else if (typeof options[attr] !== 'undefined') {
                self[attr] = options[attr];
            } else if (typeof el.prop(attr) !== 'undefined') {
                self[attr] = el.prop(attr);
            } else {
                self[attr] = 0; // to prevent empty string issues in calculations in IE
            }
        });

        if (this.value instanceof Array) {
            this.range = true;
        }

        this.selection = this.element.data('slider-selection') || options.selection;
        this.selectionEl = this.picker.find('.slider-selection');
        if (this.selection === 'none') {
            this.selectionEl.addClass('hide');
        }

        this.selectionElStyle = this.selectionEl[0].style;

        this.handle1 = this.picker.find('.slider-handle:first');
        this.handle1Stype = this.handle1[0].style;

        this.handle2 = this.picker.find('.slider-handle:last');
        this.handle2Stype = this.handle2[0].style;

        var handle = this.element.data('slider-handle') || options.handle;
        switch (handle) {
            case 'round':
                this.handle1.addClass('round');
                this.handle2.addClass('round');
                break;
            case 'triangle':
                this.handle1.addClass('triangle');
                this.handle2.addClass('triangle');
                break;
        }

        if (this.range) {
            this.value[0] = Math.max(this.min, Math.min(this.max, this.value[0]));
            this.value[1] = Math.max(this.min, Math.min(this.max, this.value[1]));
        } else {
            this.value = [ Math.max(this.min, Math.min(this.max, this.value))];
            this.handle2.addClass('hide');
            if (this.selection === 'after') {
                this.value[1] = this.max;
            } else {
                this.value[1] = this.min;
            }
        }
        this.diff = this.max - this.min;
        this.percentage = [
            (this.value[0] - this.min) * 100 / this.diff,
            (this.value[1] - this.min) * 100 / this.diff,
            this.step * 100 / this.diff
        ];

        this.offset = this.picker.offset();
        this.size = this.picker[0][this.sizePos];

        this.formater = options.formater;

        this.reversed = this.element.data('slider-reversed') || options.reversed;

        this.layout();

        this.handle1.on({
            keydown: $.proxy(this.keydown, this, 0),
            mousedown: $.proxy(this.triggerFocusOnHandle, this, 0)
        });

        this.handle2.on({
            keydown: $.proxy(this.keydown, this, 1),
            mousedown: $.proxy(this.triggerFocusOnHandle, this, 1)
        });

        if (this.touchCapable) {
            // Touch: Bind touch events:
            this.picker.on({
                touchstart: $.proxy(this.mousedown, this)
            });
        } else {
            this.picker.on({
                mousedown: $.proxy(this.mousedown, this)
            });
        }

        if (tooltip === 'hide') {
            this.tooltip.addClass('hide');
        } else if (tooltip === 'always') {
            this.showTooltip();
            this.alwaysShowTooltip = true;
        } else {
            this.picker.on({
                mouseenter: $.proxy(this.showTooltip, this),
                mouseleave: $.proxy(this.hideTooltip, this)
            });
            this.handle1.on({
                focus: $.proxy(this.showTooltip, this),
                blur: $.proxy(this.hideTooltip, this)
            });
            this.handle2.on({
                focus: $.proxy(this.showTooltip, this),
                blur: $.proxy(this.hideTooltip, this)
            });
        }

        this.enabled = options.enabled &&
            (this.element.data('slider-enabled') === undefined || this.element.data('slider-enabled') === true);
        if (this.enabled) {
            this.enable();
        } else {
            this.disable();
        }
    };

    Slider.prototype = {
        constructor: Slider,

        over: false,
        inDrag: false,

        /**
         * 显示提示框
         * @method showToolTip
         */
        showTooltip: function () {
            this.tooltip.addClass('in');
            this.over = true;
        },

        /**
         * 隐藏提示框
         * @method showToolTip
         */
        hideTooltip: function () {
            if (this.inDrag === false && this.alwaysShowTooltip !== true) {
                this.tooltip.removeClass('in');
            }
            this.over = false;
        },

        layout: function () {
            var positionPercentages;

            if (this.reversed) {
                positionPercentages = [ 100 - this.percentage[0], this.percentage[1] ];
            } else {
                positionPercentages = [ this.percentage[0], this.percentage[1] ];
            }

            this.handle1Stype[this.stylePos] = positionPercentages[0] + '%';
            this.handle2Stype[this.stylePos] = positionPercentages[1] + '%';

            if (this.orientation === 'vertical') {
                this.selectionElStyle.top = Math.min(positionPercentages[0], positionPercentages[1]) + '%';
                this.selectionElStyle.height = Math.abs(positionPercentages[0] - positionPercentages[1]) + '%';
            } else {
                this.selectionElStyle.left = Math.min(positionPercentages[0], positionPercentages[1]) + '%';
                this.selectionElStyle.width = Math.abs(positionPercentages[0] - positionPercentages[1]) + '%';
            }

            if (this.range) {
                this.tooltipInner.text(
                    this.formater(this.value[0]) + ' : ' + this.formater(this.value[1])
                );
                this.tooltip[0].style[this.stylePos] = this.size * (positionPercentages[0] + (positionPercentages[1] - positionPercentages[0]) / 2) / 100 - (this.orientation === 'vertical' ? this.tooltip.outerHeight() / 2 : this.tooltip.outerWidth() / 2) + 'px';
            } else {
                this.tooltipInner.text(
                    this.formater(this.value[0])
                );
                this.tooltip[0].style[this.stylePos] = this.size * positionPercentages[0] / 100 - (this.orientation === 'vertical' ? this.tooltip.outerHeight() / 2 : this.tooltip.outerWidth() / 2) + 'px';
            }
        },


        mousedown: function (ev) {
            if (!this.isEnabled()) {
                return false;
            }
            // Touch: Get the original event:
            if (this.touchCapable && ev.type === 'touchstart') {
                ev = ev.originalEvent;
            }

            this.triggerFocusOnHandle();

            this.offset = this.picker.offset();
            this.size = this.picker[0][this.sizePos];

            var percentage = this.getPercentage(ev);

            if (this.range) {
                var diff1 = Math.abs(this.percentage[0] - percentage);
                var diff2 = Math.abs(this.percentage[1] - percentage);
                this.dragged = (diff1 < diff2) ? 0 : 1;
            } else {
                this.dragged = 0;
            }

            this.percentage[this.dragged] = this.reversed ? 100 - percentage : percentage;
            this.layout();

            if (this.touchCapable) {
                // Touch: Bind touch events:
                $(document).on({
                    touchmove: $.proxy(this.mousemove, this),
                    touchend: $.proxy(this.mouseup, this)
                });
            } else {
                $(document).on({
                    mousemove: $.proxy(this.mousemove, this),
                    mouseup: $.proxy(this.mouseup, this)
                });
            }

            this.inDrag = true;
            var val = this.calculateValue();
            this.setValue(val);
            this.element.trigger({
                type: 'slideStart',
                value: val
            }).trigger({
                type: 'slide',
                value: val
            });
            return false;
        },

        triggerFocusOnHandle: function (handleIdx) {
            if (handleIdx === 0) {
                this.handle1.focus();
            }
            if (handleIdx === 1) {
                this.handle2.focus();
            }
            return false;
        },

        keydown: function (handleIdx, ev) {
            if (!this.isEnabled()) {
                return false;
            }

            var dir;
            switch (ev.which) {
                case 37: // left
                case 40: // down
                    dir = -1;
                    break;
                case 39: // right
                case 38: // up
                    dir = 1;
                    break;
            }
            if (!dir) {
                return;
            }

            var oneStepValuePercentageChange = dir * this.percentage[2];
            var percentage = this.percentage[handleIdx] + oneStepValuePercentageChange;

            if (percentage > 100) {
                percentage = 100;
            } else if (percentage < 0) {
                percentage = 0;
            }

            this.dragged = handleIdx;
            this.adjustPercentageForRangeSliders(percentage);
            this.percentage[this.dragged] = percentage;
            this.layout();

            var val = this.calculateValue();
            this.setValue(val);
            this.element
                .trigger({
                    type: 'slide',
                    value: val
                })
                .trigger({
                    type: 'slideStop',
                    value: val
                })
                .data('value', val)
                .prop('value', val);
            return false;
        },

        mousemove: function (ev) {
            if (!this.isEnabled()) {
                return false;
            }
            // Touch: Get the original event:
            if (this.touchCapable && ev.type === 'touchmove') {
                ev = ev.originalEvent;
            }

            var percentage = this.getPercentage(ev);
            this.adjustPercentageForRangeSliders(percentage);
            this.percentage[this.dragged] = this.reversed ? 100 - percentage : percentage;
            this.layout();

            var val = this.calculateValue();
            this.setValue(val);
            this.element
                .trigger({
                    type: 'slide',
                    value: val
                })
                .data('value', val)
                .prop('value', val);
            return false;
        },

        adjustPercentageForRangeSliders: function (percentage) {
            if (this.range) {
                if (this.dragged === 0 && this.percentage[1] < percentage) {
                    this.percentage[0] = this.percentage[1];
                    this.dragged = 1;
                } else if (this.dragged === 1 && this.percentage[0] > percentage) {
                    this.percentage[1] = this.percentage[0];
                    this.dragged = 0;
                }
            }
        },

        mouseup: function () {
            if (!this.isEnabled()) {
                return false;
            }
            if (this.touchCapable) {
                // Touch: Bind touch events:
                $(document).off({
                    touchmove: this.mousemove,
                    touchend: this.mouseup
                });
            } else {
                $(document).off({
                    mousemove: this.mousemove,
                    mouseup: this.mouseup
                });
            }

            this.inDrag = false;
            if (this.over === false) {
                this.hideTooltip();
            }
            var val = this.calculateValue();
            this.layout();
            this.element
                .data('value', val)
                .prop('value', val)
                .trigger({
                    type: 'slideStop',
                    value: val
                });
            return false;
        },

        calculateValue: function () {
            var val;
            if (this.range) {
                val = [this.min, this.max];
                if (this.percentage[0] !== 0) {
                    val[0] = (Math.max(this.min, this.min + Math.round((this.diff * this.percentage[0] / 100) / this.step) * this.step));
                }
                if (this.percentage[1] !== 100) {
                    val[1] = (Math.min(this.max, this.min + Math.round((this.diff * this.percentage[1] / 100) / this.step) * this.step));
                }
                this.value = val;
            } else {
                val = (this.min + Math.round((this.diff * this.percentage[0] / 100) / this.step) * this.step);
                if (val < this.min) {
                    val = this.min;
                }
                else if (val > this.max) {
                    val = this.max;
                }
                val = parseFloat(val);
                this.value = [val, this.value[1]];
            }
            return val;
        },

        getPercentage: function (ev) {
            if (this.touchCapable) {
                ev = ev.touches[0];
            }
            var percentage = (ev[this.mousePos] - this.offset[this.stylePos]) * 100 / this.size;
            percentage = Math.round(percentage / this.percentage[2]) * this.percentage[2];
            return Math.max(0, Math.min(100, percentage));
        },

        /**
         * 获取滑动条当前的值
         * @method getValue
         * @returns {Integer}
         */
        getValue: function () {
            if (this.range) {
                return this.value;
            }
            return this.value[0];
        },

        /**
         * 设置滑动条的值
         * @method setValue
         * @param val
         */
        setValue: function (val) {
            this.value = this.validateInputValue(val);

            if (this.range) {
                this.value[0] = Math.max(this.min, Math.min(this.max, this.value[0]));
                this.value[1] = Math.max(this.min, Math.min(this.max, this.value[1]));
            } else {
                this.value = [ Math.max(this.min, Math.min(this.max, this.value))];
                this.handle2.addClass('hide');
                if (this.selection === 'after') {
                    this.value[1] = this.max;
                } else {
                    this.value[1] = this.min;
                }
            }
            this.diff = this.max - this.min;
            this.percentage = [
                (this.value[0] - this.min) * 100 / this.diff,
                (this.value[1] - this.min) * 100 / this.diff,
                this.step * 100 / this.diff
            ];
            this.layout();

            this.element
                .trigger({
                    'type': 'slide',
                    'value': this.value
                })
                .data('value', this.value)
                .prop('value', this.value);
        },

        validateInputValue: function (val) {
            if (typeof val === 'number') {
                return val;
            } else if (val instanceof Array) {
                $.each(val, function (i, input) {
                    if (typeof input !== 'number') {
                        throw new Error(ErrorMsgs.formatInvalidInputErrorMsg(input));
                    }
                });
                return val;
            } else {
                throw new Error(ErrorMsgs.formatInvalidInputErrorMsg(val));
            }
        },

        /**
         * 移除滑动条
         * @method destroy
         */
        destroy: function () {
            this.handle1.off();
            this.handle2.off();
            this.element.off().show().insertBefore(this.picker);
            this.picker.off().remove();
            $(this.element).removeData('koala.slider');
        },

        /**
         * 使滑动条不可用
         * @method disable
         */
        disable: function () {
            this.enabled = false;
            this.handle1.removeAttr("tabindex");
            this.handle2.removeAttr("tabindex");
            this.picker.addClass('slider-disabled');
            this.element.trigger('slideDisabled');
        },

        /**
         * 使滑动条可用
         * @method enable
         */
        enable: function () {
            this.enabled = true;
            this.handle1.attr("tabindex", 0);
            this.handle2.attr("tabindex", 0);
            this.picker.removeClass('slider-disabled');
            this.element.trigger('slideEnabled');
        },

        /**
         * 切换滑动条的可用/不可用
         * @method toggle
         */
        toggle: function () {
            if (this.enabled) {
                this.disable();
            } else {
                this.enable();
            }
        },

        /**
         * 判断滑动条是否可用
         * @method isEnabled
         * @returns {Boolean}
         */
        isEnabled: function () {
            return this.enabled;
        }
    };

    var publicMethods = {
        getValue: Slider.prototype.getValue,
        setValue: Slider.prototype.setValue,
        destroy: Slider.prototype.destroy,
        disable: Slider.prototype.disable,
        enable: Slider.prototype.enable,
        toggle: Slider.prototype.toggle,
        isEnabled: Slider.prototype.isEnabled
    };

    $.fn.slider = function (option) {
        if (typeof option === 'string') {
            var args = Array.prototype.slice.call(arguments, 1);
            return invokePublicMethod.call(this, option, args);
        } else {
            return createNewSliderInstance.call(this, option);
        }
    };

    function invokePublicMethod(methodName, args) {
        if (publicMethods[methodName]) {
            var sliderObject = retrieveSliderObjectFromElement(this);
            var result = publicMethods[methodName].apply(sliderObject, args);

            if (typeof result === "undefined") {
                return $(this);
            } else {
                return result;
            }
        } else {
            throw new Error("method '" + methodName + "()' does not exist for slider.");
        }
    }

    function retrieveSliderObjectFromElement(element) {
        var sliderObject = $(element).data('koala.slider');
        if (sliderObject && sliderObject instanceof Slider) {
            return sliderObject;
        } else {
            throw new Error(ErrorMsgs.callingContextNotSliderInstance);
        }
    }

    function createNewSliderInstance(opts) {
        var $this = $(this);
        $this.each(function () {
            var $this = $(this),
                data = $this.data('koala.slider'),
                options = typeof opts === 'object' && opts;
            if (!data) {
                $this.data('koala.slider', (data = new Slider(this, $.extend({}, $.fn.slider.defaults, options))));
            }
        });
        return $this;
    }

    $.fn.slider.defaults = {
        /**
         * 最小值
         * @property min
         * @type Integer
         * @default 0
         */
        min: 0,
        /**
         * 最大值
         * @property max
         * @type Integer
         * @default 100
         */
        max: 100,
        /**
         * 滑动一次改变的值
         * @property step
         * @type Integer
         * @default 1
         */
        step: 1,
        /**
         * 滑动方向
         * @property orientation
         * @type String
         * @default 默认'horizontal',可选 'vertical'代表垂直方向
         */
        orientation: 'horizontal',
        /**
         * 当前值
         * @property value
         * @type Integer
         * @default 50
         */
        value: 50,
        /**
         * 滑动条颜色的位置，选择在滑动点的前面还是后面
         * @property selection
         * @type String
         * @default 'before':前面，'after':后面
         */
        selection: 'before',
        /**
         * 提示框的状态
         * @property tooltip
         * @type String
         * @default
         *    'show':滑动时显示
         *    'hide':隐藏
         *    'always':一直显示
         */
        tooltip: 'show',
        /**
         * 滑动条的形状
         * @property handle
         * @type String
         * @default
         *    'round':圆形
         *    'triangle':三角形
         */
        handle: 'round',
        /**
         * 是否反转过来，即左边是最大，右边是最小
         * @property reversed
         * @type Boolean
         * @default false
         */
        reversed: false,
        /**
         * 是否可用
         * @property enabled
         * @type Boolean
         * @default true
         */
        enabled: true,
        /**
         * 提示框的描述
         * @property formater
         * @type function(value)
         * @default 当前值+value
         */
        formater: function (value) {
            return "当前值：" + value;
        }
    };

    $.fn.slider.Constructor = Slider;

})(window.jQuery);