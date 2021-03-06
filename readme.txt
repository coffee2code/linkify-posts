=== Linkify Posts ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: posts, post, link, linkify, archives, list, widget, template tag, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.3
Tested up to: 5.5
Stable tag: 2.3.5

Turn a string, list, or array of post IDs and/or slugs into a list of links to those posts. Provides a widget and template tag.


== Description ==

The plugin provides a widget called "Linkify Posts" as well as a template tag, `c2c_linkify_posts()`, which allow you to easily specify posts to list and how to list them. Posts are specified by either ID or slug. See other parts of the documentation for example usage and capabilities.

Particularly handy when used in conjunction with the post custom field feature of WordPress. You could define a custom field for "Related Posts" or "Additional Products" and manually list out post IDs, then utilize the function provided by this plugin to display links to those posts in a custom manner.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/linkify-posts/) | [Plugin Directory Page](https://wordpress.org/plugins/linkify-posts/) | [GitHub](https://github.com/coffee2code/linkify-posts/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `linkify-posts.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Optional: Use the `c2c_linkify_posts()` template tag in one of your templates (be sure to pass it at least the first argument indicating what post IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list). Other optional arguments are available to customize the output.
4. Optional: Use the "Linkify Posts" widget in one of the sidebar provided by your theme.


== Screenshots ==

1. The plugin's widget configuration.


== Frequently Asked Questions ==

= What happens if I tell it to list something that I have mistyped, haven't created yet, or have deleted? =

If a given ID/slug doesn't match up with an existing post then that item is ignored without error.

= How do I get items to appear as a list (using HTML tags)? =

Whether you use the template tag or the widget, specify the following information for the appropriate fields/arguments:

* Before text: `<ul><li>` (or `<ol><li>`)
* After text: `</li></ul>` (or `</li></ol>`)
* Between posts: `</li><li>`

= Does this plugin include unit tests? =

Yes.


== Template Tags ==

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

= Functions =

* `<?php c2c_linkify_posts($posts, $before = '', $after = '', $between = ', ', $before_last = '', $none = '') ?>`
Displays links to each of any number of posts specified via post IDs

= Arguments =

* `$posts`
A single post ID/slug, or multiple post IDs/slugs defined via an array, or multiple posts IDs/slugs defined via a comma-separated and/or space-separated string

* `$before`
(optional) To appear before the entire post listing (if posts exist or if 'none' setting is specified)

* `$after`
(optional) To appear after the entire post listing (if posts exist or if 'none' setting is specified)

* `$between`
(optional) To appear between posts

* `$before_last`
(optional) To appear between the second-to-last and last element, if not specified, 'between' value is used

* `$none`
(optional) To appear when no posts have been found. If blank, then the entire function doesn't display anything

= Examples =

* These are all valid calls:

`<?php c2c_linkify_posts(43); ?>`
`<?php c2c_linkify_posts("43"); ?>`
`<?php c2c_linkify_posts("hello-world"); ?>`
`<?php c2c_linkify_posts("43 92 102"); ?>`
`<?php c2c_linkify_posts("hello-world whats-cooking"); ?>`
`<?php c2c_linkify_posts("43,92,102"); ?>`
`<?php c2c_linkify_posts("hello-world, whats-cooking"); ?>`
`<?php c2c_linkify_posts("43, 92, 102"); ?>`
`<?php c2c_linkify_posts("hello-world, 92, whats-cooking"); ?>`
`<?php c2c_linkify_posts(array(43,92,102)); ?>`
`<?php c2c_linkify_posts(array("hello-world", "whats-cooking")); ?>`
`<?php c2c_linkify_posts(array("43","92","102")); ?>`

* `<?php c2c_linkify_posts("43 92"); ?>`

Outputs something like:

`<a href="https://example.com/archive/2008/01/15/some-post">Some Post</a>,
<a href="https://example.com/archive/2008/01/15/another-post">Another Post</a>`

* Assume that you have a custom field with a key of "Related Posts" that happens to have a value of "43 92" defined (and you're in-the-loop).

`<?php c2c_linkify_posts(get_post_meta($post->ID, 'Related Posts', true), "Related posts: "); ?>`

Outputs something like:

`Related posts: <a href="https://example.com/archive/2008/01/15/some-post">Some Post</a>,
<a href="https://example.com/archive/2008/01/15/another-post">Another Post</a>`

* `<ul><?php c2c_linkify_posts("43, 92", "<li>", "</li>", "</li><li>"); ?></ul>`

Outputs something like:

`<ul><li><a href="https://example.com/archive/2008/01/15/some-post">Some Post</a></li>
<li><a href="https://example.com/archive/2008/01/15/another-post">Another Post</a></li></ul>`

* `<?php c2c_linkify_posts(""); // Assume you passed an empty string as the first value ?>`

Displays nothing.

* `<?php c2c_linkify_posts("", "", "", "", "", "No posts found."); // Assume you passed an empty string as the first value ?>`

Outputs:

`No posts found.`


== Hooks ==

The plugin exposes one action for hooking.

**c2c_linkify_posts (action)**

The 'c2c_linkify_posts' hook allows you to use an alternative approach to safely invoke `c2c_linkify_posts()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

Arguments:

* same as for `c2c_linkify_posts()`

Example:

Instead of:

`<?php c2c_linkify_posts( "112, 176", 'Posts: ' ); ?>`

Do:

`<?php do_action( 'c2c_linkify_posts', "112, 176", 'Posts: ' ); ?>`


== Changelog ==

= 2.3.5 (2020-08-17) =
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add another item)
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Change: Note compatibility through WP 5.5+

= 2.3.4 (2020-05-07) =
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Update examples in documentation to use a proper example URL

= 2.3.3 (2019-11-27) =
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)
* Change: Split paragraph in README.md's "Support" section into two

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/linkify-posts/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.3.5 =
Trivial update: Restructured unit test file structure, added a TODO.md file, and noted compatibility through WP 5.5+.

= 2.3.4 =
Trivial update: Updated a few URLs to be HTTPS and noted compatibility through WP 5.4+.

= 2.3.3 =
Trivial update: modernized unit tests, created CHANGELOG.md to store historical changelog outside of readme.txt, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 2.3.2 =
Trivial update: minor hardening, added README.md, noted compatibility through WP 5.1+, updated copyright date (2019)

= 2.3.1 =
Trivial update: fixed some unit tests, noted compatibility through WP 4.7+, updated copyright date

= 2.3 =
Minor update: minor updates to widget code and unit tests; verified compatibility through WP 4.4; updated copyright date (2016).

= 2.2.3 =
Bugfix update: Prevented PHP notice under PHP7+ for widget; noted compatibility through WP 4.3+

= 2.2.2 =
Trivial update: noted compatibility through WP 4.1+ and updated copyright date

= 2.2.1 =
Trivial update: noted compatibility through WP 4.0+; added plugin icon.

= 2.2 =
Moderate update: better validate data received; added unit tests; noted compatibility through WP 3.8+

= 2.1.4 =
Trivial update: noted compatibility through WP 3.5+

= 2.1.3 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 2.1.2 =
Trivial update: noted compatibility through WP 3.3+ and minor readme.txt tweaks

= 2.1.1 =
Trivial update: noted compatibility through WP 3.2+ and minor code formatting changes (spacing)

= 2.1 =
Feature update: added widget, added Screenshots and FAQ sections to readme, noted compatibility with WP 3.1+, and updated copyright date (2011).

= 2.0 =
Significant update. Highlights: renamed plugin; renamed `linkify_post_ids()` to `c2c_linkify_posts()`; allow specifying post slug as well as ID; dropped compatibility with versions of WP older than 2.8.

= 1.5 =
Minor update. Highlights: added filter to allow alternative safe invocation of function; verified WP 3.0 compatibility.
