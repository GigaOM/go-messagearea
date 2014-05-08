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
	 *
	 * @param array $args requires contents (string) and id (int)
	 * @param int $priority default is set to 10, the priority of the message, used for sorting
	 * @return Null
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
	 *
	 * @param array $args requires contents (string) and id (int)
	 * @param int $priority default is set to 10, the priority of the message, used for sorting
	 * @return Null
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
	 *
	 * @param array $unused_messages this is hooked at the highest priority to initialize the messages
	 * @param int $priority default set to null, the priority of the message, used for sorting
	 * @return mixed if priority is null returns full config otherwise returns the config element indicated by the priority
	 */
	public function get( $unused_messages, $priority = NULL )
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
	 *
	 * @param int $id the array index of the message
	 * @param int $priority default set to null, the priority of the message, used for sorting
	 * @return Null
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
	 *
	 * @param string $key Optional, default to null
	 * @return mixed if key is null returns full config otherwise returns the config element indicated by the key
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
 *
 * @global GO_Messagearea $go_messagearea
 * @return GO_Messagearea
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
