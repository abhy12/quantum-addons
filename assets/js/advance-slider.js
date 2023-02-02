class advanceSlider extends elementorModules.frontend.handlers.SwiperBase  {
   getDefaultSettings()  {
      return {
         selectors: {
            container: ".quantum-swiper-container",
         },
      }
   }

   getDefaultElements()  {
      const selectors = this.getSettings( "selectors" );

      return {
         $container: this.$element.find( selectors.container ),
      }
   }

   onInit()  {
      this.initSwiper( this.getDefaultElements().$container );
   }

   async initSwiper( el )  {
      let swiperConfig = this.getSwiperConfig();
      console.log( swiperConfig );

      /// Swiper elementor internal library
      const Swiper = elementorFrontend.utils.swiper;
      const newSwiperInstance = await new Swiper( el, swiperConfig );

      ///adding swiper instance to container with Jquery data function
      ///P.S i don't know why anyone will need this, but just leaving it right here for now
      this.getDefaultElements().$container.data( "swiper", newSwiperInstance );
   }

   getSwiperConfig()  {
      ///all the elements settings
      const settings = this.getElementSettings();
      const elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
      const desktopSlideToShow = +settings['slide_per_view'];
      const desktopCenterSlides = +settings['center_slide'] ? true : false;
      const desktopSpaceBetween = +settings['space_between'];
      const desktopSlidePerGroup = +settings['slide_per_group'];


      ///default config for swiper
      const swiperConfig =  {
         direction: "horizontal",
         loop: settings["loop"] ? true : false,
         slidesPerView: desktopSlideToShow,
         centeredSlides: desktopCenterSlides,
         spaceBetween: desktopSpaceBetween,
         slidesPerGroup: desktopSlidePerGroup,
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
         breakpoints: {},
         ///i saw this code in Elementor source code and this seems to 
         ///"correct" the breakpoints according to swiper breakpoints
         handleElementorBreakpoints: true,
      }

      ////add breakpoints values to swiper config
      Object.keys( elementorBreakpoints ).reverse().forEach( ( breakpointName ) =>  {
			swiperConfig.breakpoints[ elementorBreakpoints[ breakpointName ].value ] =  {
				slidesPerView: +settings['slide_per_view_' + breakpointName],
            centeredSlides: +settings['center_slide_' + breakpointName] ? true : false,
				spaceBetween: +settings['space_between_' + breakpointName],
				slidesPerGroup: +settings['slide_per_group_' + breakpointName],
			}
		});

      return swiperConfig;
   }
}

jQuery( window ).on( "elementor/frontend/init", () =>  {
   const addHandler = ( $element ) => {
      elementorFrontend.elementsHandler.addHandler( advanceSlider, { $element } );
   };

   elementorFrontend.hooks.addAction(
      "frontend/element_ready/Advance_slider.default",
      addHandler
   );
});