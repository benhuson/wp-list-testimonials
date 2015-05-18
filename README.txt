=== WP List Testimonials  ===
Contributors: husobj
Donate link: https://github.com/benhuson/wp-list-testimonials
Tags: blogroll, links, testimonials, quotes, blockquotes
Requires at least: 3.4
Tested up to: 3.5.2
Stable tag: 2.0.dev

Manage and display testimonials on your site.

== Description ==

> This plugin is still backward-compatible to display testimonials using information from your blogroll links.
> In future versions this functionality will be deprecated once I have developed an upgrade procedure to copy your blogroll link testimonials.

** Old Functionality **
Provides a PHP function `wp_list_testimonials` to output your blogroll in the format of testimonials using `<blockquote>` and `<cite>` tags.

It uses the notes field of the blogroll link as the main quote, the link name as the cite, and the link description as additional information following the cite if provided. 

The function accepts the same arguments as the `get_bookmarks` WordPress function. 

== Installation ==
1. Download the archive file and uncompress it.
2. Put the "wp-list-testimonials" folder in "wp-content/plugins"
3. Enable in WordPress by visiting the "Plugins" menu and activating it.

You can then implement it in your templates using `<?php wp_list_testimonials(); ?>`.
You can show just one link category `<?php echo wp_list_testimonials('category_id=6'); ?>`.

For a link to show you, when you add a link you must enter:

1. Name
2. Web Address (enter # if not required)
3. Notes (in the advanced section)

== Changelog ==

= 1.2 =
* Added link (if not #) to the name.
* Added surrounding div for styling.

= 1.1 =
* First public version, so no changes yet!
