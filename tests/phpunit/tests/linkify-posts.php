<?php

defined( 'ABSPATH' ) or die();

class Linkify_Posts_Test extends WP_UnitTestCase {

	private $post_ids = array();

	public function setUp(): void {
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

	protected function get_results( $args, $direct_call = true ) {
		ob_start();

		$function = 'c2c_linkify_posts';

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
		$this->assertEquals( '005', c2c_LinkifyPostsWidget::version() );
	}

	public function test_widget_hooks_widgets_init() {
		$this->assertEquals( 10, has_filter( 'widgets_init', array( 'c2c_LinkifyPostsWidget', 'register_widget' ) ) );
	}

	public function test_widget_made_available() {
		$this->assertContains( 'c2c_LinkifyPostsWidget', array_keys( $GLOBALS['wp_widget_factory']->widgets ) );
	}

	public function test_single_id() {
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $this->post_ids[0] ) ) );
	}

	public function test_array_of_ids() {
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $this->post_ids ) ) );
	}

	public function test_single_slug() {
		$post = get_post( $this->post_ids[0] );
		$this->assertEquals( $this->expected_output( 1 ), $this->get_results( array( $post->post_name ) ) );
	}

	public function test_array_of_slugs() {
		$post_slugs = array_map( array( $this, 'get_slug' ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_slugs ) ) );
	}

	public function test_all_empty_posts() {
		$this->assertEmpty( $this->get_results( array( '' ) ) );
		$this->assertEmpty( $this->get_results( array( array() ) ) );
		$this->assertEmpty( $this->get_results( array( array( array(), '' ) ) ) );
	}

	public function test_an_empty_post() {
		$post_ids = array_merge( array( '' ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ) ) );
	}

	public function test_all_invalid_posts() {
		$this->assertEmpty( $this->get_results( array( 99999999 ) ) );
		$this->assertEmpty( $this->get_results( array( 'not-a-post' ) ) );
	}

	public function test_an_invalid_post() {
		$post_ids = array_merge( array( 99999999 ), $this->post_ids );
		$this->assertEquals( $this->expected_output( 5 ), $this->get_results( array( $post_ids ) ) );
	}

	public function test_arguments_before_and_after() {
		$expected = '<div>' . $this->expected_output( 5 ) . '</div>';
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<div>', '</div>' ) ) );
	}

	public function test_argument_between() {
		$expected = '<ul><li>' . $this->expected_output( 5, 0, '</li><li>' ) . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '<ul><li>', '</li></ul>', '</li><li>' ) ) );
	}

	public function test_argument_before_last() {
		$before_last = ', and ';
		$expected = $this->expected_output( 4 ) . $before_last . $this->expected_output( 1, 4, ', ', 5 );
		$this->assertEquals( $expected, $this->get_results( array( $this->post_ids, '', '', ', ', $before_last ) ) );
	}

	public function test_argument_none() {
		$missing = 'No posts to list.';
		$expected = '<ul><li>' . $missing . '</li></ul>';
		$this->assertEquals( $expected, $this->get_results( array( array(), '<ul><li>', '</li></ul>', '</li><li>', '', $missing ) ) );
	}

	public function test_unsafe_markup_is_omitted() {
		$before_last = ', and ';
		$boom = 'alert("boom!");';
		$expected = $boom . $this->expected_output( 1, 0 ) . $before_last . $boom . $this->expected_output( 1, 1, "," ) . $boom;
		$this->assertEquals(
			$expected,
			$this->get_results( array(
				array_slice( $this->post_ids, 0, 2 ),
				"<script>$boom</script>",
				"<script>$boom</script>",
				"<script>$boom</script>",
				$before_last . "<script>$boom</script>"
			) )
		);
	}

	/*
	 * __c2c_linkify_posts_get_post_link()
	 */

	 public function test___c2c_linkify_posts_get_post_link() {
		$title = get_the_title( $this->post_ids[0] );
		$expected = sprintf(
			'<a href="http://example.org/?p=%d" title="View post: %s">%s</a>',
			esc_attr( $this->post_ids[0] ),
			esc_attr( $title ),
			esc_html( $title )
		);

		$this->assertEquals(
			$expected,
			__c2c_linkify_posts_get_post_link( $this->post_ids[0] )
		);
	}

	public function test___c2c_linkify_posts_get_post_link_with_invalid_id() {
		$this->assertEmpty( __c2c_linkify_posts_get_post_link( -1 ) );
	}

}
