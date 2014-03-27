<?php

class GO_Messagearea
{
	private $messages = array();
	private $plugin_url;
	private $script_config;

	/**
	 * constructor!
	 */
	public function __construct()
	{
		$this->plugin_url = plugins_url( '/', __FILE__ );
		do_action( 'debug_robot', print_r( $this->plugin_url, TRUE ) );

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		add_action( 'go_messagearea_add', array( $this, 'add' ), 10, 2 );
		add_action( 'go_messagearea_replace', array( $this, 'replace' ), 10, 2 );
		add_action( 'go_messagearea_remove', array( $this, 'remove' ), 10, 2 );

		// this is priority 1 because it is the filter that starts it all
		add_filter( 'go_messagearea_get', array( $this, 'get' ), 1, 2 );
	}//end __construct

	/**
	 * hooked to the init action
	 */
	public function init()
	{
		wp_register_script(
			'go-messagearea',
			"{$this->plugin_url}js/go-messagearea.js",
			array( 'jquery' ),
			$this->script_config( 'version' ),
			TRUE
		);
	}//end init

	/**
	 * initializes widgets
	 */
	public function widgets_init()
	{
		require_once __DIR__ . '/class-go-messagearea-widget.php';
		register_widget( 'GO_Messagearea_Widget' );
	}//end widgets_init

	/**
	 * hooked to the go_messagearea_add action to tack on messages for display
	 */
	public function add( $args, $priority = 10 )
	{
		if ( ! isset( $args['contents'] ) || ! isset( $args['id'] ) )
		{
			return;
		}//end if

		$priority = intval( $priority );

		if ( ! isset( $this->messages[ $priority ] ) )
		{
			$this->messages[ $priority ] = array();
		}//end if

		$this->messages[ $priority ][ $args['id'] ] = $args;
	}//end add

	/**
	 * hooked to the go_messagearea_replace action to replace all messages with the provided message
	 */
	public function replace( $args, $priority = 10 )
	{
		if ( ! isset( $args['contents'] ) || ! isset( $args['id'] ) )
		{
			return;
		}//end if

		$this->messages = array();
		$this->add( $args, $priority );
	}//end replace

	/**
	 * gets the message stack
	 */
	public function get( $messages, $priority = NULL )
	{
		if ( $priority )
		{
			$priority = intval( $priority );

			return isset( $this->messages[ $priority ] ) ? $this->messages[ $priority ] : array();
		}//end if

		return $this->messages;
	}//end get

	/**
	 * removes a message from the message stack
	 */
	public function remove( $id, $priority = NULL )
	{
		if ( $priority )
		{
			$priority = intval( $priority );

			if ( isset( $this->messages[ $priority ] ) && isset( $this->messages[ $priority ][ $id ] ) )
			{
				unset( $this->messages[ $priority ][ $id ] );
			}//end if

			return;
		}//end if

		foreach ( $this->messages as $priority => $messages )
		{
			if ( isset( $messages[ $id ] ) )
			{
				unset( $this->messages[ $priority ][ $id ] );
				return;
			}//end if
		}//end foreach
	}//end remove

	/**
	 * lazy load the script config
	 */
	private function script_config( $key = NULL )
	{
		if ( ! isset( $this->script_config ) )
		{
			$this->script_config = apply_filters( 'go_config', array( 'version' => 1 ), 'go-script-version' );
		}//end if

		if ( $key )
		{
			return isset( $this->script_config[ $key ] ) ? $this->script_config[ $key ] : NULL;
		}//end if

		return $this->script_config;
	}//end script_config
}//end class

/**
 * singleton
 */
function go_messagearea()
{
	global $go_messagearea;

	if ( ! $go_messagearea )
	{
		$go_messagearea = new GO_Messagearea;
	}//end if

	return $go_messagearea;
}//end go_messagearea
