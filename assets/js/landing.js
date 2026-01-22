/**
 * Nati Fuori Binario Landing - JavaScript
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        NFBLanding.init();
    });

    var NFBLanding = {
        init: function() {
            this.mobileMenu();
            this.smoothScroll();
            this.rassegnaToggle();
            this.eventiToggle();
            this.headerShrink();
            this.scrollToTop();
            this.scrollReveal();
        },

        /**
         * Mobile Menu Functionality
         */
        mobileMenu: function() {
            var $toggle = $('#nfb-mobile-toggle');
            var $close = $('#nfb-mobile-close');
            var $nav = $('#nfb-nav');
            var $body = $('body');
            var $overlay = $('<div class="nfb-mobile-overlay"></div>');

            // Append overlay to body
            $body.append($overlay);

            // Open menu
            $toggle.on('click', function(e) {
                e.preventDefault();
                $nav.addClass('active');
                $overlay.addClass('active');
                $body.css('overflow', 'hidden');
            });

            // Close menu
            function closeMenu() {
                $nav.removeClass('active');
                $overlay.removeClass('active');
                $body.css('overflow', '');
            }

            $close.on('click', function(e) {
                e.preventDefault();
                closeMenu();
            });

            $overlay.on('click', function() {
                closeMenu();
            });

            // Close menu on link click
            $nav.find('a').on('click', function() {
                if ($(window).width() <= 768) {
                    closeMenu();
                }
            });

            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $nav.hasClass('active')) {
                    closeMenu();
                }
            });

            // Handle resize
            $(window).on('resize', function() {
                if ($(window).width() > 768) {
                    closeMenu();
                }
            });
        },

        /**
         * Smooth Scroll for anchor links
         */
        smoothScroll: function() {
            $('a[href^="#"]').on('click', function(e) {
                var target = $(this.getAttribute('href'));

                if (target.length) {
                    e.preventDefault();

                    var headerHeight = $('#nfb-header').outerHeight();
                    var adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
                    var offset = target.offset().top - headerHeight - adminBarHeight - 20;

                    $('html, body').animate({
                        scrollTop: offset
                    }, 600, 'swing');
                }
            });
        },

        /**
         * Rassegna Toggle (Show More/Less)
         */
        rassegnaToggle: function() {
            var $toggle = $('#nfb-rassegna-toggle');
            var $hiddenItems = $('.nfb-rassegna-hidden');
            var $moreText = $toggle.find('.nfb-toggle-more');
            var $lessText = $toggle.find('.nfb-toggle-less');

            if (!$toggle.length || !$hiddenItems.length) {
                return;
            }

            $toggle.on('click', function() {
                var isExpanded = $(this).data('expanded');

                if (isExpanded) {
                    // Hide items
                    $hiddenItems.slideUp(300);
                    $moreText.show();
                    $lessText.hide();
                    $(this).data('expanded', false);

                    // Scroll to section
                    var $section = $('#rassegna');
                    if ($section.length) {
                        var headerHeight = $('#nfb-header').outerHeight();
                        var adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
                        $('html, body').animate({
                            scrollTop: $section.offset().top - headerHeight - adminBarHeight - 20
                        }, 300);
                    }
                } else {
                    // Show items
                    $hiddenItems.slideDown(300);
                    $moreText.hide();
                    $lessText.show();
                    $(this).data('expanded', true);
                }
            });
        },

        /**
         * Eventi Toggle (Past Events)
         */
        eventiToggle: function() {
            var $toggle = $('#nfb-eventi-toggle');
            var $pastEvents = $('#nfb-eventi-past');
            var $moreText = $toggle.find('.nfb-toggle-more');
            var $lessText = $toggle.find('.nfb-toggle-less');

            if (!$toggle.length || !$pastEvents.length) {
                return;
            }

            $toggle.on('click', function() {
                var isExpanded = $(this).data('expanded');

                if (isExpanded) {
                    // Hide past events
                    $pastEvents.slideUp(400);
                    $moreText.show();
                    $lessText.hide();
                    $(this).data('expanded', false);
                } else {
                    // Show past events
                    $pastEvents.slideDown(400);
                    $moreText.hide();
                    $lessText.show();
                    $(this).data('expanded', true);
                }
            });
        },

        /**
         * Header shrink on scroll (optional)
         */
        headerShrink: function() {
            var $header = $('#nfb-header');
            var scrollThreshold = 50;

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > scrollThreshold) {
                    $header.addClass('nfb-header-scrolled');
                } else {
                    $header.removeClass('nfb-header-scrolled');
                }
            });
        },

        /**
         * Scroll to Top Button
         */
        scrollToTop: function() {
            var $scrollBtn = $('#nfb-scroll-top');
            var scrollThreshold = 300;

            if (!$scrollBtn.length) {
                return;
            }

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > scrollThreshold) {
                    $scrollBtn.addClass('visible');
                } else {
                    $scrollBtn.removeClass('visible');
                }
            });
        },

        /**
         * Scroll Reveal Animations
         */
        scrollReveal: function() {
            // Add animation classes to elements
            $('.nfb-sinossi-text').addClass('nfb-animate nfb-animate-left');
            $('.nfb-sinossi-cover').addClass('nfb-animate nfb-animate-right');
            $('.nfb-section-title').addClass('nfb-animate nfb-animate-up');
            $('.nfb-section-content').addClass('nfb-animate nfb-animate-up');
            $('.nfb-rassegna-item').addClass('nfb-animate');
            $('.nfb-evento-card').addClass('nfb-animate');
            $('.nfb-footer-content').addClass('nfb-animate nfb-animate-up');

            // Check if Intersection Observer is supported
            if (!('IntersectionObserver' in window)) {
                // Fallback: show all elements immediately
                $('.nfb-animate').addClass('nfb-visible');
                return;
            }

            // Create observer
            var observerOptions = {
                root: null,
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
            };

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('nfb-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all animated elements
            $('.nfb-animate').each(function() {
                observer.observe(this);
            });
        }
    };

    // Make NFBLanding available globally if needed
    window.NFBLanding = NFBLanding;

})(jQuery);
