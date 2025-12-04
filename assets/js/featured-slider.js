(function ($) {
    const initializeSlider = function ($scope) {
        const $wrapper = $scope.find('.boginya-featured-products');
        if (!$wrapper.length) {
            return;
        }

        $wrapper.each(function () {
            const $instance = $(this);
            const settings = $instance.data('settings') || {};
            const autoplay = settings.autoplay === 'true';
            const autoplaySpeed = parseInt(settings.autoplaySpeed, 10) || 4500;
            const transitionSpeed = parseInt(settings.transitionSpeed, 10) || 450;
            const slidesToShow = parseInt(settings.slidesToShow, 10) || 3;
            const showDots = settings.showDots === 'true';
            const showArrows = settings.showArrows === 'true';

            const $track = $instance.find('.boginya-slider__track');
            if (!$track.length) {
                return;
            }

            const slider = new Siema({
                selector: $track[0],
                duration: transitionSpeed,
                loop: true,
                perPage: {
                    0: 1,
                    600: Math.min(2, slidesToShow),
                    900: Math.min(3, slidesToShow),
                    1200: Math.min(4, slidesToShow),
                    1400: Math.min(5, slidesToShow)
                },
            });

            let autoplayTimer = null;
            const dotsContainer = $instance.find('.boginya-dots');
            const createDots = function () {
                if (!showDots || !dotsContainer.length) {
                    return;
                }
                dotsContainer.empty();
                for (let i = 0; i < slider.innerElements.length; i++) {
                    const dot = $('<button type="button" aria-label="Go to slide ' + (i + 1) + '"></button>');
                    dot.on('click', function () {
                        slider.goTo(i);
                        restartAutoplay();
                        setActiveDot(i);
                    });
                    dotsContainer.append(dot);
                }
                setActiveDot(slider.currentSlide);
            };

            const setActiveDot = function (index) {
                if (!dotsContainer.length) {
                    return;
                }
                dotsContainer.find('button').removeClass('is-active').eq(index).addClass('is-active');
            };

            const restartAutoplay = function () {
                if (!autoplay) {
                    return;
                }
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                }
                autoplayTimer = setInterval(function () {
                    slider.next();
                }, autoplaySpeed);
            };

            $instance.find('.boginya-arrow--next').on('click', function () {
                slider.next();
                restartAutoplay();
            });

            $instance.find('.boginya-arrow--prev').on('click', function () {
                slider.prev();
                restartAutoplay();
            });

            slider.config.onChange = function () {
                setActiveDot(slider.currentSlide);
            };

            createDots();
            restartAutoplay();

            $instance.on('mouseenter', function () {
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                }
            });

            $instance.on('mouseleave', function () {
                restartAutoplay();
            });
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/boginya-featured-products.default', initializeSlider);
    });
})(jQuery);
