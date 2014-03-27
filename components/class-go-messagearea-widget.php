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
	 */
	public function widget( $args, $instance )
	{
		wp_localize_script( 'go-messagearea', 'go_messagearea', array( 'messages' => go_messagearea()->get() ) );
		wp_enqueue_script( 'go-messagearea' );

		echo $args['before_widget'];
		include __DIR__ . '/templates/widget-message-area.php';
		echo $args['after_widget'];
	}//end widget
}//end class
