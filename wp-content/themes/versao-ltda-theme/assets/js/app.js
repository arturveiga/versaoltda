( function () {
	'use strict';

	document.documentElement.classList.add( 'has-js' );

	var cartForm = document.querySelector( '.cart-page__form' );

	if ( ! cartForm ) {
		return;
	}

	var updateButton = cartForm.querySelector( '.cart-page__update' );
	var cartTotal = document.querySelector( '[data-cart-total]' );
	var cartCounter = document.querySelector( '.cart-counter' );
	var submitTimer;

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
		if ( event.target.matches( '.qty' ) ) {
			updateProjectedCart();
			scheduleCartUpdate();
		}
	} );
}() );
