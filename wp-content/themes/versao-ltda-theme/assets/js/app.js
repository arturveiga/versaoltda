( function () {
	'use strict';

	document.documentElement.classList.add( 'has-js' );

	var languageDropdown = document.querySelector( '[data-language-dropdown]' );

	if ( languageDropdown ) {
		var languageToggle = languageDropdown.querySelector( '[data-language-toggle]' );
		var languageMenu = languageDropdown.querySelector( '[data-language-menu]' );

		var closeLanguageMenu = function () {
			languageMenu.hidden = true;
			languageToggle.setAttribute( 'aria-expanded', 'false' );
		};

		languageToggle.addEventListener( 'click', function () {
			var isOpen = languageMenu.hidden;

			languageMenu.hidden = ! isOpen;
			languageToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! languageDropdown.contains( event.target ) ) {
				closeLanguageMenu();
			}
		} );

		languageDropdown.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && ! languageMenu.hidden ) {
				closeLanguageMenu();
				languageToggle.focus();
			}
		} );
	}

	var headerSearch = document.querySelector( '.header-search' );

	if ( headerSearch ) {
		var searchToggle = headerSearch.querySelector( '.header-search__toggle' );
		var searchInput = headerSearch.querySelector( '.header-search__input' );

		searchToggle.addEventListener( 'click', function () {
			var isOpen = headerSearch.classList.toggle( 'is-open' );

			searchToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

			if ( isOpen ) {
				searchInput.focus();
			}
		} );

		searchInput.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				headerSearch.classList.remove( 'is-open' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
				searchToggle.focus();
			}

			if ( 'Enter' === event.key && searchInput.value.trim() ) {
				event.preventDefault();
				headerSearch.submit();
			}
		} );
	}

	document.querySelectorAll( '[data-product-gallery]' ).forEach( function ( gallery ) {
		var mainImage = gallery.querySelector( '[data-product-gallery-main]' );
		var thumbnails = gallery.querySelectorAll( '[data-gallery-src]' );
		var thumbnailsViewport = gallery.querySelector( '.product-gallery__thumbs' );
		var previousButton = gallery.querySelector( '[data-gallery-prev]' );
		var nextButton = gallery.querySelector( '[data-gallery-next]' );
		var stage = gallery.querySelector( '[data-product-gallery-stage]' );
		var dialog = gallery.querySelector( '[data-gallery-dialog]' );
		var dialogImage = gallery.querySelector( '[data-gallery-dialog-image]' );
		var dialogCounter = gallery.querySelector( '[data-gallery-dialog-counter]' );
		var dialogPreviousButton = gallery.querySelector( '[data-gallery-dialog-prev]' );
		var dialogNextButton = gallery.querySelector( '[data-gallery-dialog-next]' );
		var dialogCloseButton = gallery.querySelector( '[data-gallery-dialog-close]' );
		var activeIndex = 0;

		if ( ! mainImage ) {
			return;
		}

		var showImage = function ( index ) {
			var imageCount = thumbnails.length || 1;

			activeIndex = ( index + imageCount ) % imageCount;

			thumbnails.forEach( function ( item, itemIndex ) {
				var isActive = itemIndex === activeIndex;

				item.classList.toggle( 'is-active', isActive );
				item.setAttribute( 'aria-pressed', isActive ? 'true' : 'false' );
			} );

			if ( thumbnails.length ) {
				mainImage.src = thumbnails[ activeIndex ].getAttribute( 'data-gallery-src' );
				mainImage.alt = thumbnails[ activeIndex ].getAttribute( 'data-gallery-alt' ) || '';

				if ( thumbnailsViewport && thumbnailsViewport.scrollTo ) {
					thumbnailsViewport.scrollTo( {
						left: thumbnails[ activeIndex ].offsetLeft - ( thumbnailsViewport.clientWidth - thumbnails[ activeIndex ].offsetWidth ) / 2,
						behavior: window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ? 'auto' : 'smooth',
					} );
				}
			}

			if ( dialogImage ) {
				dialogImage.src = mainImage.src;
				dialogImage.alt = mainImage.alt;
			}

			if ( dialogCounter ) {
				dialogCounter.textContent = ( activeIndex + 1 ) + ' de ' + imageCount;
			}
		};

		thumbnails.forEach( function ( thumbnail, index ) {
			thumbnail.addEventListener( 'click', function () {
				showImage( index );
			} );
		} );

		if ( previousButton ) {
			previousButton.addEventListener( 'click', function () {
				showImage( activeIndex - 1 );
			} );
		}

		if ( nextButton ) {
			nextButton.addEventListener( 'click', function () {
				showImage( activeIndex + 1 );
			} );
		}

		if ( stage ) {
			stage.addEventListener( 'click', function () {
				if ( dialog && 'function' === typeof dialog.showModal ) {
					showImage( activeIndex );
					dialog.showModal();
					document.documentElement.classList.add( 'has-gallery-modal' );

					if ( dialogCloseButton ) {
						dialogCloseButton.focus();
					}

					return;
				}

				if ( stage.requestFullscreen ) {
					if ( document.fullscreenElement === stage ) {
						document.exitFullscreen();
						return;
					}

					stage.requestFullscreen();
				}
			} );
		}

		var closeDialog = function () {
			if ( dialog && dialog.open ) {
				dialog.close();
			}
		};

		if ( dialogPreviousButton ) {
			dialogPreviousButton.addEventListener( 'click', function () {
				showImage( activeIndex - 1 );
			} );
		}

		if ( dialogNextButton ) {
			dialogNextButton.addEventListener( 'click', function () {
				showImage( activeIndex + 1 );
			} );
		}

		if ( dialogCloseButton ) {
			dialogCloseButton.addEventListener( 'click', closeDialog );
		}

		if ( dialog ) {
			dialog.addEventListener( 'click', function ( event ) {
				if (
					event.target === dialog ||
					event.target.classList.contains( 'product-gallery-modal__content' ) ||
					event.target.classList.contains( 'product-gallery-modal__figure' )
				) {
					closeDialog();
				}
			} );

			dialog.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key ) {
					event.preventDefault();
					showImage( activeIndex - 1 );
				}

				if ( 'ArrowRight' === event.key ) {
					event.preventDefault();
					showImage( activeIndex + 1 );
				}
			} );

			dialog.addEventListener( 'close', function () {
				document.documentElement.classList.remove( 'has-gallery-modal' );

				if ( stage ) {
					stage.focus();
				}
			} );
		}

	} );

	document.querySelectorAll( '.product-information__grid' ).forEach( function ( accordion ) {
		var items = accordion.querySelectorAll( 'details' );

		items.forEach( function ( item ) {
			item.addEventListener( 'toggle', function () {
				if ( ! item.open ) {
					return;
				}

				items.forEach( function ( sibling ) {
					if ( sibling !== item ) {
						sibling.open = false;
					}
				} );
			} );
		} );
	} );

	document.querySelectorAll( '[data-gameplay-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '[data-gameplay-track]' );
		var slides = carousel.querySelectorAll( '[data-gameplay-slide]' );
		var previousButton = carousel.querySelector( '[data-gameplay-prev]' );
		var nextButton = carousel.querySelector( '[data-gameplay-next]' );
		var dots = carousel.querySelectorAll( '[data-gameplay-dot]' );
		var mobileQuery = window.matchMedia( '(max-width: 700px)' );
		var reducedMotionQuery = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		var currentSlide = 0;
		var pointerStart = null;
		var desktopAnimating = false;
		var desktopAnimationTimer = null;
		var autoplayTimer = null;

		if ( ! track || slides.length < 2 ) {
			return;
		}

		var updateDots = function () {
			dots.forEach( function ( dot, index ) {
				if ( index === currentSlide ) {
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					dot.removeAttribute( 'aria-current' );
				}
			} );
		};

		var getSlideStep = function () {
			var firstSlide = track.firstElementChild;
			var trackStyles = window.getComputedStyle( track );
			var gap = parseFloat( trackStyles.columnGap || trackStyles.gap ) || 0;

			return firstSlide ? firstSlide.getBoundingClientRect().width + gap : 0;
		};

		var updateSlideVisibility = function () {
			if ( mobileQuery.matches ) {
				slides.forEach( function ( slide, index ) {
					slide.setAttribute( 'aria-hidden', index !== currentSlide ? 'true' : 'false' );
				} );
				return;
			}

			Array.prototype.forEach.call( track.children, function ( slide, index ) {
				slide.setAttribute( 'aria-hidden', index > 2 ? 'true' : 'false' );
			} );
		};

		var updateCarousel = function () {
			var isMobile = mobileQuery.matches;
			var slideStep;

			if ( isMobile ) {
				slides.forEach( function ( slide ) {
					track.appendChild( slide );
				} );
			} else {
				slides.forEach( function ( slide, index ) {
					track.appendChild( slides[ ( currentSlide + index ) % slides.length ] );
				} );
			}

			window.clearTimeout( desktopAnimationTimer );
			desktopAnimating = false;
			track.style.transition = '';

			slideStep = getSlideStep();
			track.style.transform = isMobile ? 'translateX(-' + ( currentSlide * slideStep ) + 'px)' : 'translateX(0)';

			updateSlideVisibility();
			updateDots();
		};

		var goToSlide = function ( index ) {
			currentSlide = ( index + slides.length ) % slides.length;
			updateCarousel();
		};

		var cycleDesktop = function ( direction ) {
			var slideStep;
			var finishAnimation;

			if ( desktopAnimating || mobileQuery.matches ) {
				return;
			}

			slideStep = getSlideStep();

			if ( ! slideStep ) {
				return;
			}

			desktopAnimating = true;
			currentSlide = ( currentSlide + direction + slides.length ) % slides.length;

			finishAnimation = function ( event ) {
				if ( event && event.target !== track ) {
					return;
				}

				track.removeEventListener( 'transitionend', finishAnimation );
				window.clearTimeout( desktopAnimationTimer );

				if ( direction > 0 ) {
					track.appendChild( track.firstElementChild );
					track.style.transition = 'none';
					track.style.transform = 'translateX(0)';
					track.offsetWidth; // Force the reordered track to settle before restoring transitions.
					track.style.transition = '';
				}

				desktopAnimating = false;
				updateSlideVisibility();
				updateDots();
			};

			track.addEventListener( 'transitionend', finishAnimation );
			desktopAnimationTimer = window.setTimeout( finishAnimation, 520 );

			if ( direction < 0 ) {
				track.style.transition = 'none';
				track.insertBefore( track.lastElementChild, track.firstElementChild );
				track.style.transform = 'translateX(-' + slideStep + 'px)';
				track.offsetWidth; // Start from the inserted slide without a visible jump.
				track.style.transition = '';
			}

			window.requestAnimationFrame( function () {
				track.style.transform = direction > 0 ? 'translateX(-' + slideStep + 'px)' : 'translateX(0)';
			} );
		};

		var stopAutoplay = function () {
			window.clearInterval( autoplayTimer );
			autoplayTimer = null;
		};

		var startAutoplay = function () {
			stopAutoplay();

			if ( mobileQuery.matches || reducedMotionQuery.matches || document.hidden || carousel.matches( ':hover' ) || carousel.contains( document.activeElement ) ) {
				return;
			}

			autoplayTimer = window.setInterval( function () {
				cycleDesktop( 1 );
			}, 3600 );
		};

		previousButton.addEventListener( 'click', function () {
			if ( mobileQuery.matches ) {
				goToSlide( currentSlide - 1 );
			} else {
				cycleDesktop( -1 );
			}
			startAutoplay();
		} );

		nextButton.addEventListener( 'click', function () {
			if ( mobileQuery.matches ) {
				goToSlide( currentSlide + 1 );
			} else {
				cycleDesktop( 1 );
			}
			startAutoplay();
		} );

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goToSlide( parseInt( dot.getAttribute( 'data-gameplay-dot' ), 10 ) );
				startAutoplay();
			} );
		} );

		carousel.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				previousButton.click();
			}

			if ( 'ArrowRight' === event.key ) {
				nextButton.click();
			}
		} );

		carousel.addEventListener( 'pointerdown', function ( event ) {
			if ( 'mouse' !== event.pointerType ) {
				pointerStart = event.clientX;
			}
		} );

		carousel.addEventListener( 'pointerup', function ( event ) {
			if ( null === pointerStart ) {
				return;
			}

			var distance = event.clientX - pointerStart;
			pointerStart = null;

			if ( Math.abs( distance ) < 40 ) {
				return;
			}

			if ( distance < 0 ) {
				nextButton.click();
			} else {
				previousButton.click();
			}
		} );

		var handleCarouselModeChange = function () {
			updateCarousel();
			startAutoplay();
		};

		carousel.addEventListener( 'mouseenter', stopAutoplay );
		carousel.addEventListener( 'mouseleave', startAutoplay );
		carousel.addEventListener( 'focusin', stopAutoplay );
		carousel.addEventListener( 'focusout', function ( event ) {
			if ( ! carousel.contains( event.relatedTarget ) ) {
				startAutoplay();
			}
		} );

		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stopAutoplay();
			} else {
				startAutoplay();
			}
		} );

		if ( mobileQuery.addEventListener ) {
			mobileQuery.addEventListener( 'change', handleCarouselModeChange );
			reducedMotionQuery.addEventListener( 'change', startAutoplay );
		} else {
			mobileQuery.addListener( handleCarouselModeChange );
			reducedMotionQuery.addListener( startAutoplay );
		}

		updateCarousel();
		startAutoplay();
	} );

	document.querySelectorAll( '[data-details-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '[data-details-track]' );
		var slides = carousel.querySelectorAll( '[data-details-slide]' );
		var previousButton = carousel.querySelector( '[data-details-prev]' );
		var nextButton = carousel.querySelector( '[data-details-next]' );
		var dots = carousel.querySelectorAll( '[data-details-dot]' );
		var zoomButton = carousel.querySelector( '[data-details-zoom]' );
		var dialog = carousel.parentElement.querySelector( '[data-details-dialog]' );
		var dialogImage = dialog ? dialog.querySelector( '[data-details-dialog-image]' ) : null;
		var dialogCounter = dialog ? dialog.querySelector( '[data-details-dialog-counter]' ) : null;
		var dialogPreviousButton = dialog ? dialog.querySelector( '[data-details-dialog-prev]' ) : null;
		var dialogNextButton = dialog ? dialog.querySelector( '[data-details-dialog-next]' ) : null;
		var dialogCloseButton = dialog ? dialog.querySelector( '[data-details-dialog-close]' ) : null;
		var reducedMotionQuery = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		var currentSlide = 0;
		var pointerStart = null;
		var autoplayTimer = null;

		if ( ! track || slides.length < 2 ) {
			return;
		}

		var updateDialog = function () {
			var activeImage = slides[ currentSlide ].querySelector( 'img' );

			if ( activeImage && dialogImage ) {
				dialogImage.src = activeImage.currentSrc || activeImage.src;
				dialogImage.alt = activeImage.alt;
			}

			if ( dialogCounter ) {
				dialogCounter.textContent = ( currentSlide + 1 ) + ' de ' + slides.length;
			}
		};

		var updateCarousel = function () {
			track.style.transform = 'translateX(-' + ( currentSlide * 100 ) + '%)';

			slides.forEach( function ( slide, index ) {
				slide.setAttribute( 'aria-hidden', index !== currentSlide ? 'true' : 'false' );
			} );

			dots.forEach( function ( dot, index ) {
				if ( index === currentSlide ) {
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					dot.removeAttribute( 'aria-current' );
				}
			} );

			if ( dialog && dialog.open ) {
				updateDialog();
			}
		};

		var goToSlide = function ( index ) {
			currentSlide = ( index + slides.length ) % slides.length;
			updateCarousel();
		};

		var stopAutoplay = function () {
			window.clearInterval( autoplayTimer );
			autoplayTimer = null;
		};

		var startAutoplay = function () {
			stopAutoplay();

			if ( reducedMotionQuery.matches || document.hidden || carousel.matches( ':hover' ) || carousel.contains( document.activeElement ) ) {
				return;
			}

			autoplayTimer = window.setInterval( function () {
				goToSlide( currentSlide + 1 );
			}, 4600 );
		};

		if ( previousButton ) {
			previousButton.addEventListener( 'click', function () {
				goToSlide( currentSlide - 1 );
				startAutoplay();
			} );
		}

		if ( nextButton ) {
			nextButton.addEventListener( 'click', function () {
				goToSlide( currentSlide + 1 );
				startAutoplay();
			} );
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goToSlide( parseInt( dot.getAttribute( 'data-details-dot' ), 10 ) );
				startAutoplay();
			} );
		} );

		carousel.addEventListener( 'keydown', function ( event ) {
			if ( event.target.closest( '[data-details-dialog]' ) ) {
				return;
			}

			if ( 'ArrowLeft' === event.key ) {
				goToSlide( currentSlide - 1 );
			}

			if ( 'ArrowRight' === event.key ) {
				goToSlide( currentSlide + 1 );
			}

			startAutoplay();
		} );

		carousel.addEventListener( 'pointerdown', function ( event ) {
			if ( event.target.closest( '[data-details-dialog]' ) ) {
				return;
			}

			if ( 'mouse' !== event.pointerType ) {
				pointerStart = event.clientX;
			}
		} );

		carousel.addEventListener( 'pointerup', function ( event ) {
			if ( event.target.closest( '[data-details-dialog]' ) ) {
				pointerStart = null;
				return;
			}

			if ( null === pointerStart ) {
				return;
			}

			var distance = event.clientX - pointerStart;
			pointerStart = null;

			if ( Math.abs( distance ) >= 40 ) {
				goToSlide( currentSlide + ( distance < 0 ? 1 : -1 ) );
				startAutoplay();
			}
		} );

		carousel.addEventListener( 'mouseenter', stopAutoplay );
		carousel.addEventListener( 'mouseleave', startAutoplay );
		carousel.addEventListener( 'focusin', stopAutoplay );
		carousel.addEventListener( 'focusout', function ( event ) {
			if ( ! carousel.contains( event.relatedTarget ) ) {
				startAutoplay();
			}
		} );

		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stopAutoplay();
			} else {
				startAutoplay();
			}
		} );

		if ( reducedMotionQuery.addEventListener ) {
			reducedMotionQuery.addEventListener( 'change', startAutoplay );
		} else {
			reducedMotionQuery.addListener( startAutoplay );
		}

		var closeDialog = function () {
			if ( dialog && dialog.open ) {
				dialog.close();
			}
		};

		if ( zoomButton ) {
			zoomButton.addEventListener( 'click', function () {
				if ( dialog && 'function' === typeof dialog.showModal ) {
					stopAutoplay();
					updateDialog();
					dialog.showModal();
					document.documentElement.classList.add( 'has-details-modal' );

					if ( dialogCloseButton ) {
						dialogCloseButton.focus();
					}
				}
			} );
		}

		if ( dialogPreviousButton ) {
			dialogPreviousButton.addEventListener( 'click', function () {
				goToSlide( currentSlide - 1 );
			} );
		}

		if ( dialogNextButton ) {
			dialogNextButton.addEventListener( 'click', function () {
				goToSlide( currentSlide + 1 );
			} );
		}

		if ( dialogCloseButton ) {
			dialogCloseButton.addEventListener( 'click', closeDialog );
		}

		if ( dialog ) {
			dialog.addEventListener( 'click', function ( event ) {
				if (
					event.target === dialog ||
					event.target.classList.contains( 'details-modal__content' ) ||
					event.target.classList.contains( 'details-modal__figure' )
				) {
					closeDialog();
				}
			} );

			dialog.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key ) {
					event.preventDefault();
					event.stopPropagation();
					goToSlide( currentSlide - 1 );
				}

				if ( 'ArrowRight' === event.key ) {
					event.preventDefault();
					event.stopPropagation();
					goToSlide( currentSlide + 1 );
				}
			} );

			dialog.addEventListener( 'close', function () {
				document.documentElement.classList.remove( 'has-details-modal' );

				if ( zoomButton ) {
					zoomButton.focus();
				}

				startAutoplay();
			} );
		}

		updateCarousel();
		startAutoplay();
	} );

	var cartForm = document.querySelector( '.cart-page__form' );

	if ( ! cartForm ) {
		return;
	}

	var updateButton = cartForm.querySelector( '.cart-page__update' );
	var shippingUpdateButton = cartForm.querySelector( '[data-update-shipping]' );
	var shippingPostcode = cartForm.querySelector( '#calc_shipping_postcode' );
	var cartTotal = document.querySelector( '[data-cart-total]' );
	var cartCounter = document.querySelector( '.cart-counter' );
	var submitTimer;

	function formatPostcode( value ) {
		var digits = value.replace( /\D/g, '' ).slice( 0, 8 );

		return digits.length > 5 ? digits.slice( 0, 5 ) + '-' + digits.slice( 5 ) : digits;
	}

	if ( shippingPostcode ) {
		shippingPostcode.value = formatPostcode( shippingPostcode.value );
		shippingPostcode.addEventListener( 'input', function () {
			shippingPostcode.value = formatPostcode( shippingPostcode.value );
		} );
	}

	function formatMoney( value ) {
		var currency = cartTotal ? cartTotal.getAttribute( 'data-currency' ) || 'BRL' : 'BRL';

		return new Intl.NumberFormat( 'pt-BR', {
			style: 'currency',
			currency: currency,
		} ).format( value );
	}

	function updateProjectedCart() {
		var total = 0;
		var count = 0;

		cartForm.querySelectorAll( '[data-cart-item]' ).forEach( function ( item ) {
			var input = item.querySelector( '.qty' );
			var quantity = input ? parseFloat( input.value ) || 0 : 0;
			var unitPrice = parseFloat( item.getAttribute( 'data-unit-price' ) ) || 0;

			total += unitPrice * quantity;
			count += quantity;
		} );

		if ( cartTotal ) {
			cartTotal.textContent = formatMoney( total );
		}

		if ( cartCounter ) {
			cartCounter.textContent = String( count );
		}
	}

	function scheduleCartUpdate() {
		if ( ! updateButton ) {
			return;
		}

		window.clearTimeout( submitTimer );
		cartForm.classList.add( 'is-updating' );
		submitTimer = window.setTimeout( function () {
			updateButton.disabled = false;
			updateButton.click();
		}, 850 );
	}

	cartForm.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-cart-qty]' );

		if ( ! button ) {
			return;
		}

		var quantity = button.closest( '.cart-page__quantity' );
		var input = quantity ? quantity.querySelector( '.qty' ) : null;

		if ( ! input ) {
			return;
		}

		var current = parseFloat( input.value ) || 0;
		var step = parseFloat( input.getAttribute( 'step' ) ) || 1;
		var min = parseFloat( input.getAttribute( 'min' ) );
		var max = parseFloat( input.getAttribute( 'max' ) );
		var next = 'plus' === button.getAttribute( 'data-cart-qty' ) ? current + step : current - step;

		if ( ! Number.isNaN( min ) ) {
			next = Math.max( min, next );
		}

		if ( ! Number.isNaN( max ) && max > 0 ) {
			next = Math.min( max, next );
		}

		input.value = next;
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	} );

	cartForm.addEventListener( 'change', function ( event ) {
		if ( event.target.matches( 'input[name^="shipping_method"]' ) && shippingUpdateButton ) {
			event.stopPropagation();
			shippingUpdateButton.click();
			return;
		}

		if ( event.target.matches( '.qty' ) ) {
			updateProjectedCart();
			scheduleCartUpdate();
		}
	} );
}() );
