<?php

class GO_Messagearea_Widget extends WP_Widget
{
	/**
	 * constructor!
	 */
	public function __construct()
	{
		$widget_ops = array(
			'classname'   => 'widget-go-messagearea-widget',
			'description' => 'Hookable message area',
		);

		parent::__construct( 'go-messagearea-widget', 'GO Message Area', $widget_ops );
	}//end __construct

	/**
	 * renders the widget
	 *
	 * @param array $args
	 * @param array $unused_instance The widget option
	 */
	public function widget( $args, $unused_instance )
	{
		$messages = apply_filters( 'go_messagearea_get', array() );

		wp_localize_script( 'go-messagearea', 'go_messagearea', array( 'messages' => $messages ) );
		wp_enqueue_script( 'go-messagearea' );

		if ( count( $messages ) )
		{
			$args['before_widget'] = str_replace( 'class="', 'class="has-messages ', $args['before_widget'] );
		}//end if

		echo $args['before_widget'];
		include __DIR__ . '/templates/widget-message-area.php';
		echo $args['after_widget'];
	}//end widget
}//end class
