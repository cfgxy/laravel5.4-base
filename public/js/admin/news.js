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
/******/ 	return __webpack_require__(__webpack_require__.s = 264);
/******/ })
/************************************************************************/
/******/ ({

/***/ 183:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(9)(
  /* script */
  __webpack_require__(206),
  /* template */
  __webpack_require__(246),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/Volumes/Data/Users/guxy/projects/laravel5.4-base/app/Features/Admin/vue/pages/news/Edit.vue.html"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] Edit.vue.html: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5c35e32a", Component.options)
  } else {
    hotAPI.reload("data-v-5c35e32a", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 189:
/***/ (function(module, exports, __webpack_require__) {

startApp({
    'routes': [{ path: '/', component: __webpack_require__(232) }, { path: '/create', component: __webpack_require__(183) }, { path: '/edit/:id', component: __webpack_require__(183) }]
});

/***/ }),

/***/ 206:
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


/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {
        var _this = this;

        var newsId = this.$route.params.id;
        if (newsId) {
            this.newsId = newsId;
            this.formName = 'news.updation';

            axios.get('./data/' + newsId).then(function (ret) {
                var msg = ret.data;

                _this.news = msg.data;

                _this.ue = UE.getEditor('container', {
                    autoHeightEnabled: true
                });

                _this.ue.ready(function () {
                    _this.ue.setContent(_this.news.content);
                });
            });
        } else {
            this.ue = UE.getEditor('container', {
                autoHeightEnabled: true
            });
        }

        $('form', this.$el).on('jsvalidator.ok', function (e) {
            _this.onSubmit();
        });
    },
    destroyed: function destroyed() {
        this.ue.destroy();
    },
    data: function data() {
        return {
            news: {},
            formName: 'news.creation'
        };
    },

    methods: {
        onSubmit: function onSubmit() {
            var obj = $('form', this.$el).serializeJSON();

            var url = './data';
            if (this.newsId) {
                url += '/' + this.newsId;
            }

            axios.post('./data', obj).then(function (ret) {
                var msg = ret.data;

                if (msg.code === 422) {
                    $.addErrorStates(form, msg.data.errors);
                    return apperror(msg.msg);
                } else if (msg.code > 0) {
                    return apperror(msg.msg);
                }

                location.href = '/admin/news/#/';
            });
        }
    }
});

/***/ }),

/***/ 207:
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


/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        var _this = this;

        return {
            ds: new APIPagerDatasource('./data', [{ headerName: "ID", field: "id", width: 120 }, { headerName: "标题", field: "title" }, { headerName: "创建日期", width: 150, field: "created_at" }], [{ name: 'act1', label: '动作1', icon: 'fa-bookmark', url: '/admin/news/#/edit/{{id}}' }]).ajaxData(function (data) {
                return _.defaults({ show_all: _this.showAll }, data, _this.searchParams);
            }).success(function (ret) {
                _this.extras = ret.data.extras;
            }),
            searchParams: {},
            showAll: false,
            currentRow: {},
            extras: {},
            breadcrum: [appDashboardBreadcrumItem, makeAppBreadcrum('用户管理', "./#" + this.$route.path, 'fa-user')]
        };
    },

    methods: {
        onSearch: function onSearch(params) {
            this.searchParams = params.data;
            this.$refs.grid.pager.loadPage();
        },
        onAdd: function onAdd() {
            location.href = '#/create';
        },
        onDelete: function onDelete() {
            var _this2 = this;

            var ids = _.map(this.$refs.grid.api.getSelectedRows(), 'id');
            if (!ids.length) {
                apptoast('请勾选需要删除的项');
                return;
            }

            appconfirm("\u5C06\u8981\u5220\u9664" + ids.length + "\u6761\u8BB0\u5F55,\u662F\u5426\u7EE7\u7EED?", function () {
                axios.delete('./data/selected', {
                    'params': { ids: ids }
                }).then(function (ret) {
                    var msg = ret.data;

                    if (msg.code > 0) {
                        apperror(msg.msg);
                        return;
                    } else {
                        appsuccess(msg.msg);
                    }
                    _this2.$refs.grid.api.deselectAll();
                    _this2.$refs.grid.pager.loadPage();
                });
            });
        },
        onRowAction: function onRowAction(act, row) {
            alert(act.name);
        }
    }
});

/***/ }),

/***/ 232:
/***/ (function(module, exports, __webpack_require__) {

var Component = __webpack_require__(9)(
  /* script */
  __webpack_require__(207),
  /* template */
  __webpack_require__(244),
  /* scopeId */
  null,
  /* cssModules */
  null
)
Component.options.__file = "/Volumes/Data/Users/guxy/projects/laravel5.4-base/app/Features/Admin/vue/pages/news/List.vue.html"
if (Component.esModule && Object.keys(Component.esModule).some(function (key) {return key !== "default" && key !== "__esModule"})) {console.error("named exports are not supported in *.vue files.")}
if (Component.options.functional) {console.error("[vue-loader] List.vue.html: functional components are not supported with templates, they should use render functions.")}

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4d9b71d4", Component.options)
  } else {
    hotAPI.reload("data-v-4d9b71d4", Component.options)
  }
})()}

module.exports = Component.exports


/***/ }),

/***/ 244:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('panel', [_c('span', {
    slot: "title"
  }, [_vm._v("\n         用户管理\n    ")]), _vm._v(" "), _c('div', {
    slot: "body"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-md-5"
  }, [_c('h4', {
    staticClass: "guxy-title"
  }, [_c('button', {
    staticClass: "btn btn-app-mid btn-app-red btn-sm",
    attrs: {
      "type": "button"
    },
    on: {
      "click": _vm.onAdd
    }
  }, [_vm._v("新增")])])]), _vm._v(" "), _c('div', {
    staticClass: "col-md-5 pull-right"
  }, [_c('search-bar', {
    staticClass: "mt5",
    attrs: {
      "id": "searchbar1"
    },
    on: {
      "search": _vm.onSearch
    }
  })], 1)]), _vm._v(" "), _c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-md-12"
  }, [_c('flex-table', {
    ref: "grid",
    attrs: {
      "id": "sample",
      "dataSource": _vm.ds,
      "height": "450px"
    },
    on: {
      "row-action": _vm.onRowAction
    }
  })], 1)])]), _vm._v(" "), _c('div', {
    slot: "extras"
  })])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4d9b71d4", module.exports)
  }
}

/***/ }),

/***/ 246:
/***/ (function(module, exports, __webpack_require__) {

module.exports={render:function (){var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;
  return _c('panel', [_c('span', {
    slot: "title"
  }, [_vm._v("\n         内容管理\n    ")]), _vm._v(" "), _c('div', {
    slot: "body"
  }, [_c('div', {
    staticClass: "row"
  }, [_c('div', {
    staticClass: "col-md-12"
  }, [_c('form', {
    staticClass: "form-horizontal",
    attrs: {
      "name": _vm.formName,
      "data-toggle": "jsvalidator"
    }
  }, [_c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("标题")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-10"
  }, [_c('input', {
    staticClass: "form-control",
    attrs: {
      "name": "title",
      "type": "text",
      "placeholder": "输入标题"
    },
    domProps: {
      "value": _vm.news.title || ''
    }
  })])]), _vm._v(" "), _c('div', {
    staticClass: "form-group"
  }, [_c('label', {
    staticClass: "col-sm-2 control-label"
  }, [_vm._v("内容")]), _vm._v(" "), _c('div', {
    staticClass: "col-sm-10"
  }, [_c('script', {
    attrs: {
      "id": "container",
      "name": "content",
      "type": "text/plain"
    }
  })])]), _vm._v(" "), _c('input', {
    staticClass: "button pull-right",
    attrs: {
      "type": "submit",
      "value": "提交"
    }
  })])])])]), _vm._v(" "), _c('div', {
    slot: "extras"
  })])
},staticRenderFns: []}
module.exports.render._withStripped = true
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-5c35e32a", module.exports)
  }
}

/***/ }),

/***/ 264:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(189);


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