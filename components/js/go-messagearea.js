(function( $ ) {
	go_messagearea.event = {};

	go_messagearea.init = function() {
		this.$area = $( '#go-messagearea' );
		this.$widget = this.$area.closest( '.widget' );
		this.message_count = this.$area.find( '.go-messagearea-message' ).length;

		$( document ).on( 'click', '.go-messagearea-message .go-messagearea-close', this.event.close );
		$( document ).on( 'go-messagearea-add', this.event.add );
		$( document ).on( 'go-messagearea-replace', this.event.replace );
		$( document ).on( 'go-messagearea-remove', this.event.remove );
	};

	/**
	 * adds message data to the go_messagearea.messages array
	 */
	go_messagearea.data_add = function( message, priority ) {
		if ( 'undefined' == typeof this.messages[ priority ] ) {
			this.messages[ priority ] = {};
		}//end if

		this.messages[ priority ][ message.id ] = message;
		this.message_count++;

		if ( ! this.$widget.hasClass( 'has-messages' ) ) {
			this.$widget.addClass( 'has-messages' );
		}//end if
	};

	/**
	 * removes message data from the go_messagearea.messages array
	 */
	go_messagearea.data_remove = function( message_id, priority ) {
		if ( 'undefined' == typeof this.messages[ priority ][ message_id ] ) {
			return;
		}//end if

		delete this.messages[ priority ][ message_id ];

		this.message_count--;
	};

	/**
	 * build a message for insertion into the message area
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.build = function( message, priority ) {
		if ( 'undefined' == message.contents && 'undefined' == message.text ) {
			return false;
		}//end if

		if ( ! priority ) {
			priority = 10;
		} else {
			priority = parseInt( priority, 10 );
		}//end else

		var $message = $( '<div class="go-messagearea-message twelve-columns columns"/>' );

		var contents = message.contents || message.text || '';
		$message.html( contents );

		$message.attr( 'data-priority', priority );

		if ( 'undefined' != message.type ) {
			$message.addClass( 'type-' + message.type );
			$message.attr( 'data-type', message.type );
		}//end if

		if ( 'undefined' != message.id ) {
			$message.attr( 'data-id', message.id );
		}//end if

		$message.hide();

		return $message;
	};

	/**
	 * Inject a message into the message area at the appropriate priority location.
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.insert = function( message, priority ) {
		if ( 'undefined' == message.id ) {
			return false;
		}//end if

		var $message = this.build( message, priority );
		var $messages = this.$area.find( '.go-messagearea-message' );
		var earliest_priority = 0;

		if ( ! $messages.length ) {
			this.$area.append( $message );
			$message.fadeIn( 'fast' );
			this.data_add( message, parseInt( $message.data( 'priority' ), 10 ) );
			return true;
		}//end if

		var $current = null;

		$messages.each( function() {
			$current = $( this );
			var current_priority = parseInt( $current.data( 'priority' ), 10 );

			if ( current_priority > priority ) {
				return false;
			}//end if
		});

		if ( ! $current ) {
			this.$area.prepend( $message );
		} else if ( priority >= parseInt( $current.data( 'priority' ), 10 ) ) {
			$current.after( $message );
		} else {
			$current.before( $message );
		}//end else

		$message.fadeIn( 'fast' );

		this.data_add( message, parseInt( $message.data( 'priority' ), 10 ) );

		return true;
	};

	/**
	 * Replaces ALL messages in the message area with the passed-in message
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.replace = function( message, priority ) {
		if ( 'undefined' == message.id ) {
			return false;
		}//end if

		var $message = this.build( message, priority );
		this.$area.html('').append( $message );

		$message.fadeIn( 'fast' );

		this.messages = [];
		this.data_add( message, parseInt( $message.data( 'priority' ), 10 ) );

		return true;
	};

	/**
	 * Removes a message
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.remove = function( message_id, priority ) {
		var $element = this.$area.find( 'div[data-id="' + message_id + '"]' );

		if ( priority ) {
			$element = $element.filter( '[data-priority="' + priority + '"]' );

			this.data_remove( message_id, parseInt( priority, 10 ) );
		} else {
			for ( var i in this.messages ) {
				this.data_remove( message_id, i );
			}//end for
		}//end else

		$element.fadeOut( 'fast', function() {
			$element.remove();

			if ( this.message_count <= 0 ) {
				this.message_count = 0;
				this.$widget.removeClass( 'has-messages' );
			}//end if
		});
	};

	/**
	 * Event to handle inserting messages
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.event.add = function( e, message, priority ) {
		go_messagearea.insert( message, priority );
	};

	/**
	 * Event to handle replacing messages
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.event.replace = function( e, message, priority ) {
		go_messagearea.replace( message, priority );
	};

	/**
	 * Event to handle removing messages
	 *
	 * @param message Object Message object with (at minimum) these properties: contents & type
	 * @param priority Integer Priority of the message. The lower the number, the higher on the page it'll be
	 */
	go_messagearea.event.remove = function( e, message_id, priority ) {
		go_messagearea.remove( message_id, priority );
	};

	/**
	 * Handles the closing of a message
	 */
	go_messagearea.event.close = function( e ) {
		e.preventDefault();

		var $el = $( this ).closest( '.go-messagearea-message' );
		var message_id = $el.data( 'id' );
		var priority = $el.data( 'priority' );

		go_messagearea.remove( message_id, priority );
	};
})( jQuery );

jQuery( function( $ ) {
	go_messagearea.init();
});
