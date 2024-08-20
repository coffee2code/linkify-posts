<?php
/**
 * Linkify Posts plugin widget code
 *
 * Copyright (c) 2011-2025 by Scott Reilly (aka coffee2code)
 *
 * @package Linkify_Posts_Widget
 * @author  Scott Reilly
 * @version 005
 */

defined( 'ABSPATH' ) or die();

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'linkify-widget.php' );

if ( class_exists( 'WP_Widget' ) && ! class_exists( 'c2c_LinkifyPostsWidget' ) ) :

class c2c_LinkifyPostsWidget extends c2c_LinkifyWidget {

	/**
	 * Returns the version of the widget.
	 *
	 * @since 004
	 */
	public static function version() {
		return '005';
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$config = array(
			// input can be 'checkbox', 'multiselect', 'select', 'short_text', 'text', 'textarea', 'hidden', or 'none'
			// datatype can be 'array' or 'hash'
			// can also specify input_attributes
			'title' => array(
				'input'   => 'text',
				'default' => __( 'Posts', 'linkify-posts' ),
				'label'   => __( 'Title', 'linkify-posts' ),
			),
			'posts' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Posts', 'linkify-posts' ),
				'help'    => __( 'A single post ID/slug, or multiple post IDs/slugs defined via a comma-separated and/or space-separated string.', 'linkify-posts' ),
			),
			'before' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before text', 'linkify-posts' ),
				'help'    => __( 'Text to display before all posts.', 'linkify-posts' ),
			),
			'after' => array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'After text', 'linkify-posts' ),
				'help'    => __( 'Text to display after all posts.', 'linkify-posts' ),
			),
			'between' =>  array(
				'input'   => 'text',
				'default' => ', ',
				'label'   => __( 'Between posts', 'linkify-posts' ),
				'help'    => __( 'Text to appear between posts.', 'linkify-posts' ),
			),
			'before_last' =>  array(
				'input'   => 'text',
				'default' => '',
				'label'   => __( 'Before last post', 'linkify-posts' ),
				'help'    => __( 'Text to appear between the second-to-last and last element, if not specified, \'between\' value is used.', 'linkify-posts' ),
			),
			'none' =>  array(
				'input'   => 'text',
				'default' => __( 'No posts specified to be displayed', 'linkify-tags' ),
				'label'   => __( 'None text', 'linkify-posts' ),
				'help'    => __( 'Text to appear when no posts have been found.  If blank, then the entire function doesn\'t display anything.', 'linkify-posts' ),
			),
		);

		parent::__construct(
			'linkify_posts',
			__( 'Linkify Posts', 'linkify-posts' ),
			__( 'Converts a list of posts (by slug or ID) into links to those posts.', 'linkify-posts' ),
			$config
		);
	}

	/**
	 * Outputs the main content within the body of the widget.
	 *
	 * @since 005
	 *
	 * @param array $args Widget args.
	 * @param array $instance Widget instance.
	 */
	public function widget_content( $args, $instance ) {
		extract( $args );
		c2c_linkify_posts( $posts, $before, $after, $between, $before_last, $none );
	}

} // end class c2c_LinkifyPostsWidget

add_action( 'widgets_init', array( 'c2c_LinkifyPostsWidget', 'register_widget' ) );

endif; // end if !class_exists()
