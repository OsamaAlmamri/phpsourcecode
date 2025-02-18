/*!
 * Vux v0.1.3 (https://vux.li)
 * Licensed under the MIT license
 */
!function (t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.vuxXButton = e() : t.vuxXButton = e()
}(this, function () {
    return function (t) {
        function e(n) {
            if (o[n])return o[n].exports;
            var s = o[n] = {exports: {}, id: n, loaded: !1};
            return t[n].call(s.exports, s, s.exports, e), s.loaded = !0, s.exports
        }

        var o = {};
        return e.m = t, e.c = o, e.p = "", e(0)
    }([function (t, e, o) {
        t.exports = o(4)
    }, function (t, e) {
        "use strict";
        Object.defineProperty(e, "__esModule", {value: !0}), e["default"] = {
            props: {
                type: {"default": "default"},
                disabled: Boolean,
                mini: Boolean,
                plain: Boolean,
                text: String
            }, computed: {
                classes: function () {
                    return [{
                        weui_btn_disabled: this.disabled,
                        weui_btn_mini: this.mini
                    }, "weui_btn_" + this.type, this.plain ? "weui_btn_plain_" + this.type : ""]
                }
            }
        }
    }, function (t, e) {
    }, function (t, e) {
        t.exports = "<button class=weui_btn :class=classes :disabled=disabled> {{text}}<slot></slot> </button>"
    }, function (t, e, o) {
        var n, s;
        o(2), n = o(1), s = o(3), t.exports = n || {}, t.exports.__esModule && (t.exports = t.exports["default"]), s && (("function" == typeof t.exports ? t.exports.options || (t.exports.options = {}) : t.exports).template = s)
    }])
});