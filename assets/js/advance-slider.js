/******/ "use strict";
/******/ var __webpack_modules__ = ({

/***/ "./widgets/advance-slider/css/advance-slider.css":
/*!*******************************************************!*\
  !*** ./widgets/advance-slider/css/advance-slider.css ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ });
/************************************************************************/
/******/ // The module cache
/******/ var __webpack_module_cache__ = {};
/******/ 
/******/ // The require function
/******/ function __webpack_require__(moduleId) {
/******/ 	// Check if module is in cache
/******/ 	var cachedModule = __webpack_module_cache__[moduleId];
/******/ 	if (cachedModule !== undefined) {
/******/ 		return cachedModule.exports;
/******/ 	}
/******/ 	// Create a new module (and put it into the cache)
/******/ 	var module = __webpack_module_cache__[moduleId] = {
/******/ 		// no module.id needed
/******/ 		// no module.loaded needed
/******/ 		exports: {}
/******/ 	};
/******/ 
/******/ 	// Execute the module function
/******/ 	__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 
/******/ 	// Return the exports of the module
/******/ 	return module.exports;
/******/ }
/******/ 
/************************************************************************/
/******/ /* webpack/runtime/make namespace object */
/******/ (() => {
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = (exports) => {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/ })();
/******/ 
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*****************************************************!*\
  !*** ./widgets/advance-slider/js/advance-slider.js ***!
  \*****************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _css_advance_slider_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/advance-slider.css */ "./widgets/advance-slider/css/advance-slider.css");
var __async = (__this, __arguments, generator) => {
  return new Promise((resolve, reject) => {
    var fulfilled = (value) => {
      try {
        step(generator.next(value));
      } catch (e) {
        reject(e);
      }
    };
    var rejected = (value) => {
      try {
        step(generator.throw(value));
      } catch (e) {
        reject(e);
      }
    };
    var step = (x) => x.done ? resolve(x.value) : Promise.resolve(x.value).then(fulfilled, rejected);
    step((generator = generator.apply(__this, __arguments)).next());
  });
};

class advanceSlider extends elementorModules.frontend.handlers.SwiperBase {
  constructor(e) {
    super(e);
    this.outerContainerSelector = "";
  }
  getDefaultSettings() {
    return {
      selectors: {
        outerContainer: ".el-quantum-advance-slider-outer-container",
        container: ".el-quantum-advance-slider-container"
      }
    };
  }
  getDefaultElements() {
    const selectors = this.getSettings("selectors");
    return {
      $container: this.$element.find(selectors.outerContainer + " " + selectors.container)
    };
  }
  onInit() {
    this.outerContainerSelector = "." + Array.from(this.$element[0].classList).join(".");
    this.initSwiper(this.getDefaultElements().$container);
  }
  initSwiper(el) {
    return __async(this, null, function* () {
      let swiperConfig = this.getSwiperConfig();
      const Swiper = elementorFrontend.utils.swiper;
      const newSwiperInstance = yield new Swiper(el, swiperConfig);
      this.getDefaultElements().$container.data("swiper", newSwiperInstance);
    });
  }
  getSwiperConfig() {
    const settings = this.getElementSettings();
    const elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
    const desktopSlideToShow = +settings["slide_per_view"];
    const desktopCenterSlides = +settings["center_slide"] ? true : false;
    const desktopSpaceBetween = +settings["space_between"];
    const desktopSlidePerGroup = +settings["slide_per_group"];
    const isPaginationClickable = settings["is_pagination_clickable"] === "yes" ? true : false;
    const paginationType = settings["pagination_type"];
    const isScrollbarDraggable = settings["is_scrollbar_draggable"] === "yes" ? true : false;
    let customNextBtn = settings["custom_navigation_next_button_selector"];
    let customPrevBtn = settings["custom_navigation_prev_button_selector"];
    let customPagination = settings["custom_pagination_selector"];
    let customScrollbar = settings["custom_scrollbar_selector"];
    if (typeof customNextBtn === "string")
      customNextBtn = customNextBtn.trim();
    if (typeof customPrevBtn === "string")
      customPrevBtn = customPrevBtn.trim();
    if (typeof customPagination === "string")
      customPagination = customPagination.trim();
    if (typeof customScrollbar === "string")
      customScrollbar = customScrollbar.trim();
    if (customScrollbar) {
      const scrollbarEl = document.querySelector(customScrollbar);
      if (scrollbarEl)
        scrollbarEl.classList.add("swiper-scrollbar");
    }
    const swiperConfig = {
      direction: "horizontal",
      loop: settings["loop"] ? true : false,
      slidesPerView: desktopSlideToShow,
      centeredSlides: desktopCenterSlides,
      spaceBetween: desktopSpaceBetween,
      slidesPerGroup: desktopSlidePerGroup,
      pagination: {
        el: customPagination ? customPagination : ".swiper-pagination",
        clickable: isPaginationClickable,
        type: paginationType
      },
      navigation: {
        nextEl: customNextBtn ? customNextBtn : this.outerContainerSelector + " .el-quantum-next-btn",
        prevEl: customPrevBtn ? customPrevBtn : this.outerContainerSelector + " .el-quantum-prev-btn"
      },
      scrollbar: {
        el: customScrollbar ? customScrollbar : ".el-quantum-slider-scrollbar",
        draggable: isScrollbarDraggable
      },
      breakpoints: {},
      ///i saw this code in Elementor source code and this seems to
      ///"correct" the breakpoints according to swiper breakpoints
      handleElementorBreakpoints: true
    };
    Object.keys(elementorBreakpoints).reverse().forEach((breakpointName) => {
      swiperConfig.breakpoints[elementorBreakpoints[breakpointName].value] = {
        slidesPerView: +settings["slide_per_view_" + breakpointName],
        centeredSlides: +settings["center_slide_" + breakpointName] ? true : false,
        spaceBetween: +settings["space_between_" + breakpointName],
        slidesPerGroup: +settings["slide_per_group_" + breakpointName]
      };
    });
    return swiperConfig;
  }
}
jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(advanceSlider, { $element });
  };
  elementorFrontend.hooks.addAction(
    "frontend/element_ready/Advance_slider.default",
    addHandler
  );
});

})();


//# sourceMappingURL=advance-slider.js.map