# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Add namespace
* Add shortcode
* Add block
* Support a `$args`-style argument array rather than numerous explicit arguments (though this is a bit moot with PHP8 features). Obviously, maintain backward-compatibility.
  * Great opportunity to add support for an optional 'echo' arg to control if function echoes.
  * Update all documentation examples to use the new syntax
* Widget: Consider using `c2c-widget.php` if that can be dropped in.
* Support getting post IDs via a custom field if on a single page/post? (for a manual related-posts implementation)
* Support conditional before and after? (if main content is empty, don't output before/after strings; could just be 'before_none' and 'after_none', but only after argument array support is added)
* Widget: Indicate that 'posts' is required
  * Ideally, the widget UI should warn until a post is specified and enforce requirement
* Disable support of HTML by default and require its support to be enabled? (though I consider it necessary for most people's use cases)

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/linkify-posts/) or on [GitHub](https://github.com/coffee2code/linkify-posts/) as an issue or PR).