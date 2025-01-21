/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/global/custom-cursor.js":
/*!*******************************************!*\
  !*** ./assets/js/global/custom-cursor.js ***!
  \*******************************************/
/***/ (function() {

eval("jQuery(document).ready(function ($) {\n  var cursor = document.querySelector('.custom-cursor');\n  var isTouchDevice = function isTouchDevice() {\n    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;\n  };\n  if (isTouchDevice()) {\n    cursor.style.display = 'none';\n    return;\n  }\n\n  // Track mouse movement\n  document.addEventListener('mousemove', function (e) {\n    cursor.style.left = \"\".concat(e.clientX, \"px\");\n    cursor.style.top = \"\".concat(e.clientY, \"px\");\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwiJCIsImN1cnNvciIsInF1ZXJ5U2VsZWN0b3IiLCJpc1RvdWNoRGV2aWNlIiwid2luZG93IiwibmF2aWdhdG9yIiwibWF4VG91Y2hQb2ludHMiLCJtc01heFRvdWNoUG9pbnRzIiwic3R5bGUiLCJkaXNwbGF5IiwiYWRkRXZlbnRMaXN0ZW5lciIsImUiLCJsZWZ0IiwiY29uY2F0IiwiY2xpZW50WCIsInRvcCIsImNsaWVudFkiXSwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2N1c3RvbS1jdXJzb3IuanM/OGE5ZiJdLCJzb3VyY2VzQ29udGVudCI6WyJqUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCQpIHtcclxuICAgIGNvbnN0IGN1cnNvciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jdXN0b20tY3Vyc29yJyk7XHJcblxyXG4gICAgY29uc3QgaXNUb3VjaERldmljZSA9ICgpID0+IHtcclxuICAgICAgICByZXR1cm4gJ29udG91Y2hzdGFydCcgaW4gd2luZG93IHx8IG5hdmlnYXRvci5tYXhUb3VjaFBvaW50cyA+IDAgfHwgbmF2aWdhdG9yLm1zTWF4VG91Y2hQb2ludHMgPiAwO1xyXG4gICAgfTtcclxuXHJcbiAgICBpZiAoaXNUb3VjaERldmljZSgpKSB7XHJcbiAgICAgICAgY3Vyc29yLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG5cclxuICAgIC8vIFRyYWNrIG1vdXNlIG1vdmVtZW50XHJcbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdtb3VzZW1vdmUnLCAoZSkgPT4ge1xyXG4gICAgICAgIGN1cnNvci5zdHlsZS5sZWZ0ID0gYCR7ZS5jbGllbnRYfXB4YDtcclxuICAgICAgICBjdXJzb3Iuc3R5bGUudG9wID0gYCR7ZS5jbGllbnRZfXB4YDtcclxuICAgIH0pO1xyXG59KTtcclxuIl0sIm1hcHBpbmdzIjoiQUFBQUEsTUFBTSxDQUFDQyxRQUFRLENBQUMsQ0FBQ0MsS0FBSyxDQUFDLFVBQVNDLENBQUMsRUFBRTtFQUMvQixJQUFNQyxNQUFNLEdBQUdILFFBQVEsQ0FBQ0ksYUFBYSxDQUFDLGdCQUFnQixDQUFDO0VBRXZELElBQU1DLGFBQWEsR0FBRyxTQUFoQkEsYUFBYUEsQ0FBQSxFQUFTO0lBQ3hCLE9BQU8sY0FBYyxJQUFJQyxNQUFNLElBQUlDLFNBQVMsQ0FBQ0MsY0FBYyxHQUFHLENBQUMsSUFBSUQsU0FBUyxDQUFDRSxnQkFBZ0IsR0FBRyxDQUFDO0VBQ3JHLENBQUM7RUFFRCxJQUFJSixhQUFhLENBQUMsQ0FBQyxFQUFFO0lBQ2pCRixNQUFNLENBQUNPLEtBQUssQ0FBQ0MsT0FBTyxHQUFHLE1BQU07SUFDN0I7RUFDSjs7RUFFQTtFQUNBWCxRQUFRLENBQUNZLGdCQUFnQixDQUFDLFdBQVcsRUFBRSxVQUFDQyxDQUFDLEVBQUs7SUFDMUNWLE1BQU0sQ0FBQ08sS0FBSyxDQUFDSSxJQUFJLE1BQUFDLE1BQUEsQ0FBTUYsQ0FBQyxDQUFDRyxPQUFPLE9BQUk7SUFDcENiLE1BQU0sQ0FBQ08sS0FBSyxDQUFDTyxHQUFHLE1BQUFGLE1BQUEsQ0FBTUYsQ0FBQyxDQUFDSyxPQUFPLE9BQUk7RUFDdkMsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDIiwiaWdub3JlTGlzdCI6W10sImZpbGUiOiIuL2Fzc2V0cy9qcy9nbG9iYWwvY3VzdG9tLWN1cnNvci5qcyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/js/global/custom-cursor.js\n");

/***/ }),

/***/ "./assets/js/global/load-at-top.js":
/*!*****************************************!*\
  !*** ./assets/js/global/load-at-top.js ***!
  \*****************************************/
/***/ (function() {

eval("document.addEventListener('DOMContentLoaded', function () {\n  if ('scrollRestoration' in history) {\n    history.scrollRestoration = 'manual';\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJoaXN0b3J5Iiwic2Nyb2xsUmVzdG9yYXRpb24iXSwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2xvYWQtYXQtdG9wLmpzPzg4MmUiXSwic291cmNlc0NvbnRlbnQiOlsiZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uICgpIHtcclxuICAgIGlmICgnc2Nyb2xsUmVzdG9yYXRpb24nIGluIGhpc3RvcnkpIHtcclxuICAgICAgICBoaXN0b3J5LnNjcm9sbFJlc3RvcmF0aW9uID0gJ21hbnVhbCc7XHJcbiAgICB9XHJcbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUFBLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsa0JBQWtCLEVBQUUsWUFBWTtFQUN0RCxJQUFJLG1CQUFtQixJQUFJQyxPQUFPLEVBQUU7SUFDaENBLE9BQU8sQ0FBQ0MsaUJBQWlCLEdBQUcsUUFBUTtFQUN4QztBQUNKLENBQUMsQ0FBQyIsImlnbm9yZUxpc3QiOltdLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL2xvYWQtYXQtdG9wLmpzIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./assets/js/global/load-at-top.js\n");

/***/ }),

/***/ "./assets/css/app.scss":
/*!*****************************!*\
  !*** ./assets/css/app.scss ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvY3NzL2FwcC5zY3NzIiwibWFwcGluZ3MiOiI7QUFBQSIsInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz9kMTkxIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/css/app.scss\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/global": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkvulkancreative"] = self["webpackChunkvulkancreative"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/load-at-top.js"); })
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/custom-cursor.js"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/css/app.scss"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;