<?php
/**
 * Leaderboard widget
 * 
 *
 * @since 1.0
 * @return void
 */
class AffiliateWP_Leaderboard_Widget extends WP_Widget {

    /** Constructor */
	public function __construct() {
		parent::__construct(
			'affiliatewp_leaderboard',
			__( 'Affiliate Leaderboard', 'affiliatewp-leaderboard' ),
			array( 
				'description' => __( 'Displays an affiliate leaderboard', 'affiliatewp-leaderboard' ),
			)
		);
	}

    /** @see WP_Widget::widget */
    public function widget( $args, $instance ) {

        // Variables from widget settings
		$title = apply_filters( 'widget_title', $instance[ 'title' ], $instance, $args['id'] );

        // Used by themes. Opens the widget
        echo $args['before_widget'];

        // Display the widget title
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
		}

		$defaults = array(
			'number'    => isset( $instance['number'] ) ? $instance['number'] : 5,
            'referrals' => isset( $instance['referrals'] ) && 'on' == $instance['referrals'] ? $instance['referrals'] : 'no',
            'visits'    => isset( $instance['visits'] ) && 'on' == $instance['visits'] ? $instance['visits'] : 'no',
            'earnings'  => isset( $instance['earnings'] ) && 'on' == $instance['earnings'] ? $instance['earnings'] : 'no',
            'orderby'   => isset( $instance['orderby'] ) ? $instance['orderby'] : 'no'
		);

    	// load the leaderboard
        echo affiliatewp_leaderboard_load()->show_leaderboard( $defaults );

        // Used by themes. Closes the widget
        echo $args['after_widget'];
    }

   	/** @see WP_Widget::form */
    public function form( $instance ) {
        // Set up some default widget settings.
        $defaults = array(
            'title'     => __( 'Affiliate Leaderboard', 'affiliatewp-leaderboard' ),
            'number'    => 5,
            'referrals' => 'no',
            'visits'    => 'no', // yes | no
            'earnings'  => 'no', // yes | no
            'orderby'   => 'referrals' // referrals | visits | earnings
        );

        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <!-- Title -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'affiliatewp-leaderboard' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>

        <!-- Number / Affiliates to show -->
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Affiliates To Show:', 'affiliatewp-leaderboard' ) ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo $instance['number']; ?>" />
        </p>

        <!-- Show Referrals -->
        <p>
            <input <?php checked( $instance['referrals'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'referrals' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'referrals' ) ); ?>" type="checkbox" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'referrals' ) ); ?>"><?php _e( 'Show Referrals', 'affiliatewp-leaderboard' ); ?></label>
        </p>

         <!-- Show Visits -->
        <p>
            <input <?php checked( $instance['visits'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'visits' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'visits' ) ); ?>" type="checkbox" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'visits' ) ); ?>"><?php _e( 'Show Visits', 'affiliatewp-leaderboard' ); ?></label>
        </p>

         <!-- Show Earnings -->
        <p>
            <input <?php checked( $instance['earnings'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'earnings' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'earnings' ) ); ?>" type="checkbox" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'earnings' ) ); ?>"><?php _e( 'Show Earnings', 'affiliatewp-leaderboard' ); ?></label>
        </p>

        <!-- Orderby -->
        <?php 
        $orderby = array( 'referrals', 'visits', 'earnings' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order By:', 'affiliatewp-leaderboard' ); ?></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
            <?php foreach ( $orderby as $order ) { ?>
                <option <?php selected( $instance['orderby'], $order ); ?> value="<?php echo esc_attr( $order ); ?>"><?php echo ucfirst( $order ); ?></option>
            <?php } ?>
            </select>
        </p>

    <?php }

    /** @see WP_Widget::update */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['number']    = isset( $new_instance['number'] ) ? $new_instance['number'] : '';
        $instance['referrals'] = isset( $new_instance['referrals'] ) ? $new_instance['referrals'] : '';
        $instance['visits']    = isset( $new_instance['visits'] ) ? $new_instance['visits'] : '';
        $instance['earnings']  = isset( $new_instance['earnings'] ) ? $new_instance['earnings'] : '';
        $instance['orderby']   = strip_tags( $new_instance['orderby'] );
       
        return $instance;
    } 

}

/**
 * Register Widgets
 *
 * Registers the EDD Widgets.
 *
 * @since 1.0
 * @return void
 */
function affiliatewp_leaderboard_register_widget() {
	register_widget( 'affiliatewp_leaderboard_widget' );
}
add_action( 'widgets_init', 'affiliatewp_leaderboard_register_widget' );