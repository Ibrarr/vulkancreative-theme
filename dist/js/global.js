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

/***/ "./assets/css/app.scss":
/*!*****************************!*\
  !*** ./assets/css/app.scss ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvY3NzL2FwcC5zY3NzIiwibWFwcGluZ3MiOiI7QUFBQSIsInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz9kMTkxIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/css/app.scss\n\n}");

/***/ }),

/***/ "./assets/js/global/custom-cursor.js":
/*!*******************************************!*\
  !*** ./assets/js/global/custom-cursor.js ***!
  \*******************************************/
/***/ (function() {

eval("{jQuery(document).ready(function ($) {\n  var cursor = document.querySelector('.custom-cursor');\n  var isTouchDevice = function isTouchDevice() {\n    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;\n  };\n  if (isTouchDevice()) {\n    cursor.style.display = 'none';\n    return;\n  }\n\n  // Track mouse movement\n  document.addEventListener('mousemove', function (e) {\n    cursor.style.left = \"\".concat(e.clientX, \"px\");\n    cursor.style.top = \"\".concat(e.clientY, \"px\");\n  });\n});\njQuery(function ($) {\n  $(document).on('gform_post_render gform_confirmation_loaded gform_page_loaded', function () {\n    var $cursor = $('.custom-cursor');\n    if (!$cursor.length) {\n      $cursor = $('<div class=\"custom-cursor\"></div>');\n    }\n    $('body').append($cursor);\n    $cursor.removeClass('hidden').show();\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL2N1c3RvbS1jdXJzb3IuanMiLCJuYW1lcyI6WyJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwiJCIsImN1cnNvciIsInF1ZXJ5U2VsZWN0b3IiLCJpc1RvdWNoRGV2aWNlIiwid2luZG93IiwibmF2aWdhdG9yIiwibWF4VG91Y2hQb2ludHMiLCJtc01heFRvdWNoUG9pbnRzIiwic3R5bGUiLCJkaXNwbGF5IiwiYWRkRXZlbnRMaXN0ZW5lciIsImUiLCJsZWZ0IiwiY29uY2F0IiwiY2xpZW50WCIsInRvcCIsImNsaWVudFkiLCJvbiIsIiRjdXJzb3IiLCJsZW5ndGgiLCJhcHBlbmQiLCJyZW1vdmVDbGFzcyIsInNob3ciXSwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2pzL2dsb2JhbC9jdXN0b20tY3Vyc29yLmpzPzhhOWYiXSwic291cmNlc0NvbnRlbnQiOlsialF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigkKSB7XG4gICAgY29uc3QgY3Vyc29yID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmN1c3RvbS1jdXJzb3InKTtcblxuICAgIGNvbnN0IGlzVG91Y2hEZXZpY2UgPSAoKSA9PiB7XG4gICAgICAgIHJldHVybiAnb250b3VjaHN0YXJ0JyBpbiB3aW5kb3cgfHwgbmF2aWdhdG9yLm1heFRvdWNoUG9pbnRzID4gMCB8fCBuYXZpZ2F0b3IubXNNYXhUb3VjaFBvaW50cyA+IDA7XG4gICAgfTtcblxuICAgIGlmIChpc1RvdWNoRGV2aWNlKCkpIHtcbiAgICAgICAgY3Vyc29yLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICAvLyBUcmFjayBtb3VzZSBtb3ZlbWVudFxuICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ21vdXNlbW92ZScsIChlKSA9PiB7XG4gICAgICAgIGN1cnNvci5zdHlsZS5sZWZ0ID0gYCR7ZS5jbGllbnRYfXB4YDtcbiAgICAgICAgY3Vyc29yLnN0eWxlLnRvcCA9IGAke2UuY2xpZW50WX1weGA7XG4gICAgfSk7XG59KTtcblxualF1ZXJ5KGZ1bmN0aW9uICgkKSB7XG4gICAgJChkb2N1bWVudCkub24oJ2dmb3JtX3Bvc3RfcmVuZGVyIGdmb3JtX2NvbmZpcm1hdGlvbl9sb2FkZWQgZ2Zvcm1fcGFnZV9sb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgbGV0ICRjdXJzb3IgPSAkKCcuY3VzdG9tLWN1cnNvcicpO1xuICAgICAgICBpZiAoISRjdXJzb3IubGVuZ3RoKSB7XG4gICAgICAgICAgICAkY3Vyc29yID0gJCgnPGRpdiBjbGFzcz1cImN1c3RvbS1jdXJzb3JcIj48L2Rpdj4nKTtcbiAgICAgICAgfVxuXG4gICAgICAgICQoJ2JvZHknKS5hcHBlbmQoJGN1cnNvcik7XG5cbiAgICAgICAgJGN1cnNvci5yZW1vdmVDbGFzcygnaGlkZGVuJykuc2hvdygpO1xuICAgIH0pO1xufSk7XG4iXSwibWFwcGluZ3MiOiJBQUFBQSxNQUFNLENBQUNDLFFBQVEsQ0FBQyxDQUFDQyxLQUFLLENBQUMsVUFBU0MsQ0FBQyxFQUFFO0VBQy9CLElBQU1DLE1BQU0sR0FBR0gsUUFBUSxDQUFDSSxhQUFhLENBQUMsZ0JBQWdCLENBQUM7RUFFdkQsSUFBTUMsYUFBYSxHQUFHLFNBQWhCQSxhQUFhQSxDQUFBLEVBQVM7SUFDeEIsT0FBTyxjQUFjLElBQUlDLE1BQU0sSUFBSUMsU0FBUyxDQUFDQyxjQUFjLEdBQUcsQ0FBQyxJQUFJRCxTQUFTLENBQUNFLGdCQUFnQixHQUFHLENBQUM7RUFDckcsQ0FBQztFQUVELElBQUlKLGFBQWEsQ0FBQyxDQUFDLEVBQUU7SUFDakJGLE1BQU0sQ0FBQ08sS0FBSyxDQUFDQyxPQUFPLEdBQUcsTUFBTTtJQUM3QjtFQUNKOztFQUVBO0VBQ0FYLFFBQVEsQ0FBQ1ksZ0JBQWdCLENBQUMsV0FBVyxFQUFFLFVBQUNDLENBQUMsRUFBSztJQUMxQ1YsTUFBTSxDQUFDTyxLQUFLLENBQUNJLElBQUksTUFBQUMsTUFBQSxDQUFNRixDQUFDLENBQUNHLE9BQU8sT0FBSTtJQUNwQ2IsTUFBTSxDQUFDTyxLQUFLLENBQUNPLEdBQUcsTUFBQUYsTUFBQSxDQUFNRixDQUFDLENBQUNLLE9BQU8sT0FBSTtFQUN2QyxDQUFDLENBQUM7QUFDTixDQUFDLENBQUM7QUFFRm5CLE1BQU0sQ0FBQyxVQUFVRyxDQUFDLEVBQUU7RUFDaEJBLENBQUMsQ0FBQ0YsUUFBUSxDQUFDLENBQUNtQixFQUFFLENBQUMsK0RBQStELEVBQUUsWUFBWTtJQUV4RixJQUFJQyxPQUFPLEdBQUdsQixDQUFDLENBQUMsZ0JBQWdCLENBQUM7SUFDakMsSUFBSSxDQUFDa0IsT0FBTyxDQUFDQyxNQUFNLEVBQUU7TUFDakJELE9BQU8sR0FBR2xCLENBQUMsQ0FBQyxtQ0FBbUMsQ0FBQztJQUNwRDtJQUVBQSxDQUFDLENBQUMsTUFBTSxDQUFDLENBQUNvQixNQUFNLENBQUNGLE9BQU8sQ0FBQztJQUV6QkEsT0FBTyxDQUFDRyxXQUFXLENBQUMsUUFBUSxDQUFDLENBQUNDLElBQUksQ0FBQyxDQUFDO0VBQ3hDLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQyIsImlnbm9yZUxpc3QiOltdfQ==\n//# sourceURL=webpack-internal:///./assets/js/global/custom-cursor.js\n\n}");

/***/ }),

/***/ "./assets/js/global/dark-mode.js":
/*!***************************************!*\
  !*** ./assets/js/global/dark-mode.js ***!
  \***************************************/
/***/ (function() {

eval("{document.addEventListener('DOMContentLoaded', function () {\n  var toggleBtns = document.querySelectorAll('.theme-toggle');\n  var body = document.body;\n\n  // Load user's theme preference from localStorage\n  if (localStorage.getItem('darkMode') === 'enabled') {\n    body.classList.add('dark-mode');\n    toggleBtns.forEach(function (btn) {\n      return btn.classList.add('theme-toggle--toggled');\n    });\n  }\n  toggleBtns.forEach(function (btn) {\n    btn.addEventListener('click', function () {\n      // Toggle dark mode on <body>\n      body.classList.toggle('dark-mode');\n\n      // Persist dark-mode preference\n      localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');\n\n      // Update all toggle buttons' appearance\n      toggleBtns.forEach(function (b) {\n        return b.classList.toggle('theme-toggle--toggled');\n      });\n    });\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL2RhcmstbW9kZS5qcyIsIm5hbWVzIjpbImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsInRvZ2dsZUJ0bnMiLCJxdWVyeVNlbGVjdG9yQWxsIiwiYm9keSIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJjbGFzc0xpc3QiLCJhZGQiLCJmb3JFYWNoIiwiYnRuIiwidG9nZ2xlIiwic2V0SXRlbSIsImNvbnRhaW5zIiwiYiJdLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2RhcmstbW9kZS5qcz82MThhIl0sInNvdXJjZXNDb250ZW50IjpbImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XG4gICAgY29uc3QgdG9nZ2xlQnRucyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50aGVtZS10b2dnbGUnKTtcbiAgICBjb25zdCBib2R5ID0gZG9jdW1lbnQuYm9keTtcblxuICAgIC8vIExvYWQgdXNlcidzIHRoZW1lIHByZWZlcmVuY2UgZnJvbSBsb2NhbFN0b3JhZ2VcbiAgICBpZiAobG9jYWxTdG9yYWdlLmdldEl0ZW0oJ2RhcmtNb2RlJykgPT09ICdlbmFibGVkJykge1xuICAgICAgICBib2R5LmNsYXNzTGlzdC5hZGQoJ2RhcmstbW9kZScpO1xuICAgICAgICB0b2dnbGVCdG5zLmZvckVhY2goYnRuID0+IGJ0bi5jbGFzc0xpc3QuYWRkKCd0aGVtZS10b2dnbGUtLXRvZ2dsZWQnKSk7XG4gICAgfVxuXG4gICAgdG9nZ2xlQnRucy5mb3JFYWNoKGJ0biA9PiB7XG4gICAgICAgIGJ0bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgICAgIC8vIFRvZ2dsZSBkYXJrIG1vZGUgb24gPGJvZHk+XG4gICAgICAgICAgICBib2R5LmNsYXNzTGlzdC50b2dnbGUoJ2RhcmstbW9kZScpO1xuXG4gICAgICAgICAgICAvLyBQZXJzaXN0IGRhcmstbW9kZSBwcmVmZXJlbmNlXG4gICAgICAgICAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbSgnZGFya01vZGUnLCBib2R5LmNsYXNzTGlzdC5jb250YWlucygnZGFyay1tb2RlJykgPyAnZW5hYmxlZCcgOiAnZGlzYWJsZWQnKTtcblxuICAgICAgICAgICAgLy8gVXBkYXRlIGFsbCB0b2dnbGUgYnV0dG9ucycgYXBwZWFyYW5jZVxuICAgICAgICAgICAgdG9nZ2xlQnRucy5mb3JFYWNoKGIgPT4gYi5jbGFzc0xpc3QudG9nZ2xlKCd0aGVtZS10b2dnbGUtLXRvZ2dsZWQnKSk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG4iXSwibWFwcGluZ3MiOiJBQUFBQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQU07RUFDaEQsSUFBTUMsVUFBVSxHQUFHRixRQUFRLENBQUNHLGdCQUFnQixDQUFDLGVBQWUsQ0FBQztFQUM3RCxJQUFNQyxJQUFJLEdBQUdKLFFBQVEsQ0FBQ0ksSUFBSTs7RUFFMUI7RUFDQSxJQUFJQyxZQUFZLENBQUNDLE9BQU8sQ0FBQyxVQUFVLENBQUMsS0FBSyxTQUFTLEVBQUU7SUFDaERGLElBQUksQ0FBQ0csU0FBUyxDQUFDQyxHQUFHLENBQUMsV0FBVyxDQUFDO0lBQy9CTixVQUFVLENBQUNPLE9BQU8sQ0FBQyxVQUFBQyxHQUFHO01BQUEsT0FBSUEsR0FBRyxDQUFDSCxTQUFTLENBQUNDLEdBQUcsQ0FBQyx1QkFBdUIsQ0FBQztJQUFBLEVBQUM7RUFDekU7RUFFQU4sVUFBVSxDQUFDTyxPQUFPLENBQUMsVUFBQUMsR0FBRyxFQUFJO0lBQ3RCQSxHQUFHLENBQUNULGdCQUFnQixDQUFDLE9BQU8sRUFBRSxZQUFNO01BQ2hDO01BQ0FHLElBQUksQ0FBQ0csU0FBUyxDQUFDSSxNQUFNLENBQUMsV0FBVyxDQUFDOztNQUVsQztNQUNBTixZQUFZLENBQUNPLE9BQU8sQ0FBQyxVQUFVLEVBQUVSLElBQUksQ0FBQ0csU0FBUyxDQUFDTSxRQUFRLENBQUMsV0FBVyxDQUFDLEdBQUcsU0FBUyxHQUFHLFVBQVUsQ0FBQzs7TUFFL0Y7TUFDQVgsVUFBVSxDQUFDTyxPQUFPLENBQUMsVUFBQUssQ0FBQztRQUFBLE9BQUlBLENBQUMsQ0FBQ1AsU0FBUyxDQUFDSSxNQUFNLENBQUMsdUJBQXVCLENBQUM7TUFBQSxFQUFDO0lBQ3hFLENBQUMsQ0FBQztFQUNOLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQyIsImlnbm9yZUxpc3QiOltdfQ==\n//# sourceURL=webpack-internal:///./assets/js/global/dark-mode.js\n\n}");

/***/ }),

/***/ "./assets/js/global/load-at-top.js":
/*!*****************************************!*\
  !*** ./assets/js/global/load-at-top.js ***!
  \*****************************************/
/***/ (function() {

eval("{document.addEventListener('DOMContentLoaded', function () {\n  if ('scrollRestoration' in history) {\n    history.scrollRestoration = 'manual';\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL2xvYWQtYXQtdG9wLmpzIiwibmFtZXMiOlsiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwiaGlzdG9yeSIsInNjcm9sbFJlc3RvcmF0aW9uIl0sInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly92dWxrYW5jcmVhdGl2ZS8uL2Fzc2V0cy9qcy9nbG9iYWwvbG9hZC1hdC10b3AuanM/ODgyZSJdLCJzb3VyY2VzQ29udGVudCI6WyJkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24gKCkge1xuICAgIGlmICgnc2Nyb2xsUmVzdG9yYXRpb24nIGluIGhpc3RvcnkpIHtcbiAgICAgICAgaGlzdG9yeS5zY3JvbGxSZXN0b3JhdGlvbiA9ICdtYW51YWwnO1xuICAgIH1cbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUFBLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsa0JBQWtCLEVBQUUsWUFBWTtFQUN0RCxJQUFJLG1CQUFtQixJQUFJQyxPQUFPLEVBQUU7SUFDaENBLE9BQU8sQ0FBQ0MsaUJBQWlCLEdBQUcsUUFBUTtFQUN4QztBQUNKLENBQUMsQ0FBQyIsImlnbm9yZUxpc3QiOltdfQ==\n//# sourceURL=webpack-internal:///./assets/js/global/load-at-top.js\n\n}");

/***/ }),

/***/ "./assets/js/global/remove-anchor-from-url.js":
/*!****************************************************!*\
  !*** ./assets/js/global/remove-anchor-from-url.js ***!
  \****************************************************/
/***/ (function() {

eval("{document.addEventListener('DOMContentLoaded', function () {\n  document.querySelectorAll('a[href^=\"#\"]').forEach(function (link) {\n    link.addEventListener('click', function (e) {\n      var id = link.getAttribute('href').slice(1); // \"why\"\n      var target = document.getElementById(id);\n      if (target) {\n        e.preventDefault(); // stop the hash appearing\n        target.scrollIntoView({\n          behaviour: 'smooth'\n        });\n        history.replaceState(null, '', '/'); // tidy URL → https://vulkancreative.test\n      }\n    });\n  });\n  if (window.location.hash) {\n    var target = document.querySelector(window.location.hash);\n    if (target) {\n      setTimeout(function () {\n        target.scrollIntoView({\n          behavior: 'smooth'\n        });\n        history.replaceState(null, '', '/');\n      }, 10);\n    }\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL3JlbW92ZS1hbmNob3ItZnJvbS11cmwuanMiLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9yRWFjaCIsImxpbmsiLCJlIiwiaWQiLCJnZXRBdHRyaWJ1dGUiLCJzbGljZSIsInRhcmdldCIsImdldEVsZW1lbnRCeUlkIiwicHJldmVudERlZmF1bHQiLCJzY3JvbGxJbnRvVmlldyIsImJlaGF2aW91ciIsImhpc3RvcnkiLCJyZXBsYWNlU3RhdGUiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhhc2giLCJxdWVyeVNlbGVjdG9yIiwic2V0VGltZW91dCIsImJlaGF2aW9yIl0sInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly92dWxrYW5jcmVhdGl2ZS8uL2Fzc2V0cy9qcy9nbG9iYWwvcmVtb3ZlLWFuY2hvci1mcm9tLXVybC5qcz8yYzUzIl0sInNvdXJjZXNDb250ZW50IjpbImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnYVtocmVmXj1cIiNcIl0nKS5mb3JFYWNoKGxpbmsgPT4ge1xuICAgICAgICBsaW5rLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZSA9PiB7XG4gICAgICAgICAgICBjb25zdCBpZCA9IGxpbmsuZ2V0QXR0cmlidXRlKCdocmVmJykuc2xpY2UoMSk7ICAgLy8gXCJ3aHlcIlxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoaWQpO1xuICAgICAgICAgICAgaWYgKHRhcmdldCkge1xuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTsgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIHN0b3AgdGhlIGhhc2ggYXBwZWFyaW5nXG4gICAgICAgICAgICAgICAgdGFyZ2V0LnNjcm9sbEludG9WaWV3KHsgYmVoYXZpb3VyOiAnc21vb3RoJyB9KTtcbiAgICAgICAgICAgICAgICBoaXN0b3J5LnJlcGxhY2VTdGF0ZShudWxsLCAnJywgJy8nKTsgICAgICAgICAvLyB0aWR5IFVSTCDihpIgaHR0cHM6Ly92dWxrYW5jcmVhdGl2ZS50ZXN0XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0pO1xuXG4gICAgaWYgKHdpbmRvdy5sb2NhdGlvbi5oYXNoKSB7XG4gICAgICAgIGNvbnN0IHRhcmdldCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Iod2luZG93LmxvY2F0aW9uLmhhc2gpO1xuICAgICAgICBpZiAodGFyZ2V0KSB7XG4gICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgICAgICB0YXJnZXQuc2Nyb2xsSW50b1ZpZXcoeyBiZWhhdmlvcjogJ3Ntb290aCcgfSk7XG4gICAgICAgICAgICAgICAgaGlzdG9yeS5yZXBsYWNlU3RhdGUobnVsbCwgJycsICcvJyk7XG4gICAgICAgICAgICB9LCAxMCk7XG4gICAgICAgIH1cbiAgICB9XG59KTsiXSwibWFwcGluZ3MiOiJBQUFBQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQVk7RUFDdERELFFBQVEsQ0FBQ0UsZ0JBQWdCLENBQUMsY0FBYyxDQUFDLENBQUNDLE9BQU8sQ0FBQyxVQUFBQyxJQUFJLEVBQUk7SUFDdERBLElBQUksQ0FBQ0gsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFVBQUFJLENBQUMsRUFBSTtNQUNoQyxJQUFNQyxFQUFFLEdBQUdGLElBQUksQ0FBQ0csWUFBWSxDQUFDLE1BQU0sQ0FBQyxDQUFDQyxLQUFLLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBRztNQUNqRCxJQUFNQyxNQUFNLEdBQUdULFFBQVEsQ0FBQ1UsY0FBYyxDQUFDSixFQUFFLENBQUM7TUFDMUMsSUFBSUcsTUFBTSxFQUFFO1FBQ1JKLENBQUMsQ0FBQ00sY0FBYyxDQUFDLENBQUMsQ0FBQyxDQUEwQjtRQUM3Q0YsTUFBTSxDQUFDRyxjQUFjLENBQUM7VUFBRUMsU0FBUyxFQUFFO1FBQVMsQ0FBQyxDQUFDO1FBQzlDQyxPQUFPLENBQUNDLFlBQVksQ0FBQyxJQUFJLEVBQUUsRUFBRSxFQUFFLEdBQUcsQ0FBQyxDQUFDLENBQVM7TUFDakQ7SUFDSixDQUFDLENBQUM7RUFDTixDQUFDLENBQUM7RUFFRixJQUFJQyxNQUFNLENBQUNDLFFBQVEsQ0FBQ0MsSUFBSSxFQUFFO0lBQ3RCLElBQU1ULE1BQU0sR0FBR1QsUUFBUSxDQUFDbUIsYUFBYSxDQUFDSCxNQUFNLENBQUNDLFFBQVEsQ0FBQ0MsSUFBSSxDQUFDO0lBQzNELElBQUlULE1BQU0sRUFBRTtNQUNSVyxVQUFVLENBQUMsWUFBTTtRQUNiWCxNQUFNLENBQUNHLGNBQWMsQ0FBQztVQUFFUyxRQUFRLEVBQUU7UUFBUyxDQUFDLENBQUM7UUFDN0NQLE9BQU8sQ0FBQ0MsWUFBWSxDQUFDLElBQUksRUFBRSxFQUFFLEVBQUUsR0FBRyxDQUFDO01BQ3ZDLENBQUMsRUFBRSxFQUFFLENBQUM7SUFDVjtFQUNKO0FBQ0osQ0FBQyxDQUFDIiwiaWdub3JlTGlzdCI6W119\n//# sourceURL=webpack-internal:///./assets/js/global/remove-anchor-from-url.js\n\n}");

/***/ }),

/***/ "./assets/js/global/smooth-scrolling.js":
/*!**********************************************!*\
  !*** ./assets/js/global/smooth-scrolling.js ***!
  \**********************************************/
/***/ (function() {

eval("{// import gsap from 'gsap';\n// import { ScrollSmoother } from 'gsap/ScrollSmoother';\n// import { ScrollTrigger } from 'gsap/ScrollTrigger';\n//\n// gsap.registerPlugin(ScrollTrigger, ScrollSmoother);\n//\n// ScrollSmoother.create({\n//     smooth: 1,\n//     effects: true,\n//     smoothTouch: 0.1,\n// });//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL3Ntb290aC1zY3JvbGxpbmcuanMiLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly92dWxrYW5jcmVhdGl2ZS8uL2Fzc2V0cy9qcy9nbG9iYWwvc21vb3RoLXNjcm9sbGluZy5qcz81NDJmIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGltcG9ydCBnc2FwIGZyb20gJ2dzYXAnO1xuLy8gaW1wb3J0IHsgU2Nyb2xsU21vb3RoZXIgfSBmcm9tICdnc2FwL1Njcm9sbFNtb290aGVyJztcbi8vIGltcG9ydCB7IFNjcm9sbFRyaWdnZXIgfSBmcm9tICdnc2FwL1Njcm9sbFRyaWdnZXInO1xuLy9cbi8vIGdzYXAucmVnaXN0ZXJQbHVnaW4oU2Nyb2xsVHJpZ2dlciwgU2Nyb2xsU21vb3RoZXIpO1xuLy9cbi8vIFNjcm9sbFNtb290aGVyLmNyZWF0ZSh7XG4vLyAgICAgc21vb3RoOiAxLFxuLy8gICAgIGVmZmVjdHM6IHRydWUsXG4vLyAgICAgc21vb3RoVG91Y2g6IDAuMSxcbi8vIH0pO1xuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwiaWdub3JlTGlzdCI6W119\n//# sourceURL=webpack-internal:///./assets/js/global/smooth-scrolling.js\n\n}");

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
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/dark-mode.js"); })
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/load-at-top.js"); })
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/custom-cursor.js"); })
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/smooth-scrolling.js"); })
/******/ 	__webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/js/global/remove-anchor-from-url.js"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], function() { return __webpack_require__("./assets/css/app.scss"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;