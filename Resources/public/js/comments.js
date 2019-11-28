/**
 * ShkComments
 * @version 1.0.0
 * @author Andchir<andchir@gmail.com>
 */

(function (factory) {

    if ( typeof define === 'function' && define.amd ) {

        // AMD. Register as an anonymous module.
        define([], factory);

    } else if ( typeof exports === 'object' ) {

        // Node/CommonJS
        module.exports = factory();

    } else {

        // Browser globals
        window.ShkComments = factory();
    }

}(function( ){

    'use strict';

    /**
     * class ShkComments
     * @param options
     * @constructor
     */
    function ShkComments (options) {
        const self = this;
        let isInitialized = false, mainOptions = {}, container;

        const defaultOptions = {
            baseUrl: '/',
            threadId: 0,
            selector: '#shk-comments',
            loadingClass: 'loading',
            onAddSuccess: function(data) {
                if (data.form) {
                    container.querySelector('form').outerHTML = data.form;
                    self.formSubmitInit();
                }
            },
            onAddFail: function(data) {
                if (data.form) {
                    container.querySelector('form').outerHTML = data.form;
                    self.formSubmitInit();
                } else if (data.error) {
                    alert(data.error);
                }
            }
        };

        Object.assign(mainOptions, defaultOptions, options);

        this.init = function() {
            container = document.querySelector(mainOptions.selector);
            if (!container) {
                return;
            }
            this.getThreadHtml();
            isInitialized = true;
        };

        /**
         * Get comments thread HTML
         * @param {function} callbackFunc
         */
        this.getThreadHtml = function(callbackFunc) {
            self.showLoading(true);
            const url = mainOptions.baseUrl + '/' + mainOptions.threadId;
            this.ajax(url, {}, function(res) {
                container.innerHTML = res;
                self.formSubmitInit();
                self.showLoading(false);
                if (typeof callbackFunc === 'function') {
                    callbackFunc();
                }
            }, function() {
                self.showLoading(false);
            });
        };

        /**
         * Get container element
         * @returns {HTMLElement}
         */
        this.getContainer = function() {
            return container;
        };

        /**
         * Form submit initialize
         */
        this.formSubmitInit = function() {
            if (!container) {
                return;
            }
            const formEl = container.querySelector('form');
            if (!formEl) {
                return;
            }
            formEl.addEventListener('submit', this.onFormSubmit.bind(this));
        };

        /**
         * On form submit
         * @param e
         */
        this.onFormSubmit = function(e) {
            e.preventDefault();

            const url = mainOptions.baseUrl + '/add';
            const formData = new FormData(e.target);
            const buttonEl = container.querySelector('button[type="submit"]');
            if (buttonEl) {
                buttonEl.disabled = true;
            }

            self.showLoading(true);
            this.ajax(url, formData, function(res) {
                self.showLoading(false);
                mainOptions.onAddSuccess(res);
                if (buttonEl) {
                    buttonEl.disabled = false;
                }
            }, function(err) {
                self.showLoading(false);
                mainOptions.onAddFail(err);
                if (buttonEl) {
                    buttonEl.disabled = false;
                }
            }, 'POST');
        };

        /**
         * Show loading animation
         * @param {boolean} show
         */
        this.showLoading = function(show) {
            if (!container) {
                return;
            }
            show = show || false;
            if (show) {
                container.classList.add(mainOptions.loadingClass);
            } else {
                container.classList.remove(mainOptions.loadingClass);
            }
        };

        /**
         * Ajax request
         * @param {string} url
         * @param {object} data
         * @param {function} successFn
         * @param {function} failFn
         * @param {string} method
         */
        this.ajax = function(url, data, successFn, failFn, method) {
            method = method || 'GET';
            const request = new XMLHttpRequest();
            request.open(method, url, true);

            request.onload = function() {
                const result = ['{','['].indexOf(request.responseText.substr(0,1)) > -1
                    ? JSON.parse(request.responseText)
                    : request.responseText;
                if (request.status >= 200 && request.status < 400) {
                    if (typeof successFn === 'function') {
                        successFn(result);
                    }
                } else {
                    if (typeof failFn === 'function') {
                        failFn(result);
                    }
                }
            };

            request.onerror = function() {
                if (typeof failFn === 'function') {
                    failFn(request);
                }
            };

            request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            if (!(data instanceof FormData)) {
                // request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            }
            if (method === 'POST') {
                request.send(data);
            } else {
                request.send();
            }
        };

        /**
         * Run callback after DOM ready
         * @param cb
         */
        this.onReady = function(cb) {
            if (document.readyState !== 'loading') {
                cb();
            } else {
                document.addEventListener('DOMContentLoaded', cb);
            }
        };

        this.onReady(this.init.bind(this));
    }

    return ShkComments;
}));
