<?php

defined( 'ABSPATH' ) or die();

class Linkify_Posts_Test extends WP_UnitTestCase {

	private $post_ids = array();

	public function setUp() {
		parent::setUp();
		$this->post_ids = $this->factory->post->create_many( 5 );
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function get_slug( $post_id ) {
		return get_post( $post_id )->post_name;
	}

	/**
	 * Returns the expected output.
	 *
	 * @param int    $count      The number of posts to list.
	 * @param int    $post_index Optional. The index into the $post_ids array to start at. Default 0.
	 * @param string $between    Optional. The string to appear between posts. Default ', '.
	 * @param int    $post_num   Optional. The post number. Default 1.
	 * @return string
	 */
	protected function expected_output( $count, $post_index = 0, $between = ', ', $post_num = 1 ) {
		$str = '';
		for ( $n = 1; $n <= $count; $n++, $post_index++ ) {
			if ( ! empty( $str ) ) {
				$str .= $between;
			}
			$post = get_post( $this->post_ids[ $post_index ] );
			$str .= '<a href="http://example.org/?p=' . $post->ID . '" title="View post: ' . $post->post_title . '">' . $post->post_title . '</a>';
		}
		return $str;
	}

	protected function get_results( $args, $direct_call = true, $use_deprecated = false ) {
		ob_start();

		$function = $use_deprecated ? 'linkify_post_ids' : 'c2c_linkify_posts';

		if ( $direct_call ) {
			call_user_func_array( $function, $args );
		} else {
			do_action_ref_array( $function, $args );
		}

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}


	//
	//
	// TESTS
	//
	//


	public function test_widget_class_name() {
		$this->assertTrue( class_exists( 'c2c_LinkifyPostsWidget' ) );
	}

	public function test_widget_version() {
		$this->assertEquals( '004', c2c_LinkifyPostsWidget::version() );
	}

	public function test_widget_hooks_widgets_init() {
		$this->assertEquals( 10, has_filter( 'widgets_init', array( 'c2c_LinkifyPostsWidget', 'register_widget' ) ) );
	}

	public function test_widget_made_available() {
		$this->assertContains( 'c2c_LinkifyPostsWidget', array_keys( $GLOBALS['wp_widget_factory']->widgets ) );
	}

	public function test_single_id() {
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->post_ids[0] ) ) );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->post_ids[0], false ) ) );
	}

	public function test_array_of_ids() {
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->post_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->post_ids ), false ) );
	}

	public function test_single_slug() {
		$post = get_post( $this->post_ids[0] );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $post->post_name ) ) );
	}

	public function test_array_of_slugs() {
		$post_slugs = array_map( array( $this, 'get_slug' ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_slugs ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_slugs ), false ) );
	}

	public function test_all_empty_posts() {
		$this->assertEmpty( $this->get_results( array( '' ) ) );
		$this->assertEmpty( $this->get_results( array( array() ) ) );
		$this->assertEmpty( $this->get_results( array( array( array(), '' ) ) ) );
	}

	public function test_an_empty_post() {
		$post_ids = array_merge( array( '' ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ), false ) );
	}

	public function test_all_invalid_posts() {
		$this->assertEmpty( $this->get_results( array( 99999999 ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-a-post' ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-a-post' ), false ) );
	}

	public function test_an_invalid_post() {
		$post_ids = array_merge( array( 99999999 ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ) ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ), false ) );
	}

	public function test_arguments_before_and_after() {
		$expected = '<div>' . $this->expected_output( 5 ) . '</div>';
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<div>', '</div>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<div>', '</div>' ), false ) );
	}

	public function test_argument_between() {
		$expected = '<ul><li>' . $this->expected_output( 5, 0, '</li><li>' ) . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<ul><li>', '</li></ul>', '</li><li>' ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<ul><li>', '</li></ul>', '</li><li>' ), false ) );
	}

	public function test_argument_before_last() {
		$before_last = ', and ';
		$expected = $this->expected_output( 4 ) . $before_last . $this->expected_output( 1, 4, ', ', 5 );
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '', '', ', ', $before_last ) ) );
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '', '', ', ', $before_last ), false ) );
	}

	public function test_argument_none() {
		$missing = 'No posts to list.';
		$expected = '<ul><li>' . $missing . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ) ) );
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ), false ) );
	}

	/**
	 * @expectedDeprecated linkify_post_ids
	 */
	public function test_deprecated_function() {
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->post_ids[0] ), true, true ) );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->post_ids ), true, true ) );
		$post = get_post( $this->post_ids[0] );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $post->post_name ), true, true ) );
		$post_slugs = array_map( array( $this, 'get_slug' ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_slugs ), true, true ) );
	}

}
