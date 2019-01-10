/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
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
/******/ 	__webpack_require__.p = "/build/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var Routing = __webpack_require__(/*! ../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router */ "./vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js");

console.log(Routing);

/***/ }),

/***/ "./vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js":
/*!********************************************************************************!*\
  !*** ./vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js ***!
  \********************************************************************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var _typeof2 = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

(function (root, factory) {
    var routing = factory();
    if (true) {
        // AMD. Register as an anonymous module.
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (routing.Routing),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if ((typeof module === 'undefined' ? 'undefined' : _typeof2(module)) === 'object' && module.exports) {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = routing.Routing;
    } else {
        // Browser globals (root is window)
        root.Routing = routing.Routing;
        root.fos = {
            Router: routing.Router
        };
    }
})(this, function () {
    'use strict';

    /**
     * @fileoverview This file defines the Router class.
     *
     * You can compile this file by running the following command from the Resources folder:
     *
     *    npm install && npm run build
     */

    /**
     * Class Router
     */

    var _extends = Object.assign || function (target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];for (var key in source) {
                if (Object.prototype.hasOwnProperty.call(source, key)) {
                    target[key] = source[key];
                }
            }
        }return target;
    };

    var _typeof = typeof Symbol === "function" && _typeof2(Symbol.iterator) === "symbol" ? function (obj) {
        return typeof obj === 'undefined' ? 'undefined' : _typeof2(obj);
    } : function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj === 'undefined' ? 'undefined' : _typeof2(obj);
    };

    var _createClass = function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];descriptor.enumerable = descriptor.enumerable || false;descriptor.configurable = true;if ("value" in descriptor) descriptor.writable = true;Object.defineProperty(target, descriptor.key, descriptor);
            }
        }return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);if (staticProps) defineProperties(Constructor, staticProps);return Constructor;
        };
    }();

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
        }
    }

    var Router = function () {

        /**
         * @constructor
         * @param {Router.Context=} context
         * @param {Object.<string, Router.Route>=} routes
         */
        function Router(context, routes) {
            _classCallCheck(this, Router);

            this.context_ = context || { base_url: '', prefix: '', host: '', scheme: '' };
            this.setRoutes(routes || {});
        }

        /**
         * Returns the current instance.
         * @returns {Router}
         */

        _createClass(Router, [{
            key: 'setRoutingData',

            /**
             * Sets data for the current instance
             * @param {Object} data
             */
            value: function setRoutingData(data) {
                this.setBaseUrl(data['base_url']);
                this.setRoutes(data['routes']);

                if ('prefix' in data) {
                    this.setPrefix(data['prefix']);
                }

                this.setHost(data['host']);
                this.setScheme(data['scheme']);
            }

            /**
             * @param {Object.<string, Router.Route>} routes
             */

        }, {
            key: 'setRoutes',
            value: function setRoutes(routes) {
                this.routes_ = Object.freeze(routes);
            }

            /**
             * @return {Object.<string, Router.Route>} routes
             */

        }, {
            key: 'getRoutes',
            value: function getRoutes() {
                return this.routes_;
            }

            /**
             * @param {string} baseUrl
             */

        }, {
            key: 'setBaseUrl',
            value: function setBaseUrl(baseUrl) {
                this.context_.base_url = baseUrl;
            }

            /**
             * @return {string}
             */

        }, {
            key: 'getBaseUrl',
            value: function getBaseUrl() {
                return this.context_.base_url;
            }

            /**
             * @param {string} prefix
             */

        }, {
            key: 'setPrefix',
            value: function setPrefix(prefix) {
                this.context_.prefix = prefix;
            }

            /**
             * @param {string} scheme
             */

        }, {
            key: 'setScheme',
            value: function setScheme(scheme) {
                this.context_.scheme = scheme;
            }

            /**
             * @return {string}
             */

        }, {
            key: 'getScheme',
            value: function getScheme() {
                return this.context_.scheme;
            }

            /**
             * @param {string} host
             */

        }, {
            key: 'setHost',
            value: function setHost(host) {
                this.context_.host = host;
            }

            /**
             * @return {string}
             */

        }, {
            key: 'getHost',
            value: function getHost() {
                return this.context_.host;
            }

            /**
             * Builds query string params added to a URL.
             * Port of jQuery's $.param() function, so credit is due there.
             *
             * @param {string} prefix
             * @param {Array|Object|string} params
             * @param {Function} add
             */

        }, {
            key: 'buildQueryParams',
            value: function buildQueryParams(prefix, params, add) {
                var _this = this;

                var name = void 0;
                var rbracket = new RegExp(/\[\]$/);

                if (params instanceof Array) {
                    params.forEach(function (val, i) {
                        if (rbracket.test(prefix)) {
                            add(prefix, val);
                        } else {
                            _this.buildQueryParams(prefix + '[' + ((typeof val === 'undefined' ? 'undefined' : _typeof(val)) === 'object' ? i : '') + ']', val, add);
                        }
                    });
                } else if ((typeof params === 'undefined' ? 'undefined' : _typeof(params)) === 'object') {
                    for (name in params) {
                        this.buildQueryParams(prefix + '[' + name + ']', params[name], add);
                    }
                } else {
                    add(prefix, params);
                }
            }

            /**
             * Returns a raw route object.
             *
             * @param {string} name
             * @return {Router.Route}
             */

        }, {
            key: 'getRoute',
            value: function getRoute(name) {
                var prefixedName = this.context_.prefix + name;

                if (!(prefixedName in this.routes_)) {
                    // Check first for default route before failing
                    if (!(name in this.routes_)) {
                        throw new Error('The route "' + name + '" does not exist.');
                    }
                } else {
                    name = prefixedName;
                }

                return this.routes_[name];
            }

            /**
             * Generates the URL for a route.
             *
             * @param {string} name
             * @param {Object.<string, string>} opt_params
             * @param {boolean} absolute
             * @return {string}
             */

        }, {
            key: 'generate',
            value: function generate(name, opt_params, absolute) {
                var route = this.getRoute(name),
                    params = opt_params || {},
                    unusedParams = _extends({}, params),
                    url = '',
                    optional = true,
                    host = '';

                route.tokens.forEach(function (token) {
                    if ('text' === token[0]) {
                        url = token[1] + url;
                        optional = false;

                        return;
                    }

                    if ('variable' === token[0]) {
                        var hasDefault = route.defaults && token[3] in route.defaults;
                        if (false === optional || !hasDefault || token[3] in params && params[token[3]] != route.defaults[token[3]]) {
                            var value = void 0;

                            if (token[3] in params) {
                                value = params[token[3]];
                                delete unusedParams[token[3]];
                            } else if (hasDefault) {
                                value = route.defaults[token[3]];
                            } else if (optional) {
                                return;
                            } else {
                                throw new Error('The route "' + name + '" requires the parameter "' + token[3] + '".');
                            }

                            var empty = true === value || false === value || '' === value;

                            if (!empty || !optional) {
                                var encodedValue = encodeURIComponent(value).replace(/%2F/g, '/');

                                if ('null' === encodedValue && null === value) {
                                    encodedValue = '';
                                }

                                url = token[1] + encodedValue + url;
                            }

                            optional = false;
                        } else if (hasDefault && token[3] in unusedParams) {
                            delete unusedParams[token[3]];
                        }

                        return;
                    }

                    throw new Error('The token type "' + token[0] + '" is not supported.');
                });

                if (url === '') {
                    url = '/';
                }

                route.hosttokens.forEach(function (token) {
                    var value = void 0;

                    if ('text' === token[0]) {
                        host = token[1] + host;

                        return;
                    }

                    if ('variable' === token[0]) {
                        if (token[3] in params) {
                            value = params[token[3]];
                            delete unusedParams[token[3]];
                        } else if (route.defaults && token[3] in route.defaults) {
                            value = route.defaults[token[3]];
                        }

                        host = token[1] + value + host;
                    }
                });
                // Foo-bar!
                url = this.context_.base_url + url;
                if (route.requirements && "_scheme" in route.requirements && this.getScheme() != route.requirements["_scheme"]) {
                    url = route.requirements["_scheme"] + "://" + (host || this.getHost()) + url;
                } else if ("undefined" !== typeof route.schemes && "undefined" !== typeof route.schemes[0] && this.getScheme() !== route.schemes[0]) {
                    url = route.schemes[0] + "://" + (host || this.getHost()) + url;
                } else if (host && this.getHost() !== host) {
                    url = this.getScheme() + "://" + host + url;
                } else if (absolute === true) {
                    url = this.getScheme() + "://" + this.getHost() + url;
                }

                if (Object.keys(unusedParams).length > 0) {
                    var prefix = void 0;
                    var queryParams = [];
                    var add = function add(key, value) {
                        // if value is a function then call it and assign it's return value as value
                        value = typeof value === 'function' ? value() : value;

                        // change null to empty string
                        value = value === null ? '' : value;

                        queryParams.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
                    };

                    for (prefix in unusedParams) {
                        this.buildQueryParams(prefix, unusedParams[prefix], add);
                    }

                    url = url + '?' + queryParams.join('&').replace(/%20/g, '+');
                }

                return url;
            }
        }], [{
            key: 'getInstance',
            value: function getInstance() {
                return Routing;
            }

            /**
             * Configures the current Router instance with the provided data.
             * @param {Object} data
             */

        }, {
            key: 'setData',
            value: function setData(data) {
                var router = Router.getInstance();

                router.setRoutingData(data);
            }
        }]);

        return Router;
    }();

    /**
     * @typedef {{
     *     tokens: (Array.<Array.<string>>),
     *     defaults: (Object.<string, string>),
     *     requirements: Object,
     *     hosttokens: (Array.<string>)
     * }}
     */

    Router.Route;

    /**
     * @typedef {{
     *     base_url: (string)
     * }}
     */
    Router.Context;

    /**
     * Router singleton.
     * @const
     * @type {Router}
     */
    var Routing = new Router();

    return { Router: Router, Routing: Routing };
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgNjlmOTk3MjhhY2MxNTgyMTBjNGUiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2FwcC5qcyIsIndlYnBhY2s6Ly8vLi92ZW5kb3IvZnJpZW5kc29mc3ltZm9ueS9qc3JvdXRpbmctYnVuZGxlL1Jlc291cmNlcy9wdWJsaWMvanMvcm91dGVyLmpzIl0sIm5hbWVzIjpbIlJvdXRpbmciLCJyZXF1aXJlIiwiY29uc29sZSIsImxvZyIsInJvb3QiLCJmYWN0b3J5Iiwicm91dGluZyIsImRlZmluZSIsIm1vZHVsZSIsImV4cG9ydHMiLCJmb3MiLCJSb3V0ZXIiLCJfZXh0ZW5kcyIsIk9iamVjdCIsImFzc2lnbiIsInRhcmdldCIsImkiLCJhcmd1bWVudHMiLCJsZW5ndGgiLCJzb3VyY2UiLCJrZXkiLCJwcm90b3R5cGUiLCJoYXNPd25Qcm9wZXJ0eSIsImNhbGwiLCJfdHlwZW9mIiwiU3ltYm9sIiwiaXRlcmF0b3IiLCJvYmoiLCJjb25zdHJ1Y3RvciIsIl9jcmVhdGVDbGFzcyIsImRlZmluZVByb3BlcnRpZXMiLCJwcm9wcyIsImRlc2NyaXB0b3IiLCJlbnVtZXJhYmxlIiwiY29uZmlndXJhYmxlIiwid3JpdGFibGUiLCJkZWZpbmVQcm9wZXJ0eSIsIkNvbnN0cnVjdG9yIiwicHJvdG9Qcm9wcyIsInN0YXRpY1Byb3BzIiwiX2NsYXNzQ2FsbENoZWNrIiwiaW5zdGFuY2UiLCJUeXBlRXJyb3IiLCJjb250ZXh0Iiwicm91dGVzIiwiY29udGV4dF8iLCJiYXNlX3VybCIsInByZWZpeCIsImhvc3QiLCJzY2hlbWUiLCJzZXRSb3V0ZXMiLCJ2YWx1ZSIsInNldFJvdXRpbmdEYXRhIiwiZGF0YSIsInNldEJhc2VVcmwiLCJzZXRQcmVmaXgiLCJzZXRIb3N0Iiwic2V0U2NoZW1lIiwicm91dGVzXyIsImZyZWV6ZSIsImdldFJvdXRlcyIsImJhc2VVcmwiLCJnZXRCYXNlVXJsIiwiZ2V0U2NoZW1lIiwiZ2V0SG9zdCIsImJ1aWxkUXVlcnlQYXJhbXMiLCJwYXJhbXMiLCJhZGQiLCJfdGhpcyIsIm5hbWUiLCJyYnJhY2tldCIsIlJlZ0V4cCIsIkFycmF5IiwiZm9yRWFjaCIsInZhbCIsInRlc3QiLCJnZXRSb3V0ZSIsInByZWZpeGVkTmFtZSIsIkVycm9yIiwiZ2VuZXJhdGUiLCJvcHRfcGFyYW1zIiwiYWJzb2x1dGUiLCJyb3V0ZSIsInVudXNlZFBhcmFtcyIsInVybCIsIm9wdGlvbmFsIiwidG9rZW5zIiwidG9rZW4iLCJoYXNEZWZhdWx0IiwiZGVmYXVsdHMiLCJlbXB0eSIsImVuY29kZWRWYWx1ZSIsImVuY29kZVVSSUNvbXBvbmVudCIsInJlcGxhY2UiLCJob3N0dG9rZW5zIiwicmVxdWlyZW1lbnRzIiwic2NoZW1lcyIsImtleXMiLCJxdWVyeVBhcmFtcyIsInB1c2giLCJqb2luIiwiZ2V0SW5zdGFuY2UiLCJzZXREYXRhIiwicm91dGVyIiwiUm91dGUiLCJDb250ZXh0Il0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7QUM3REEsSUFBSUEsVUFBVUMsbUJBQU9BLENBQUMsMkpBQVIsQ0FBZDs7QUFFQUMsUUFBUUMsR0FBUixDQUFZSCxPQUFaLEU7Ozs7Ozs7Ozs7Ozs7O0FDRkMsV0FBVUksSUFBVixFQUFnQkMsT0FBaEIsRUFBeUI7QUFDdEIsUUFBSUMsVUFB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ZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIi9idWlsZC9cIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vYXNzZXRzL2pzL2FwcC5qc1wiKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCA2OWY5OTcyOGFjYzE1ODIxMGM0ZSIsImxldCBSb3V0aW5nID0gcmVxdWlyZSgnLi4vLi4vdmVuZG9yL2ZyaWVuZHNvZnN5bWZvbnkvanNyb3V0aW5nLWJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3JvdXRlcicpO1xyXG5cclxuY29uc29sZS5sb2coUm91dGluZyk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2FwcC5qcyIsIihmdW5jdGlvbiAocm9vdCwgZmFjdG9yeSkge1xuICAgIHZhciByb3V0aW5nID0gZmFjdG9yeSgpO1xuICAgIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAgICAgLy8gQU1ELiBSZWdpc3RlciBhcyBhbiBhbm9ueW1vdXMgbW9kdWxlLlxuICAgICAgICBkZWZpbmUoW10sIHJvdXRpbmcuUm91dGluZyk7XG4gICAgfSBlbHNlIGlmICh0eXBlb2YgbW9kdWxlID09PSAnb2JqZWN0JyAmJiBtb2R1bGUuZXhwb3J0cykge1xuICAgICAgICAvLyBOb2RlLiBEb2VzIG5vdCB3b3JrIHdpdGggc3RyaWN0IENvbW1vbkpTLCBidXRcbiAgICAgICAgLy8gb25seSBDb21tb25KUy1saWtlIGVudmlyb25tZW50cyB0aGF0IHN1cHBvcnQgbW9kdWxlLmV4cG9ydHMsXG4gICAgICAgIC8vIGxpa2UgTm9kZS5cbiAgICAgICAgbW9kdWxlLmV4cG9ydHMgPSByb3V0aW5nLlJvdXRpbmc7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgLy8gQnJvd3NlciBnbG9iYWxzIChyb290IGlzIHdpbmRvdylcbiAgICAgICAgcm9vdC5Sb3V0aW5nID0gcm91dGluZy5Sb3V0aW5nO1xuICAgICAgICByb290LmZvcyA9IHtcbiAgICAgICAgICAgIFJvdXRlcjogcm91dGluZy5Sb3V0ZXIsXG4gICAgICAgIH07XG4gICAgfVxufSh0aGlzLCBmdW5jdGlvbiAoKSB7XG4gICAgJ3VzZSBzdHJpY3QnO1xuXG4vKipcbiAqIEBmaWxlb3ZlcnZpZXcgVGhpcyBmaWxlIGRlZmluZXMgdGhlIFJvdXRlciBjbGFzcy5cbiAqXG4gKiBZb3UgY2FuIGNvbXBpbGUgdGhpcyBmaWxlIGJ5IHJ1bm5pbmcgdGhlIGZvbGxvd2luZyBjb21tYW5kIGZyb20gdGhlIFJlc291cmNlcyBmb2xkZXI6XG4gKlxuICogICAgbnBtIGluc3RhbGwgJiYgbnBtIHJ1biBidWlsZFxuICovXG5cbi8qKlxuICogQ2xhc3MgUm91dGVyXG4gKi9cblxudmFyIF9leHRlbmRzID0gT2JqZWN0LmFzc2lnbiB8fCBmdW5jdGlvbiAodGFyZ2V0KSB7IGZvciAodmFyIGkgPSAxOyBpIDwgYXJndW1lbnRzLmxlbmd0aDsgaSsrKSB7IHZhciBzb3VyY2UgPSBhcmd1bWVudHNbaV07IGZvciAodmFyIGtleSBpbiBzb3VyY2UpIHsgaWYgKE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChzb3VyY2UsIGtleSkpIHsgdGFyZ2V0W2tleV0gPSBzb3VyY2Vba2V5XTsgfSB9IH0gcmV0dXJuIHRhcmdldDsgfTtcblxudmFyIF90eXBlb2YgPSB0eXBlb2YgU3ltYm9sID09PSBcImZ1bmN0aW9uXCIgJiYgdHlwZW9mIFN5bWJvbC5pdGVyYXRvciA9PT0gXCJzeW1ib2xcIiA/IGZ1bmN0aW9uIChvYmopIHsgcmV0dXJuIHR5cGVvZiBvYmo7IH0gOiBmdW5jdGlvbiAob2JqKSB7IHJldHVybiBvYmogJiYgdHlwZW9mIFN5bWJvbCA9PT0gXCJmdW5jdGlvblwiICYmIG9iai5jb25zdHJ1Y3RvciA9PT0gU3ltYm9sICYmIG9iaiAhPT0gU3ltYm9sLnByb3RvdHlwZSA/IFwic3ltYm9sXCIgOiB0eXBlb2Ygb2JqOyB9O1xuXG52YXIgX2NyZWF0ZUNsYXNzID0gZnVuY3Rpb24gKCkgeyBmdW5jdGlvbiBkZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgcHJvcHMpIHsgZm9yICh2YXIgaSA9IDA7IGkgPCBwcm9wcy5sZW5ndGg7IGkrKykgeyB2YXIgZGVzY3JpcHRvciA9IHByb3BzW2ldOyBkZXNjcmlwdG9yLmVudW1lcmFibGUgPSBkZXNjcmlwdG9yLmVudW1lcmFibGUgfHwgZmFsc2U7IGRlc2NyaXB0b3IuY29uZmlndXJhYmxlID0gdHJ1ZTsgaWYgKFwidmFsdWVcIiBpbiBkZXNjcmlwdG9yKSBkZXNjcmlwdG9yLndyaXRhYmxlID0gdHJ1ZTsgT2JqZWN0LmRlZmluZVByb3BlcnR5KHRhcmdldCwgZGVzY3JpcHRvci5rZXksIGRlc2NyaXB0b3IpOyB9IH0gcmV0dXJuIGZ1bmN0aW9uIChDb25zdHJ1Y3RvciwgcHJvdG9Qcm9wcywgc3RhdGljUHJvcHMpIHsgaWYgKHByb3RvUHJvcHMpIGRlZmluZVByb3BlcnRpZXMoQ29uc3RydWN0b3IucHJvdG90eXBlLCBwcm90b1Byb3BzKTsgaWYgKHN0YXRpY1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLCBzdGF0aWNQcm9wcyk7IHJldHVybiBDb25zdHJ1Y3RvcjsgfTsgfSgpO1xuXG5mdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7IGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7IHRocm93IG5ldyBUeXBlRXJyb3IoXCJDYW5ub3QgY2FsbCBhIGNsYXNzIGFzIGEgZnVuY3Rpb25cIik7IH0gfVxuXG52YXIgUm91dGVyID0gZnVuY3Rpb24gKCkge1xuXG4gICAgLyoqXG4gICAgICogQGNvbnN0cnVjdG9yXG4gICAgICogQHBhcmFtIHtSb3V0ZXIuQ29udGV4dD19IGNvbnRleHRcbiAgICAgKiBAcGFyYW0ge09iamVjdC48c3RyaW5nLCBSb3V0ZXIuUm91dGU+PX0gcm91dGVzXG4gICAgICovXG4gICAgZnVuY3Rpb24gUm91dGVyKGNvbnRleHQsIHJvdXRlcykge1xuICAgICAgICBfY2xhc3NDYWxsQ2hlY2sodGhpcywgUm91dGVyKTtcblxuICAgICAgICB0aGlzLmNvbnRleHRfID0gY29udGV4dCB8fCB7IGJhc2VfdXJsOiAnJywgcHJlZml4OiAnJywgaG9zdDogJycsIHNjaGVtZTogJycgfTtcbiAgICAgICAgdGhpcy5zZXRSb3V0ZXMocm91dGVzIHx8IHt9KTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBSZXR1cm5zIHRoZSBjdXJyZW50IGluc3RhbmNlLlxuICAgICAqIEByZXR1cm5zIHtSb3V0ZXJ9XG4gICAgICovXG5cblxuICAgIF9jcmVhdGVDbGFzcyhSb3V0ZXIsIFt7XG4gICAgICAgIGtleTogJ3NldFJvdXRpbmdEYXRhJyxcblxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBTZXRzIGRhdGEgZm9yIHRoZSBjdXJyZW50IGluc3RhbmNlXG4gICAgICAgICAqIEBwYXJhbSB7T2JqZWN0fSBkYXRhXG4gICAgICAgICAqL1xuICAgICAgICB2YWx1ZTogZnVuY3Rpb24gc2V0Um91dGluZ0RhdGEoZGF0YSkge1xuICAgICAgICAgICAgdGhpcy5zZXRCYXNlVXJsKGRhdGFbJ2Jhc2VfdXJsJ10pO1xuICAgICAgICAgICAgdGhpcy5zZXRSb3V0ZXMoZGF0YVsncm91dGVzJ10pO1xuXG4gICAgICAgICAgICBpZiAoJ3ByZWZpeCcgaW4gZGF0YSkge1xuICAgICAgICAgICAgICAgIHRoaXMuc2V0UHJlZml4KGRhdGFbJ3ByZWZpeCddKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5zZXRIb3N0KGRhdGFbJ2hvc3QnXSk7XG4gICAgICAgICAgICB0aGlzLnNldFNjaGVtZShkYXRhWydzY2hlbWUnXSk7XG4gICAgICAgIH1cblxuICAgICAgICAvKipcbiAgICAgICAgICogQHBhcmFtIHtPYmplY3QuPHN0cmluZywgUm91dGVyLlJvdXRlPn0gcm91dGVzXG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdzZXRSb3V0ZXMnLFxuICAgICAgICB2YWx1ZTogZnVuY3Rpb24gc2V0Um91dGVzKHJvdXRlcykge1xuICAgICAgICAgICAgdGhpcy5yb3V0ZXNfID0gT2JqZWN0LmZyZWV6ZShyb3V0ZXMpO1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEByZXR1cm4ge09iamVjdC48c3RyaW5nLCBSb3V0ZXIuUm91dGU+fSByb3V0ZXNcbiAgICAgICAgICovXG5cbiAgICB9LCB7XG4gICAgICAgIGtleTogJ2dldFJvdXRlcycsXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbiBnZXRSb3V0ZXMoKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5yb3V0ZXNfO1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEBwYXJhbSB7c3RyaW5nfSBiYXNlVXJsXG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdzZXRCYXNlVXJsJyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHNldEJhc2VVcmwoYmFzZVVybCkge1xuICAgICAgICAgICAgdGhpcy5jb250ZXh0Xy5iYXNlX3VybCA9IGJhc2VVcmw7XG4gICAgICAgIH1cblxuICAgICAgICAvKipcbiAgICAgICAgICogQHJldHVybiB7c3RyaW5nfVxuICAgICAgICAgKi9cblxuICAgIH0sIHtcbiAgICAgICAga2V5OiAnZ2V0QmFzZVVybCcsXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbiBnZXRCYXNlVXJsKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuY29udGV4dF8uYmFzZV91cmw7XG4gICAgICAgIH1cblxuICAgICAgICAvKipcbiAgICAgICAgICogQHBhcmFtIHtzdHJpbmd9IHByZWZpeFxuICAgICAgICAgKi9cblxuICAgIH0sIHtcbiAgICAgICAga2V5OiAnc2V0UHJlZml4JyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHNldFByZWZpeChwcmVmaXgpIHtcbiAgICAgICAgICAgIHRoaXMuY29udGV4dF8ucHJlZml4ID0gcHJlZml4O1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEBwYXJhbSB7c3RyaW5nfSBzY2hlbWVcbiAgICAgICAgICovXG5cbiAgICB9LCB7XG4gICAgICAgIGtleTogJ3NldFNjaGVtZScsXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbiBzZXRTY2hlbWUoc2NoZW1lKSB7XG4gICAgICAgICAgICB0aGlzLmNvbnRleHRfLnNjaGVtZSA9IHNjaGVtZTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBAcmV0dXJuIHtzdHJpbmd9XG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdnZXRTY2hlbWUnLFxuICAgICAgICB2YWx1ZTogZnVuY3Rpb24gZ2V0U2NoZW1lKCkge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuY29udGV4dF8uc2NoZW1lO1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEBwYXJhbSB7c3RyaW5nfSBob3N0XG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdzZXRIb3N0JyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHNldEhvc3QoaG9zdCkge1xuICAgICAgICAgICAgdGhpcy5jb250ZXh0Xy5ob3N0ID0gaG9zdDtcbiAgICAgICAgfVxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBAcmV0dXJuIHtzdHJpbmd9XG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdnZXRIb3N0JyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIGdldEhvc3QoKSB7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5jb250ZXh0Xy5ob3N0O1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEJ1aWxkcyBxdWVyeSBzdHJpbmcgcGFyYW1zIGFkZGVkIHRvIGEgVVJMLlxuICAgICAgICAgKiBQb3J0IG9mIGpRdWVyeSdzICQucGFyYW0oKSBmdW5jdGlvbiwgc28gY3JlZGl0IGlzIGR1ZSB0aGVyZS5cbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIHtzdHJpbmd9IHByZWZpeFxuICAgICAgICAgKiBAcGFyYW0ge0FycmF5fE9iamVjdHxzdHJpbmd9IHBhcmFtc1xuICAgICAgICAgKiBAcGFyYW0ge0Z1bmN0aW9ufSBhZGRcbiAgICAgICAgICovXG5cbiAgICB9LCB7XG4gICAgICAgIGtleTogJ2J1aWxkUXVlcnlQYXJhbXMnLFxuICAgICAgICB2YWx1ZTogZnVuY3Rpb24gYnVpbGRRdWVyeVBhcmFtcyhwcmVmaXgsIHBhcmFtcywgYWRkKSB7XG4gICAgICAgICAgICB2YXIgX3RoaXMgPSB0aGlzO1xuXG4gICAgICAgICAgICB2YXIgbmFtZSA9IHZvaWQgMDtcbiAgICAgICAgICAgIHZhciByYnJhY2tldCA9IG5ldyBSZWdFeHAoL1xcW1xcXSQvKTtcblxuICAgICAgICAgICAgaWYgKHBhcmFtcyBpbnN0YW5jZW9mIEFycmF5KSB7XG4gICAgICAgICAgICAgICAgcGFyYW1zLmZvckVhY2goZnVuY3Rpb24gKHZhbCwgaSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAocmJyYWNrZXQudGVzdChwcmVmaXgpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBhZGQocHJlZml4LCB2YWwpO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgX3RoaXMuYnVpbGRRdWVyeVBhcmFtcyhwcmVmaXggKyAnWycgKyAoKHR5cGVvZiB2YWwgPT09ICd1bmRlZmluZWQnID8gJ3VuZGVmaW5lZCcgOiBfdHlwZW9mKHZhbCkpID09PSAnb2JqZWN0JyA/IGkgOiAnJykgKyAnXScsIHZhbCwgYWRkKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSBlbHNlIGlmICgodHlwZW9mIHBhcmFtcyA9PT0gJ3VuZGVmaW5lZCcgPyAndW5kZWZpbmVkJyA6IF90eXBlb2YocGFyYW1zKSkgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgZm9yIChuYW1lIGluIHBhcmFtcykge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmJ1aWxkUXVlcnlQYXJhbXMocHJlZml4ICsgJ1snICsgbmFtZSArICddJywgcGFyYW1zW25hbWVdLCBhZGQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgYWRkKHByZWZpeCwgcGFyYW1zKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBSZXR1cm5zIGEgcmF3IHJvdXRlIG9iamVjdC5cbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIHtzdHJpbmd9IG5hbWVcbiAgICAgICAgICogQHJldHVybiB7Um91dGVyLlJvdXRlfVxuICAgICAgICAgKi9cblxuICAgIH0sIHtcbiAgICAgICAga2V5OiAnZ2V0Um91dGUnLFxuICAgICAgICB2YWx1ZTogZnVuY3Rpb24gZ2V0Um91dGUobmFtZSkge1xuICAgICAgICAgICAgdmFyIHByZWZpeGVkTmFtZSA9IHRoaXMuY29udGV4dF8ucHJlZml4ICsgbmFtZTtcblxuICAgICAgICAgICAgaWYgKCEocHJlZml4ZWROYW1lIGluIHRoaXMucm91dGVzXykpIHtcbiAgICAgICAgICAgICAgICAvLyBDaGVjayBmaXJzdCBmb3IgZGVmYXVsdCByb3V0ZSBiZWZvcmUgZmFpbGluZ1xuICAgICAgICAgICAgICAgIGlmICghKG5hbWUgaW4gdGhpcy5yb3V0ZXNfKSkge1xuICAgICAgICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ1RoZSByb3V0ZSBcIicgKyBuYW1lICsgJ1wiIGRvZXMgbm90IGV4aXN0LicpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbmFtZSA9IHByZWZpeGVkTmFtZTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHRoaXMucm91dGVzX1tuYW1lXTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBHZW5lcmF0ZXMgdGhlIFVSTCBmb3IgYSByb3V0ZS5cbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIHtzdHJpbmd9IG5hbWVcbiAgICAgICAgICogQHBhcmFtIHtPYmplY3QuPHN0cmluZywgc3RyaW5nPn0gb3B0X3BhcmFtc1xuICAgICAgICAgKiBAcGFyYW0ge2Jvb2xlYW59IGFic29sdXRlXG4gICAgICAgICAqIEByZXR1cm4ge3N0cmluZ31cbiAgICAgICAgICovXG5cbiAgICB9LCB7XG4gICAgICAgIGtleTogJ2dlbmVyYXRlJyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIGdlbmVyYXRlKG5hbWUsIG9wdF9wYXJhbXMsIGFic29sdXRlKSB7XG4gICAgICAgICAgICB2YXIgcm91dGUgPSB0aGlzLmdldFJvdXRlKG5hbWUpLFxuICAgICAgICAgICAgICAgIHBhcmFtcyA9IG9wdF9wYXJhbXMgfHwge30sXG4gICAgICAgICAgICAgICAgdW51c2VkUGFyYW1zID0gX2V4dGVuZHMoe30sIHBhcmFtcyksXG4gICAgICAgICAgICAgICAgdXJsID0gJycsXG4gICAgICAgICAgICAgICAgb3B0aW9uYWwgPSB0cnVlLFxuICAgICAgICAgICAgICAgIGhvc3QgPSAnJztcblxuICAgICAgICAgICAgcm91dGUudG9rZW5zLmZvckVhY2goZnVuY3Rpb24gKHRva2VuKSB7XG4gICAgICAgICAgICAgICAgaWYgKCd0ZXh0JyA9PT0gdG9rZW5bMF0pIHtcbiAgICAgICAgICAgICAgICAgICAgdXJsID0gdG9rZW5bMV0gKyB1cmw7XG4gICAgICAgICAgICAgICAgICAgIG9wdGlvbmFsID0gZmFsc2U7XG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICgndmFyaWFibGUnID09PSB0b2tlblswXSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgaGFzRGVmYXVsdCA9IHJvdXRlLmRlZmF1bHRzICYmIHRva2VuWzNdIGluIHJvdXRlLmRlZmF1bHRzO1xuICAgICAgICAgICAgICAgICAgICBpZiAoZmFsc2UgPT09IG9wdGlvbmFsIHx8ICFoYXNEZWZhdWx0IHx8IHRva2VuWzNdIGluIHBhcmFtcyAmJiBwYXJhbXNbdG9rZW5bM11dICE9IHJvdXRlLmRlZmF1bHRzW3Rva2VuWzNdXSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHZhbHVlID0gdm9pZCAwO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodG9rZW5bM10gaW4gcGFyYW1zKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBwYXJhbXNbdG9rZW5bM11dO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRlbGV0ZSB1bnVzZWRQYXJhbXNbdG9rZW5bM11dO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmIChoYXNEZWZhdWx0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSByb3V0ZS5kZWZhdWx0c1t0b2tlblszXV07XG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKG9wdGlvbmFsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ1RoZSByb3V0ZSBcIicgKyBuYW1lICsgJ1wiIHJlcXVpcmVzIHRoZSBwYXJhbWV0ZXIgXCInICsgdG9rZW5bM10gKyAnXCIuJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBlbXB0eSA9IHRydWUgPT09IHZhbHVlIHx8IGZhbHNlID09PSB2YWx1ZSB8fCAnJyA9PT0gdmFsdWU7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICghZW1wdHkgfHwgIW9wdGlvbmFsKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGVuY29kZWRWYWx1ZSA9IGVuY29kZVVSSUNvbXBvbmVudCh2YWx1ZSkucmVwbGFjZSgvJTJGL2csICcvJyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoJ251bGwnID09PSBlbmNvZGVkVmFsdWUgJiYgbnVsbCA9PT0gdmFsdWUpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZW5jb2RlZFZhbHVlID0gJyc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdXJsID0gdG9rZW5bMV0gKyBlbmNvZGVkVmFsdWUgKyB1cmw7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG9wdGlvbmFsID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoaGFzRGVmYXVsdCAmJiB0b2tlblszXSBpbiB1bnVzZWRQYXJhbXMpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGRlbGV0ZSB1bnVzZWRQYXJhbXNbdG9rZW5bM11dO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignVGhlIHRva2VuIHR5cGUgXCInICsgdG9rZW5bMF0gKyAnXCIgaXMgbm90IHN1cHBvcnRlZC4nKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBpZiAodXJsID09PSAnJykge1xuICAgICAgICAgICAgICAgIHVybCA9ICcvJztcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcm91dGUuaG9zdHRva2Vucy5mb3JFYWNoKGZ1bmN0aW9uICh0b2tlbikge1xuICAgICAgICAgICAgICAgIHZhciB2YWx1ZSA9IHZvaWQgMDtcblxuICAgICAgICAgICAgICAgIGlmICgndGV4dCcgPT09IHRva2VuWzBdKSB7XG4gICAgICAgICAgICAgICAgICAgIGhvc3QgPSB0b2tlblsxXSArIGhvc3Q7XG5cbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICgndmFyaWFibGUnID09PSB0b2tlblswXSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAodG9rZW5bM10gaW4gcGFyYW1zKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IHBhcmFtc1t0b2tlblszXV07XG4gICAgICAgICAgICAgICAgICAgICAgICBkZWxldGUgdW51c2VkUGFyYW1zW3Rva2VuWzNdXTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmIChyb3V0ZS5kZWZhdWx0cyAmJiB0b2tlblszXSBpbiByb3V0ZS5kZWZhdWx0cykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSByb3V0ZS5kZWZhdWx0c1t0b2tlblszXV07XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBob3N0ID0gdG9rZW5bMV0gKyB2YWx1ZSArIGhvc3Q7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAvLyBGb28tYmFyIVxuICAgICAgICAgICAgdXJsID0gdGhpcy5jb250ZXh0Xy5iYXNlX3VybCArIHVybDtcbiAgICAgICAgICAgIGlmIChyb3V0ZS5yZXF1aXJlbWVudHMgJiYgXCJfc2NoZW1lXCIgaW4gcm91dGUucmVxdWlyZW1lbnRzICYmIHRoaXMuZ2V0U2NoZW1lKCkgIT0gcm91dGUucmVxdWlyZW1lbnRzW1wiX3NjaGVtZVwiXSkge1xuICAgICAgICAgICAgICAgIHVybCA9IHJvdXRlLnJlcXVpcmVtZW50c1tcIl9zY2hlbWVcIl0gKyBcIjovL1wiICsgKGhvc3QgfHwgdGhpcy5nZXRIb3N0KCkpICsgdXJsO1xuICAgICAgICAgICAgfSBlbHNlIGlmIChcInVuZGVmaW5lZFwiICE9PSB0eXBlb2Ygcm91dGUuc2NoZW1lcyAmJiBcInVuZGVmaW5lZFwiICE9PSB0eXBlb2Ygcm91dGUuc2NoZW1lc1swXSAmJiB0aGlzLmdldFNjaGVtZSgpICE9PSByb3V0ZS5zY2hlbWVzWzBdKSB7XG4gICAgICAgICAgICAgICAgdXJsID0gcm91dGUuc2NoZW1lc1swXSArIFwiOi8vXCIgKyAoaG9zdCB8fCB0aGlzLmdldEhvc3QoKSkgKyB1cmw7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKGhvc3QgJiYgdGhpcy5nZXRIb3N0KCkgIT09IGhvc3QpIHtcbiAgICAgICAgICAgICAgICB1cmwgPSB0aGlzLmdldFNjaGVtZSgpICsgXCI6Ly9cIiArIGhvc3QgKyB1cmw7XG4gICAgICAgICAgICB9IGVsc2UgaWYgKGFic29sdXRlID09PSB0cnVlKSB7XG4gICAgICAgICAgICAgICAgdXJsID0gdGhpcy5nZXRTY2hlbWUoKSArIFwiOi8vXCIgKyB0aGlzLmdldEhvc3QoKSArIHVybDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKE9iamVjdC5rZXlzKHVudXNlZFBhcmFtcykubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgIHZhciBwcmVmaXggPSB2b2lkIDA7XG4gICAgICAgICAgICAgICAgdmFyIHF1ZXJ5UGFyYW1zID0gW107XG4gICAgICAgICAgICAgICAgdmFyIGFkZCA9IGZ1bmN0aW9uIGFkZChrZXksIHZhbHVlKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vIGlmIHZhbHVlIGlzIGEgZnVuY3Rpb24gdGhlbiBjYWxsIGl0IGFuZCBhc3NpZ24gaXQncyByZXR1cm4gdmFsdWUgYXMgdmFsdWVcbiAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSB0eXBlb2YgdmFsdWUgPT09ICdmdW5jdGlvbicgPyB2YWx1ZSgpIDogdmFsdWU7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gY2hhbmdlIG51bGwgdG8gZW1wdHkgc3RyaW5nXG4gICAgICAgICAgICAgICAgICAgIHZhbHVlID0gdmFsdWUgPT09IG51bGwgPyAnJyA6IHZhbHVlO1xuXG4gICAgICAgICAgICAgICAgICAgIHF1ZXJ5UGFyYW1zLnB1c2goZW5jb2RlVVJJQ29tcG9uZW50KGtleSkgKyAnPScgKyBlbmNvZGVVUklDb21wb25lbnQodmFsdWUpKTtcbiAgICAgICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICAgICAgZm9yIChwcmVmaXggaW4gdW51c2VkUGFyYW1zKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYnVpbGRRdWVyeVBhcmFtcyhwcmVmaXgsIHVudXNlZFBhcmFtc1twcmVmaXhdLCBhZGQpO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHVybCA9IHVybCArICc/JyArIHF1ZXJ5UGFyYW1zLmpvaW4oJyYnKS5yZXBsYWNlKC8lMjAvZywgJysnKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuIHVybDtcbiAgICAgICAgfVxuICAgIH1dLCBbe1xuICAgICAgICBrZXk6ICdnZXRJbnN0YW5jZScsXG4gICAgICAgIHZhbHVlOiBmdW5jdGlvbiBnZXRJbnN0YW5jZSgpIHtcbiAgICAgICAgICAgIHJldHVybiBSb3V0aW5nO1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIENvbmZpZ3VyZXMgdGhlIGN1cnJlbnQgUm91dGVyIGluc3RhbmNlIHdpdGggdGhlIHByb3ZpZGVkIGRhdGEuXG4gICAgICAgICAqIEBwYXJhbSB7T2JqZWN0fSBkYXRhXG4gICAgICAgICAqL1xuXG4gICAgfSwge1xuICAgICAgICBrZXk6ICdzZXREYXRhJyxcbiAgICAgICAgdmFsdWU6IGZ1bmN0aW9uIHNldERhdGEoZGF0YSkge1xuICAgICAgICAgICAgdmFyIHJvdXRlciA9IFJvdXRlci5nZXRJbnN0YW5jZSgpO1xuXG4gICAgICAgICAgICByb3V0ZXIuc2V0Um91dGluZ0RhdGEoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9XSk7XG5cbiAgICByZXR1cm4gUm91dGVyO1xufSgpO1xuXG4vKipcbiAqIEB0eXBlZGVmIHt7XG4gKiAgICAgdG9rZW5zOiAoQXJyYXkuPEFycmF5LjxzdHJpbmc+PiksXG4gKiAgICAgZGVmYXVsdHM6IChPYmplY3QuPHN0cmluZywgc3RyaW5nPiksXG4gKiAgICAgcmVxdWlyZW1lbnRzOiBPYmplY3QsXG4gKiAgICAgaG9zdHRva2VuczogKEFycmF5LjxzdHJpbmc+KVxuICogfX1cbiAqL1xuXG5cblJvdXRlci5Sb3V0ZTtcblxuLyoqXG4gKiBAdHlwZWRlZiB7e1xuICogICAgIGJhc2VfdXJsOiAoc3RyaW5nKVxuICogfX1cbiAqL1xuUm91dGVyLkNvbnRleHQ7XG5cbi8qKlxuICogUm91dGVyIHNpbmdsZXRvbi5cbiAqIEBjb25zdFxuICogQHR5cGUge1JvdXRlcn1cbiAqL1xudmFyIFJvdXRpbmcgPSBuZXcgUm91dGVyKCk7XG5cbiAgICByZXR1cm4geyBSb3V0ZXI6IFJvdXRlciwgUm91dGluZzogUm91dGluZyB9O1xufSkpO1xuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3ZlbmRvci9mcmllbmRzb2ZzeW1mb255L2pzcm91dGluZy1idW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9yb3V0ZXIuanMiXSwic291cmNlUm9vdCI6IiJ9