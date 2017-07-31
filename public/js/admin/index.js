/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 262);
/******/ })
/************************************************************************/
/******/ ({

/***/ 187:
/***/ (function(module, exports, __webpack_require__) {

startApp({
    'routes': [{ path: '/', component: __webpack_require__(229) }]
});

/***/ }),

/***/ 203:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {}
});

/***/ }),

/***/ 229:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(9)(
  /* script */
  __webpack_require__(203),
  /* template */
  __webpack_require__(250),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/Volumes/Data/Users/guxy/projects/laravel5.4-base/app/Features/Admin/vue/pages/Dashboard.vue.html"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Dashboard.vue.html: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-77f6cc5c", Component.options)
  } else {
    hotAPI.reload("data-v-77f6cc5c", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 250:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('panel', [_c('span', {
    slot: "title"
  }, [_vm._v("业务管理系统")]), _vm._v(" "), _c('span', {
    slot: "body"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-md-3 col-sm-6 col-xs-12"
  }, [_c('div', {
    staticClass: "info-box"
  }, [_c('span', {
    staticClass: "info-box-icon bg-aqua"
  }, [_c('i', {
    staticClass: "ion ion-ios-gear-outline"
  })]), _vm._v(" "), _c('div', {
    staticClass: "info-box-content"
  }, [_c('span', {
    staticClass: "info-box-text"
  }, [_vm._v("CPU Traffic")]), _vm._v(" "), _c('span', {
    staticClass: "info-box-number"
  }, [_vm._v("90"), _c('small', [_vm._v("%")])])])])]), _vm._v(" "), _c('div', {
    staticClass: "col-md-3 col-sm-6 col-xs-12"
  }, [_c('div', {
    staticClass: "info-box"
  }, [_c('span', {
    staticClass: "info-box-icon bg-red"
  }, [_c('i', {
    staticClass: "fa fa-google-plus"
  })]), _vm._v(" "), _c('div', {
    staticClass: "info-box-content"
  }, [_c('span', {
    staticClass: "info-box-text"
  }, [_vm._v("Likes")]), _vm._v(" "), _c('span', {
    staticClass: "info-box-number"
  }, [_vm._v("41,410")])])])]), _vm._v(" "), _c('div', {
    staticClass: "clearfix visible-sm-block"
  }), _vm._v(" "), _c('div', {
    staticClass: "col-md-3 col-sm-6 col-xs-12"
  }, [_c('div', {
    staticClass: "info-box"
  }, [_c('span', {
    staticClass: "info-box-icon bg-green"
  }, [_c('i', {
    staticClass: "ion ion-ios-cart-outline"
  })]), _vm._v(" "), _c('div', {
    staticClass: "info-box-content"
  }, [_c('span', {
    staticClass: "info-box-text"
  }, [_vm._v("Sales")]), _vm._v(" "), _c('span', {
    staticClass: "info-box-number"
  }, [_vm._v("760")])])])]), _vm._v(" "), _c('div', {
    staticClass: "col-md-3 col-sm-6 col-xs-12"
  }, [_c('div', {
    staticClass: "info-box"
  }, [_c('span', {
    staticClass: "info-box-icon bg-yellow"
  }, [_c('i', {
    staticClass: "ion ion-ios-people-outline"
  })]), _vm._v(" "), _c('div', {
    staticClass: "info-box-content"
  }, [_c('span', {
    staticClass: "info-box-text"
  }, [_vm._v("New Members")]), _vm._v(" "), _c('span', {
    staticClass: "info-box-number"
  }, [_vm._v("2,000")])])])])])])])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-77f6cc5c", module.exports)
  }
}

/***/ }),

/***/ 262:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(187);


/***/ }),

/***/ 9:
/***/ (function(module, exports) {

// this module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  scopeId,
  cssModules
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  // inject cssModules
  if (cssModules) {
    var computed = Object.create(options.computed || null)
    Object.keys(cssModules).forEach(function (key) {
      var module = cssModules[key]
      computed[key] = function () { return module }
    })
    options.computed = computed
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ })

/******/ });