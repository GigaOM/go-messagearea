go-messagearea
==============

Provide a hook-able message area

## Instructions

Adding this widget to your layout gives you an area to inject messages
(sorted by priority) onto your site.  Here's how:

### Add a message

__PHP__

```php
$message = array(
	'id' => 'bacon-is-tasty',
	'type' => 'success',
	'contents' => '<h2>Bacon is tasty!</h2><p>This is a known fact.</p>',
);

$priority = 10;

// inserts the message
do_action( 'go_messagearea_add', $message, $priority );

// replaces all messages with the provided message
do_action( 'go_messagearea_replace', $message, $priority );
```

__JS__

```js
var message = {
	id: 'bacon-is-tasty',
	type: 'success',
	contents: '<h2>Bacon is tasty!</h2><p>This is a known fact.</p>'
};

var priority = 10;

// inserts a message
$( document ).trigger( 'go-messagearea-add', message, priority );
// or
go_messagearea.insert( message, priority );

// replaces messages with provided message
$( document ).trigger( 'go-messagearea-replace', message, priority );
// or
go_messagearea.replace( message, priority );
```

### Remove messages

__PHP__

```php
// delete any message with the provided message id
do_action( 'go_messagearea_remove', $message_id );

// delete any message with the provided message id in the specific priority order
do_action( 'go_messagearea_remove', $message_id, $priority );
```

__JS__

```js
$( document ).trigger( 'go-messagearea-remove', message_code );

// or

go_messagearea.remove( message_code );
```

### Get messages

__PHP__

```php
$messages = apply_filters( 'go_messagearea_get', array() );
```

__JS__

```js
console.log( go_messagearea.messages );
```
