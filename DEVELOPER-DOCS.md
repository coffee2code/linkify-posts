# Developer Documentation

This plugin provides a [hook](#hook) and a [template tag](#template-tag).

## Template Tag

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

### Functions

* `<?php c2c_linkify_posts( $posts, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) ?>`
Displays links to each of any number of posts specified via post IDs/slugs

### Arguments

* `$posts` _(string|int|array)_
A single post ID/slug, or multiple post IDs/slugs defined via an array, or multiple post IDs/slugs defined via a comma-separated and/or space-separated string

* `$before` _(string)_
Optional. Text to appear before the entire post listing (if posts exist or if 'none' setting is specified). Default is an empty string.

* `$after` _(string)_
Optional. Text to appear after the entire post listing (if posts exist or if 'none' setting is specified). Default is an empty string.

* `$between` _(string)_
Optional. Text to appear between posts. Default is ", ".

* `$before_last` _(string)_
Optional. Text to appear between the second-to-last and last element, if not specified, 'between' value is used. Default is an empty string.

* `$none` _(string)_
Optional. Text to appear when no posts have been found. If blank, then the entire function doesn't display anything. Default is an empty string.

### Examples

These are all valid calls:

```php
<?php c2c_linkify_posts(43); ?>
<?php c2c_linkify_posts("43"); ?>
<?php c2c_linkify_posts("hello-world"); ?>
<?php c2c_linkify_posts("43 92 102"); ?>
<?php c2c_linkify_posts("hello-world whats-cooking"); ?>
<?php c2c_linkify_posts("43,92,102"); ?>
<?php c2c_linkify_posts("hello-world, whats-cooking"); ?>
<?php c2c_linkify_posts("43, 92, 102"); ?>
<?php c2c_linkify_posts("hello-world, 92, whats-cooking"); ?>
<?php c2c_linkify_posts(array(43,92,102)); ?>
<?php c2c_linkify_posts(array("hello-world", "whats-cooking")); ?>
<?php c2c_linkify_posts(array("43","92","102")); ?>
```

Though, for consistency and readability, you'd be better off sticking to specifying slugs or IDs (with a preference for the former, especially if using hardcoded values).

* `<?php c2c_linkify_posts("43 92"); ?>`

Outputs something like:

```html
<a href="https://example.com/archive/2008/01/15/some-post">Some Post</a>,
<a href="https://example.com/archive/2008/01/15/another-post">Another Post</a>
```

* Assume that you have a custom field with a key of "Related Posts" that happens to have a value of "43 92" defined (and you're in-the-loop).

`<?php c2c_linkify_posts(get_post_meta($post->ID, 'Related Posts', true), "Related posts: "); ?>`

Outputs something like:

```html
Related posts: <a href="https://example.com/archive/2008/01/15/some-post">Some Post</a>,
<a href="https://example.com/archive/2008/01/15/another-post">Another Post</a>
```

* `<ul><?php c2c_linkify_posts("43, 92", "<li>", "</li>", "</li><li>"); ?></ul>`

Outputs something like:

```html
<ul><li><a href="https://example.com/archive/2008/01/15/some-post">Some Post</a></li>
<li><a href="https://example.com/archive/2008/01/15/another-post">Another Post</a></li></ul>
```

* `<?php c2c_linkify_posts(""); // Assume you passed an empty string as the first value ?>`

Displays nothing.

* `<?php c2c_linkify_posts("", "", "", "", "", "No posts found."); // Assume you passed an empty string as the first value ?>`

Outputs:

`No posts found.`


## Hook

The plugin exposes one action for hooking.

### `c2c_linkify_posts` _(action)_

The `c2c_linkify_posts` hook allows you to use an alternative approach to safely invoke `c2c_linkify_posts()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `c2c_linkify_posts()`

#### Example:

Instead of:

`<?php c2c_linkify_posts( "43, 92", 'Posts: ' ); ?>`

Do:

`<?php do_action( 'c2c_linkify_posts', "43, 92", 'Posts: ' ); ?>`
