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

		if ( ! mainImage || ! thumbnails.length ) {
			return;
		}

		thumbnails.forEach( function ( thumbnail ) {
			thumbnail.addEventListener( 'click', function () {
				thumbnails.forEach( function ( item ) {
					item.classList.remove( 'is-active' );
					item.setAttribute( 'aria-pressed', 'false' );
				} );

				mainImage.src = thumbnail.getAttribute( 'data-gallery-src' );
				mainImage.alt = thumbnail.getAttribute( 'data-gallery-alt' ) || '';
				thumbnail.classList.add( 'is-active' );
				thumbnail.setAttribute( 'aria-pressed', 'true' );
			} );
		} );
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
		var currentSlide = 0;
		var pointerStart = null;

		if ( ! track || slides.length < 2 ) {
			return;
		}

		var updateCarousel = function () {
			var isMobile = mobileQuery.matches;

			track.style.transform = isMobile ? 'translateX(-' + ( currentSlide * 100 ) + '%)' : '';

			slides.forEach( function ( slide, index ) {
				slide.setAttribute( 'aria-hidden', isMobile && index !== currentSlide ? 'true' : 'false' );
			} );

			dots.forEach( function ( dot, index ) {
				if ( index === currentSlide ) {
					dot.setAttribute( 'aria-current', 'true' );
				} else {
					dot.removeAttribute( 'aria-current' );
				}
			} );
		};

		var goToSlide = function ( index ) {
			currentSlide = ( index + slides.length ) % slides.length;
			updateCarousel();
		};

		previousButton.addEventListener( 'click', function () {
			goToSlide( currentSlide - 1 );
		} );

		nextButton.addEventListener( 'click', function () {
			goToSlide( currentSlide + 1 );
		} );

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goToSlide( parseInt( dot.getAttribute( 'data-gameplay-dot' ), 10 ) );
			} );
		} );

		carousel.addEventListener( 'keydown', function ( event ) {
			if ( ! mobileQuery.matches ) {
				return;
			}

			if ( 'ArrowLeft' === event.key ) {
				goToSlide( currentSlide - 1 );
			}

			if ( 'ArrowRight' === event.key ) {
				goToSlide( currentSlide + 1 );
			}
		} );

		carousel.addEventListener( 'pointerdown', function ( event ) {
			if ( mobileQuery.matches && 'mouse' !== event.pointerType ) {
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

			goToSlide( currentSlide + ( distance < 0 ? 1 : -1 ) );
		} );

		if ( mobileQuery.addEventListener ) {
			mobileQuery.addEventListener( 'change', updateCarousel );
		} else {
			mobileQuery.addListener( updateCarousel );
		}

		updateCarousel();
	} );

	document.querySelectorAll( '[data-details-carousel]' ).forEach( function ( carousel ) {
		var track = carousel.querySelector( '[data-details-track]' );
		var slides = carousel.querySelectorAll( '[data-details-slide]' );
		var previousButton = carousel.querySelector( '[data-details-prev]' );
		var nextButton = carousel.querySelector( '[data-details-next]' );
		var dots = carousel.querySelectorAll( '[data-details-dot]' );
		var currentSlide = 0;
		var pointerStart = null;

		if ( ! track || slides.length < 2 ) {
			return;
		}

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
		};

		var goToSlide = function ( index ) {
			currentSlide = ( index + slides.length ) % slides.length;
			updateCarousel();
		};

		previousButton.addEventListener( 'click', function () {
			goToSlide( currentSlide - 1 );
		} );

		nextButton.addEventListener( 'click', function () {
			goToSlide( currentSlide + 1 );
		} );

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goToSlide( parseInt( dot.getAttribute( 'data-details-dot' ), 10 ) );
			} );
		} );

		carousel.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				goToSlide( currentSlide - 1 );
			}

			if ( 'ArrowRight' === event.key ) {
				goToSlide( currentSlide + 1 );
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

			if ( Math.abs( distance ) >= 40 ) {
				goToSlide( currentSlide + ( distance < 0 ? 1 : -1 ) );
			}
		} );

		updateCarousel();
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
