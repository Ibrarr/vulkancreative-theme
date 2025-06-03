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
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvY3NzL2FwcC5zY3NzIiwibWFwcGluZ3MiOiI7QUFBQSIsInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2Nzcy9hcHAuc2Nzcz9kMTkxIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/css/app.scss\n");

/***/ }),

/***/ "./assets/js/global/custom-cursor.js":
/*!*******************************************!*\
  !*** ./assets/js/global/custom-cursor.js ***!
  \*******************************************/
/***/ (function() {

eval("jQuery(document).ready(function ($) {\n  var cursor = document.querySelector('.custom-cursor');\n  var isTouchDevice = function isTouchDevice() {\n    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;\n  };\n  if (isTouchDevice()) {\n    cursor.style.display = 'none';\n    return;\n  }\n\n  // Track mouse movement\n  document.addEventListener('mousemove', function (e) {\n    cursor.style.left = \"\".concat(e.clientX, \"px\");\n    cursor.style.top = \"\".concat(e.clientY, \"px\");\n  });\n});\njQuery(function ($) {\n  $(document).on('gform_post_render gform_confirmation_loaded gform_page_loaded', function () {\n    var $cursor = $('.custom-cursor');\n    if (!$cursor.length) {\n      $cursor = $('<div class=\"custom-cursor\"></div>');\n    }\n    $('body').append($cursor);\n    $cursor.removeClass('hidden').show();\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwiJCIsImN1cnNvciIsInF1ZXJ5U2VsZWN0b3IiLCJpc1RvdWNoRGV2aWNlIiwid2luZG93IiwibmF2aWdhdG9yIiwibWF4VG91Y2hQb2ludHMiLCJtc01heFRvdWNoUG9pbnRzIiwic3R5bGUiLCJkaXNwbGF5IiwiYWRkRXZlbnRMaXN0ZW5lciIsImUiLCJsZWZ0IiwiY29uY2F0IiwiY2xpZW50WCIsInRvcCIsImNsaWVudFkiLCJvbiIsIiRjdXJzb3IiLCJsZW5ndGgiLCJhcHBlbmQiLCJyZW1vdmVDbGFzcyIsInNob3ciXSwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2N1c3RvbS1jdXJzb3IuanM/OGE5ZiJdLCJzb3VyY2VzQ29udGVudCI6WyJqUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCQpIHtcbiAgICBjb25zdCBjdXJzb3IgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuY3VzdG9tLWN1cnNvcicpO1xuXG4gICAgY29uc3QgaXNUb3VjaERldmljZSA9ICgpID0+IHtcbiAgICAgICAgcmV0dXJuICdvbnRvdWNoc3RhcnQnIGluIHdpbmRvdyB8fCBuYXZpZ2F0b3IubWF4VG91Y2hQb2ludHMgPiAwIHx8IG5hdmlnYXRvci5tc01heFRvdWNoUG9pbnRzID4gMDtcbiAgICB9O1xuXG4gICAgaWYgKGlzVG91Y2hEZXZpY2UoKSkge1xuICAgICAgICBjdXJzb3Iuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIC8vIFRyYWNrIG1vdXNlIG1vdmVtZW50XG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignbW91c2Vtb3ZlJywgKGUpID0+IHtcbiAgICAgICAgY3Vyc29yLnN0eWxlLmxlZnQgPSBgJHtlLmNsaWVudFh9cHhgO1xuICAgICAgICBjdXJzb3Iuc3R5bGUudG9wID0gYCR7ZS5jbGllbnRZfXB4YDtcbiAgICB9KTtcbn0pO1xuXG5qUXVlcnkoZnVuY3Rpb24gKCQpIHtcbiAgICAkKGRvY3VtZW50KS5vbignZ2Zvcm1fcG9zdF9yZW5kZXIgZ2Zvcm1fY29uZmlybWF0aW9uX2xvYWRlZCBnZm9ybV9wYWdlX2xvYWRlZCcsIGZ1bmN0aW9uICgpIHtcblxuICAgICAgICBsZXQgJGN1cnNvciA9ICQoJy5jdXN0b20tY3Vyc29yJyk7XG4gICAgICAgIGlmICghJGN1cnNvci5sZW5ndGgpIHtcbiAgICAgICAgICAgICRjdXJzb3IgPSAkKCc8ZGl2IGNsYXNzPVwiY3VzdG9tLWN1cnNvclwiPjwvZGl2PicpO1xuICAgICAgICB9XG5cbiAgICAgICAgJCgnYm9keScpLmFwcGVuZCgkY3Vyc29yKTtcblxuICAgICAgICAkY3Vyc29yLnJlbW92ZUNsYXNzKCdoaWRkZW4nKS5zaG93KCk7XG4gICAgfSk7XG59KTtcbiJdLCJtYXBwaW5ncyI6IkFBQUFBLE1BQU0sQ0FBQ0MsUUFBUSxDQUFDLENBQUNDLEtBQUssQ0FBQyxVQUFTQyxDQUFDLEVBQUU7RUFDL0IsSUFBTUMsTUFBTSxHQUFHSCxRQUFRLENBQUNJLGFBQWEsQ0FBQyxnQkFBZ0IsQ0FBQztFQUV2RCxJQUFNQyxhQUFhLEdBQUcsU0FBaEJBLGFBQWFBLENBQUEsRUFBUztJQUN4QixPQUFPLGNBQWMsSUFBSUMsTUFBTSxJQUFJQyxTQUFTLENBQUNDLGNBQWMsR0FBRyxDQUFDLElBQUlELFNBQVMsQ0FBQ0UsZ0JBQWdCLEdBQUcsQ0FBQztFQUNyRyxDQUFDO0VBRUQsSUFBSUosYUFBYSxDQUFDLENBQUMsRUFBRTtJQUNqQkYsTUFBTSxDQUFDTyxLQUFLLENBQUNDLE9BQU8sR0FBRyxNQUFNO0lBQzdCO0VBQ0o7O0VBRUE7RUFDQVgsUUFBUSxDQUFDWSxnQkFBZ0IsQ0FBQyxXQUFXLEVBQUUsVUFBQ0MsQ0FBQyxFQUFLO0lBQzFDVixNQUFNLENBQUNPLEtBQUssQ0FBQ0ksSUFBSSxNQUFBQyxNQUFBLENBQU1GLENBQUMsQ0FBQ0csT0FBTyxPQUFJO0lBQ3BDYixNQUFNLENBQUNPLEtBQUssQ0FBQ08sR0FBRyxNQUFBRixNQUFBLENBQU1GLENBQUMsQ0FBQ0ssT0FBTyxPQUFJO0VBQ3ZDLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQztBQUVGbkIsTUFBTSxDQUFDLFVBQVVHLENBQUMsRUFBRTtFQUNoQkEsQ0FBQyxDQUFDRixRQUFRLENBQUMsQ0FBQ21CLEVBQUUsQ0FBQywrREFBK0QsRUFBRSxZQUFZO0lBRXhGLElBQUlDLE9BQU8sR0FBR2xCLENBQUMsQ0FBQyxnQkFBZ0IsQ0FBQztJQUNqQyxJQUFJLENBQUNrQixPQUFPLENBQUNDLE1BQU0sRUFBRTtNQUNqQkQsT0FBTyxHQUFHbEIsQ0FBQyxDQUFDLG1DQUFtQyxDQUFDO0lBQ3BEO0lBRUFBLENBQUMsQ0FBQyxNQUFNLENBQUMsQ0FBQ29CLE1BQU0sQ0FBQ0YsT0FBTyxDQUFDO0lBRXpCQSxPQUFPLENBQUNHLFdBQVcsQ0FBQyxRQUFRLENBQUMsQ0FBQ0MsSUFBSSxDQUFDLENBQUM7RUFDeEMsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDIiwiaWdub3JlTGlzdCI6W10sImZpbGUiOiIuL2Fzc2V0cy9qcy9nbG9iYWwvY3VzdG9tLWN1cnNvci5qcyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/js/global/custom-cursor.js\n");

/***/ }),

/***/ "./assets/js/global/dark-mode.js":
/*!***************************************!*\
  !*** ./assets/js/global/dark-mode.js ***!
  \***************************************/
/***/ (function() {

eval("document.addEventListener('DOMContentLoaded', function () {\n  var toggleBtns = document.querySelectorAll('.theme-toggle');\n  var body = document.body;\n\n  // Load user's theme preference from localStorage\n  if (localStorage.getItem('darkMode') === 'enabled') {\n    body.classList.add('dark-mode');\n    toggleBtns.forEach(function (btn) {\n      return btn.classList.add('theme-toggle--toggled');\n    });\n  }\n  toggleBtns.forEach(function (btn) {\n    btn.addEventListener('click', function () {\n      // Toggle dark mode on <body>\n      body.classList.toggle('dark-mode');\n\n      // Persist dark-mode preference\n      localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');\n\n      // Update all toggle buttons' appearance\n      toggleBtns.forEach(function (b) {\n        return b.classList.toggle('theme-toggle--toggled');\n      });\n    });\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvanMvZ2xvYmFsL2RhcmstbW9kZS5qcyIsIm5hbWVzIjpbImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsInRvZ2dsZUJ0bnMiLCJxdWVyeVNlbGVjdG9yQWxsIiwiYm9keSIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJjbGFzc0xpc3QiLCJhZGQiLCJmb3JFYWNoIiwiYnRuIiwidG9nZ2xlIiwic2V0SXRlbSIsImNvbnRhaW5zIiwiYiJdLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2RhcmstbW9kZS5qcz82MThhIl0sInNvdXJjZXNDb250ZW50IjpbImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XG4gICAgY29uc3QgdG9nZ2xlQnRucyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy50aGVtZS10b2dnbGUnKTtcbiAgICBjb25zdCBib2R5ID0gZG9jdW1lbnQuYm9keTtcblxuICAgIC8vIExvYWQgdXNlcidzIHRoZW1lIHByZWZlcmVuY2UgZnJvbSBsb2NhbFN0b3JhZ2VcbiAgICBpZiAobG9jYWxTdG9yYWdlLmdldEl0ZW0oJ2RhcmtNb2RlJykgPT09ICdlbmFibGVkJykge1xuICAgICAgICBib2R5LmNsYXNzTGlzdC5hZGQoJ2RhcmstbW9kZScpO1xuICAgICAgICB0b2dnbGVCdG5zLmZvckVhY2goYnRuID0+IGJ0bi5jbGFzc0xpc3QuYWRkKCd0aGVtZS10b2dnbGUtLXRvZ2dsZWQnKSk7XG4gICAgfVxuXG4gICAgdG9nZ2xlQnRucy5mb3JFYWNoKGJ0biA9PiB7XG4gICAgICAgIGJ0bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgICAgIC8vIFRvZ2dsZSBkYXJrIG1vZGUgb24gPGJvZHk+XG4gICAgICAgICAgICBib2R5LmNsYXNzTGlzdC50b2dnbGUoJ2RhcmstbW9kZScpO1xuXG4gICAgICAgICAgICAvLyBQZXJzaXN0IGRhcmstbW9kZSBwcmVmZXJlbmNlXG4gICAgICAgICAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbSgnZGFya01vZGUnLCBib2R5LmNsYXNzTGlzdC5jb250YWlucygnZGFyay1tb2RlJykgPyAnZW5hYmxlZCcgOiAnZGlzYWJsZWQnKTtcblxuICAgICAgICAgICAgLy8gVXBkYXRlIGFsbCB0b2dnbGUgYnV0dG9ucycgYXBwZWFyYW5jZVxuICAgICAgICAgICAgdG9nZ2xlQnRucy5mb3JFYWNoKGIgPT4gYi5jbGFzc0xpc3QudG9nZ2xlKCd0aGVtZS10b2dnbGUtLXRvZ2dsZWQnKSk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7XG4iXSwibWFwcGluZ3MiOiJBQUFBQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQU07RUFDaEQsSUFBTUMsVUFBVSxHQUFHRixRQUFRLENBQUNHLGdCQUFnQixDQUFDLGVBQWUsQ0FBQztFQUM3RCxJQUFNQyxJQUFJLEdBQUdKLFFBQVEsQ0FBQ0ksSUFBSTs7RUFFMUI7RUFDQSxJQUFJQyxZQUFZLENBQUNDLE9BQU8sQ0FBQyxVQUFVLENBQUMsS0FBSyxTQUFTLEVBQUU7SUFDaERGLElBQUksQ0FBQ0csU0FBUyxDQUFDQyxHQUFHLENBQUMsV0FBVyxDQUFDO0lBQy9CTixVQUFVLENBQUNPLE9BQU8sQ0FBQyxVQUFBQyxHQUFHO01BQUEsT0FBSUEsR0FBRyxDQUFDSCxTQUFTLENBQUNDLEdBQUcsQ0FBQyx1QkFBdUIsQ0FBQztJQUFBLEVBQUM7RUFDekU7RUFFQU4sVUFBVSxDQUFDTyxPQUFPLENBQUMsVUFBQUMsR0FBRyxFQUFJO0lBQ3RCQSxHQUFHLENBQUNULGdCQUFnQixDQUFDLE9BQU8sRUFBRSxZQUFNO01BQ2hDO01BQ0FHLElBQUksQ0FBQ0csU0FBUyxDQUFDSSxNQUFNLENBQUMsV0FBVyxDQUFDOztNQUVsQztNQUNBTixZQUFZLENBQUNPLE9BQU8sQ0FBQyxVQUFVLEVBQUVSLElBQUksQ0FBQ0csU0FBUyxDQUFDTSxRQUFRLENBQUMsV0FBVyxDQUFDLEdBQUcsU0FBUyxHQUFHLFVBQVUsQ0FBQzs7TUFFL0Y7TUFDQVgsVUFBVSxDQUFDTyxPQUFPLENBQUMsVUFBQUssQ0FBQztRQUFBLE9BQUlBLENBQUMsQ0FBQ1AsU0FBUyxDQUFDSSxNQUFNLENBQUMsdUJBQXVCLENBQUM7TUFBQSxFQUFDO0lBQ3hFLENBQUMsQ0FBQztFQUNOLENBQUMsQ0FBQztBQUNOLENBQUMsQ0FBQyIsImlnbm9yZUxpc3QiOltdfQ==\n//# sourceURL=webpack-internal:///./assets/js/global/dark-mode.js\n");

/***/ }),

/***/ "./assets/js/global/load-at-top.js":
/*!*****************************************!*\
  !*** ./assets/js/global/load-at-top.js ***!
  \*****************************************/
/***/ (function() {

eval("document.addEventListener('DOMContentLoaded', function () {\n  if ('scrollRestoration' in history) {\n    history.scrollRestoration = 'manual';\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJoaXN0b3J5Iiwic2Nyb2xsUmVzdG9yYXRpb24iXSwic291cmNlcyI6WyJ3ZWJwYWNrOi8vdnVsa2FuY3JlYXRpdmUvLi9hc3NldHMvanMvZ2xvYmFsL2xvYWQtYXQtdG9wLmpzPzg4MmUiXSwic291cmNlc0NvbnRlbnQiOlsiZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uICgpIHtcbiAgICBpZiAoJ3Njcm9sbFJlc3RvcmF0aW9uJyBpbiBoaXN0b3J5KSB7XG4gICAgICAgIGhpc3Rvcnkuc2Nyb2xsUmVzdG9yYXRpb24gPSAnbWFudWFsJztcbiAgICB9XG59KTsiXSwibWFwcGluZ3MiOiJBQUFBQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQVk7RUFDdEQsSUFBSSxtQkFBbUIsSUFBSUMsT0FBTyxFQUFFO0lBQ2hDQSxPQUFPLENBQUNDLGlCQUFpQixHQUFHLFFBQVE7RUFDeEM7QUFDSixDQUFDLENBQUMiLCJpZ25vcmVMaXN0IjpbXSwiZmlsZSI6Ii4vYXNzZXRzL2pzL2dsb2JhbC9sb2FkLWF0LXRvcC5qcyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./assets/js/global/load-at-top.js\n");

/***/ }),

/***/ "./assets/js/global/remove-anchor-from-url.js":
/*!****************************************************!*\
  !*** ./assets/js/global/remove-anchor-from-url.js ***!
  \****************************************************/
/***/ (function() {

eval("document.addEventListener('DOMContentLoaded', function () {\n  document.querySelectorAll('a[href^=\"#\"]').forEach(function (link) {\n    link.addEventListener('click', function (e) {\n      var id = link.getAttribute('href').slice(1); // \"why\"\n      var target = document.getElementById(id);\n      if (target) {\n        e.preventDefault(); // stop the hash appearing\n        target.scrollIntoView({\n          behaviour: 'smooth'\n        });\n        history.replaceState(null, '', '/'); // tidy URL → https://vulkancreative.test\n      }\n    });\n  });\n  if (window.location.hash) {\n    var target = document.querySelector(window.location.hash);\n    if (target) {\n      setTimeout(function () {\n        target.scrollIntoView({\n          behavior: 'smooth'\n        });\n        history.replaceState(null, '', '/');\n      }, 10);\n    }\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9yRWFjaCIsImxpbmsiLCJlIiwiaWQiLCJnZXRBdHRyaWJ1dGUiLCJzbGljZSIsInRhcmdldCIsImdldEVsZW1lbnRCeUlkIiwicHJldmVudERlZmF1bHQiLCJzY3JvbGxJbnRvVmlldyIsImJlaGF2aW91ciIsImhpc3RvcnkiLCJyZXBsYWNlU3RhdGUiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsImhhc2giLCJxdWVyeVNlbGVjdG9yIiwic2V0VGltZW91dCIsImJlaGF2aW9yIl0sInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2pzL2dsb2JhbC9yZW1vdmUtYW5jaG9yLWZyb20tdXJsLmpzPzJjNTMiXSwic291cmNlc0NvbnRlbnQiOlsiZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uICgpIHtcbiAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdhW2hyZWZePVwiI1wiXScpLmZvckVhY2gobGluayA9PiB7XG4gICAgICAgIGxpbmsuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBlID0+IHtcbiAgICAgICAgICAgIGNvbnN0IGlkID0gbGluay5nZXRBdHRyaWJ1dGUoJ2hyZWYnKS5zbGljZSgxKTsgICAvLyBcIndoeVwiXG4gICAgICAgICAgICBjb25zdCB0YXJnZXQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChpZCk7XG4gICAgICAgICAgICBpZiAodGFyZ2V0KSB7XG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpOyAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gc3RvcCB0aGUgaGFzaCBhcHBlYXJpbmdcbiAgICAgICAgICAgICAgICB0YXJnZXQuc2Nyb2xsSW50b1ZpZXcoeyBiZWhhdmlvdXI6ICdzbW9vdGgnIH0pO1xuICAgICAgICAgICAgICAgIGhpc3RvcnkucmVwbGFjZVN0YXRlKG51bGwsICcnLCAnLycpOyAgICAgICAgIC8vIHRpZHkgVVJMIOKGkiBodHRwczovL3Z1bGthbmNyZWF0aXZlLnRlc3RcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICBpZiAod2luZG93LmxvY2F0aW9uLmhhc2gpIHtcbiAgICAgICAgY29uc3QgdGFyZ2V0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3Rvcih3aW5kb3cubG9jYXRpb24uaGFzaCk7XG4gICAgICAgIGlmICh0YXJnZXQpIHtcbiAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRhcmdldC5zY3JvbGxJbnRvVmlldyh7IGJlaGF2aW9yOiAnc21vb3RoJyB9KTtcbiAgICAgICAgICAgICAgICBoaXN0b3J5LnJlcGxhY2VTdGF0ZShudWxsLCAnJywgJy8nKTtcbiAgICAgICAgICAgIH0sIDEwKTtcbiAgICAgICAgfVxuICAgIH1cbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUFBLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsa0JBQWtCLEVBQUUsWUFBWTtFQUN0REQsUUFBUSxDQUFDRSxnQkFBZ0IsQ0FBQyxjQUFjLENBQUMsQ0FBQ0MsT0FBTyxDQUFDLFVBQUFDLElBQUksRUFBSTtJQUN0REEsSUFBSSxDQUFDSCxnQkFBZ0IsQ0FBQyxPQUFPLEVBQUUsVUFBQUksQ0FBQyxFQUFJO01BQ2hDLElBQU1DLEVBQUUsR0FBR0YsSUFBSSxDQUFDRyxZQUFZLENBQUMsTUFBTSxDQUFDLENBQUNDLEtBQUssQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFHO01BQ2pELElBQU1DLE1BQU0sR0FBR1QsUUFBUSxDQUFDVSxjQUFjLENBQUNKLEVBQUUsQ0FBQztNQUMxQyxJQUFJRyxNQUFNLEVBQUU7UUFDUkosQ0FBQyxDQUFDTSxjQUFjLENBQUMsQ0FBQyxDQUFDLENBQTBCO1FBQzdDRixNQUFNLENBQUNHLGNBQWMsQ0FBQztVQUFFQyxTQUFTLEVBQUU7UUFBUyxDQUFDLENBQUM7UUFDOUNDLE9BQU8sQ0FBQ0MsWUFBWSxDQUFDLElBQUksRUFBRSxFQUFFLEVBQUUsR0FBRyxDQUFDLENBQUMsQ0FBUztNQUNqRDtJQUNKLENBQUMsQ0FBQztFQUNOLENBQUMsQ0FBQztFQUVGLElBQUlDLE1BQU0sQ0FBQ0MsUUFBUSxDQUFDQyxJQUFJLEVBQUU7SUFDdEIsSUFBTVQsTUFBTSxHQUFHVCxRQUFRLENBQUNtQixhQUFhLENBQUNILE1BQU0sQ0FBQ0MsUUFBUSxDQUFDQyxJQUFJLENBQUM7SUFDM0QsSUFBSVQsTUFBTSxFQUFFO01BQ1JXLFVBQVUsQ0FBQyxZQUFNO1FBQ2JYLE1BQU0sQ0FBQ0csY0FBYyxDQUFDO1VBQUVTLFFBQVEsRUFBRTtRQUFTLENBQUMsQ0FBQztRQUM3Q1AsT0FBTyxDQUFDQyxZQUFZLENBQUMsSUFBSSxFQUFFLEVBQUUsRUFBRSxHQUFHLENBQUM7TUFDdkMsQ0FBQyxFQUFFLEVBQUUsQ0FBQztJQUNWO0VBQ0o7QUFDSixDQUFDLENBQUMiLCJpZ25vcmVMaXN0IjpbXSwiZmlsZSI6Ii4vYXNzZXRzL2pzL2dsb2JhbC9yZW1vdmUtYW5jaG9yLWZyb20tdXJsLmpzIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./assets/js/global/remove-anchor-from-url.js\n");

/***/ }),

/***/ "./assets/js/global/smooth-scrolling.js":
/*!**********************************************!*\
  !*** ./assets/js/global/smooth-scrolling.js ***!
  \**********************************************/
/***/ (function() {

eval("// import gsap from 'gsap';\n// import { ScrollSmoother } from 'gsap/ScrollSmoother';\n// import { ScrollTrigger } from 'gsap/ScrollTrigger';\n//\n// gsap.registerPlugin(ScrollTrigger, ScrollSmoother);\n//\n// ScrollSmoother.create({\n//     smooth: 1,\n//     effects: true,\n//     smoothTouch: 0.1,\n// });//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sInNvdXJjZXMiOlsid2VicGFjazovL3Z1bGthbmNyZWF0aXZlLy4vYXNzZXRzL2pzL2dsb2JhbC9zbW9vdGgtc2Nyb2xsaW5nLmpzPzU0MmYiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gaW1wb3J0IGdzYXAgZnJvbSAnZ3NhcCc7XG4vLyBpbXBvcnQgeyBTY3JvbGxTbW9vdGhlciB9IGZyb20gJ2dzYXAvU2Nyb2xsU21vb3RoZXInO1xuLy8gaW1wb3J0IHsgU2Nyb2xsVHJpZ2dlciB9IGZyb20gJ2dzYXAvU2Nyb2xsVHJpZ2dlcic7XG4vL1xuLy8gZ3NhcC5yZWdpc3RlclBsdWdpbihTY3JvbGxUcmlnZ2VyLCBTY3JvbGxTbW9vdGhlcik7XG4vL1xuLy8gU2Nyb2xsU21vb3RoZXIuY3JlYXRlKHtcbi8vICAgICBzbW9vdGg6IDEsXG4vLyAgICAgZWZmZWN0czogdHJ1ZSxcbi8vICAgICBzbW9vdGhUb3VjaDogMC4xLFxuLy8gfSk7XG4iXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJpZ25vcmVMaXN0IjpbXSwiZmlsZSI6Ii4vYXNzZXRzL2pzL2dsb2JhbC9zbW9vdGgtc2Nyb2xsaW5nLmpzIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./assets/js/global/smooth-scrolling.js\n");

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