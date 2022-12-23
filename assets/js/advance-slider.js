class advanceSlider extends elementorModules.frontend.handlers.SwiperBase {
  constructor(elements) {
    super(elements);
    this.newSwiper(this.elements.$container);
    // console.log( this.getElementSettings( 'slide_per_group' ) );
  }

  getDefaultSettings() {
    return {
      selectors: {
        container: ".quantum-swiper-container",
      },
    };
  }

  getSwiperDefaultConfig() {
    const settings = this.getElementSettings;
    return {
      direction: "horizontal",
      loop: settings("loop") ? true : false,
      pagination: {
        el: ".swiper-pagination",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      scrollbar: {
        el: ".swiper-scrollbar",
      },
    };
  }

  getDefaultElements() {
    const selectors = this.getSettings("selectors");
    return {
      $container: this.$element.find(selectors.container),
      // $button: this.$element.find(selectors.button),
    };
  }

  //used for adding eventListner to any element
  // bindEvents() {
  //   this.elements.$button.on("click", this.onButtonClick.bind(this));
  // }

  /**
   * add responsive settings
   * @param Object of swiper config
   * @param Object with key of the swiper option and the value of elementId
   * @returns Object with breakpoints
   */
  addBreakPointSettings(config, args) {
    const settings = this.getElementSettings,
      allBreakPoints = elementorFrontend.config.responsive.activeBreakpoints,
      breakpointName = Object.keys(allBreakPoints);
    let breakpointSettings = {},
      lastBreakpoint;

    args.forEach((option) => {
      lastBreakpoint = null;
      const arg = Object.keys(option);
      const optionKey = arg[0];
      const optionValue = option[optionKey];
      const optionType = option.type;

      for (let i = 0; i <= breakpointName.length; i++) {
        let breakpointBreakpoint, elementValue;

        if (i !== breakpointName.length) {
          elementValue = settings(optionValue + "_" + breakpointName[i]);
        } else if (i === breakpointName.length)
          elementValue = settings(optionValue);

        if (elementValue === undefined || elementValue === "") continue;

        if (optionType !== undefined) {
          if (optionType === "BOOLEAN") {
            if (elementValue == 0) {
              elementValue = false;
            } else elementValue = true;
          } else if (optionType === "NUMBER") {
            if (parseInt(elementValue) === NaN) {
              console.error(optionValue + " Needs to be a number");
              continue;
            }
          }
        } else {
          console.error("You have to define type of " + optionValue);
          continue;
        }

        if (i === breakpointName.length) {
          breakpointBreakpoint = {[optionKey]: elementValue};
          breakpointSettings[lastBreakpoint] = {
            ...breakpointSettings[lastBreakpoint],
            ...breakpointBreakpoint,
          };
        } else if (i < breakpointName.length) {
          if (i === 0) {
            config[optionKey] = elementValue;
          } else if (i > 0) {
            breakpointBreakpoint = {[optionKey]: elementValue};
            breakpointSettings[lastBreakpoint] = {
              ...breakpointSettings[lastBreakpoint],
              ...breakpointBreakpoint,
            };
          }

          lastBreakpoint = allBreakPoints[breakpointName[i]].value;
          if (allBreakPoints[breakpointName[i]].direction === "max") {
            lastBreakpoint++;
          }
        }
      }
    });

    config.breakpoints = {...breakpointSettings};
    return config;
  }

  newSwiper(el) {
    const responsiveOptions = [
      {
        slidesPerView: "slide_per_view",
        type: "NUMBER",
      },
      {
        centeredSlides: "center_slide",
        type: "BOOLEAN",
      },
      {
        spaceBetween: "space_between",
        type: "NUMBER",
      },
      {
        slidesPerGroup: "slide_per_group",
        type: "NUMBER",
      },
    ];

    let defaultSwiperConfig = this.getSwiperDefaultConfig(),
      swiperConfig = this.addBreakPointSettings(
        defaultSwiperConfig,
        responsiveOptions
      );
    console.log(swiperConfig);

    // Not using typeof Swiper condition logic here because
    // if you link swiper lib via CDN element built with elementor's
    // swiper instance won't work
    //
    // P.S. I have not put lots of thought into it and maybe change it later
    //
    const Swiper = elementorFrontend.utils.swiper;
    new Swiper(el, swiperConfig).then((newSwiperInstance) => {
      let mySwiper = newSwiperInstance;
      this.elements.$container.data("swiper", mySwiper);
      // console.log( this.elements.$container.data() );
    });
  }
}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(simpleSlider, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/simple_slider.default",
    addHandler
  );
});