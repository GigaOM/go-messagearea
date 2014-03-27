<div id="go-messagearea">
	<?php
	$messages = go_messagearea()->get();

	if ( $messages )
	{
		foreach ( $messages as $priority => $items )
		{
			foreach ( $items as $item )
			{
				if ( ! isset( $item['contents'] ) || ! isset( $item['type'] ) )
				{
					continue;
				}//end if

				$data = array(
					'data-priority' => $priority,
					'data-type' => $item['type'],
				);

				$message_id = isset( $item['id'] ) ? $item['id'] : NULL;
				if ( $message_id )
				{
					$data['data-id'] = $message_id;
				}//end if

				?>
				<div
					class="go-messagearea-message type-<?php echo esc_attr( $item['type'] ); ?>"
					<?php
					foreach ( $data as $key => $value )
					{
						echo esc_attr( $key ) . '="' . esc_attr( $value ) . '"' . "\n";
					}//end foreach
					?>
				>
					<?php echo wp_kses_post( $item['contents'] ); ?>
				</div>
				<?php
			}//end foreach
		}//end foreach
	}//end if
	?>
</div>
