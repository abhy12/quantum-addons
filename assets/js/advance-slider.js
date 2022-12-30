class advanceSlider extends elementorModules.frontend.handlers.Base {
   getDefaultSettings() {
      return {
         selectors: {
            container: ".quantum-swiper-container",
         },
      }
   }

   getDefaultElements() {
      const selectors = this.getSettings( "selectors" );

      return {
         $container: this.$element.find( selectors.container ),
      }
   }

   onInit() {
      this.initSwiper( this.getDefaultElements().$container );
   }

   initSwiper( el ) {
      let swiperConfig = this.getSwiperConfig();
      console.log( swiperConfig );

      /// Swiper elementor internal library
      const Swiper = elementorFrontend.utils.swiper;

      new Swiper( el, swiperConfig ).then( ( newSwiperInstance ) => {
         let mySwiper = newSwiperInstance;
         this.getDefaultElements().$container.data( "swiper", mySwiper );
         // console.log( this.elements.$container.data() );
      });
   }

   getSwiperConfig() {
      const settings = this.getElementSettings;
      const allBreakPoints = elementorFrontend.config.responsive.activeBreakpoints;
      const breakpointName = Object.keys( allBreakPoints );
      const config = {
         direction: "horizontal",
         loop: settings( "loop" ) ? true : false,
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
      }
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

      let breakpointSettings = {},
         lastBreakpoint;

      responsiveOptions.forEach( ( option ) => {
         lastBreakpoint = null;
         const arg = Object.keys( option );
         const optionKey = arg[0];
         const optionValue = option[optionKey];
         const optionType = option.type;

         for( let i = 0; i <= breakpointName.length; i++ ) {
            let breakpointBreakpoint, elementValue;

            if( i !== breakpointName.length ) {
               elementValue = settings( optionValue + "_" + breakpointName[i] );
            } else if( i === breakpointName.length )
               elementValue = settings( optionValue );

            if( elementValue === undefined || elementValue === "" ) continue;

            if( optionType !== undefined ) {
               if( optionType === "BOOLEAN" ) {
                  if( elementValue == 0 ) {
                     elementValue = false;
                  } else elementValue = true;
               } else if( optionType === "NUMBER" ) {
                  if( parseInt( elementValue ) === NaN ) {
                     console.error( optionValue + " Needs to be a number" );
                     continue;
                  }
               }
            } else {
               console.error( "You have to define type of " + optionValue );
               continue;
            }

            if( i === breakpointName.length ) {
               breakpointBreakpoint = {
                  [optionKey]: elementValue,
               }

               breakpointSettings[lastBreakpoint] = {
                  ...breakpointSettings[lastBreakpoint],
                  ...breakpointBreakpoint,
               }
            } else if( i < breakpointName.length ) {
               if( i === 0 ) {
                  config[ optionKey ] = elementValue;
               } else if( i > 0 ) {
                  breakpointBreakpoint = {
                     [optionKey]: elementValue,
                  }

                  breakpointSettings[lastBreakpoint] = {
                     ...breakpointSettings[lastBreakpoint],
                     ...breakpointBreakpoint,
                  }
               }

               lastBreakpoint = allBreakPoints[breakpointName[i]].value;
               if( allBreakPoints[ breakpointName[i]].direction === "max" ) {
                  lastBreakpoint++;
               }
            }
         }
      });

      config.breakpoints = { ...breakpointSettings };
      return config;
   }
}

jQuery( window ).on( "elementor/frontend/init", () => {
   const addHandler = ( $element ) => {
      elementorFrontend.elementsHandler.addHandler( advanceSlider, { $element } );
   };

   elementorFrontend.hooks.addAction(
      "frontend/element_ready/Advance_slider.default",
      addHandler
   );
});