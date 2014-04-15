=== Gigaom Message Area ===
Contributors: borkweb
Tags: widgets, admin
Requires at least: 3.6
Tested up to: 3.8.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a widget with a PHP & JS hookable message area.

== Description ==

Adding this widget to your layout gives you an area to inject messages
(sorted by priority) onto your site.  Here's how:

__Add a message__

_PHP_

```
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

_JS_

```
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

__Remove messages__

_PHP_

```
// delete any message with the provided message id
do_action( 'go_messagearea_remove', $message_id );

// delete any message with the provided message id in the specific priority order
do_action( 'go_messagearea_remove', $message_id, $priority );
```

_JS_
```
$( document ).trigger( 'go-messagearea-remove', message_code );

// or

go_messagearea.remove( message_code );
```

__Get messages__

_PHP_

```
$messages = apply_filters( 'go_messagearea_get', array() );
```

_JS_

```
console.log( go_messagearea.messages );
```

=== Links ===

* [WordPress plugin page](http://wordpress.org/plugins/go-messagearea/)
* [GitHub repo](https://github.com/GigaOM/go-messagearea)

== Installation ==

1. Upload `go-messagearea` to the `/wp-content/plugins/` directory
1. Activate 'Gigaom MessageArea' through the 'Plugins' menu in WordPress

== Contributing ==

This plugin is developed and [available on GitHub](https://github.com/GigaOM/go-messagearea). Contributions and questions are welcome!
