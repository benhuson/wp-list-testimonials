=== WP List Testimonials  ===
Contributors: Ben Huson
Donate link: http://www.benhuson.co.uk/wordpress-plugins/wp-list-testimonials/
Tags: blogroll, links, testimonials, quotes, blockquotes
Requires at least: 2.5
Tested up to: 2.8
Stable tag: 1.2

Outputs testimonials using information from your blogroll links.

== Description ==
Provides a PHP function `wp_list_testimonials` to output your blogroll in the format of testimonials using `<blockquote>` and `<cite>` tags.

It uses the notes field of the blogroll link as the main quote, the link name as the cite, and the link description as additional information following the cite if provided. 

The function accepts the same arguments as the `get_bookmarks` WordPress function. 

== Installation ==
1. Download the archive file and uncompress it.
2. Put the "wp_list_testimonials" folder in "wp-content/plugins"
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


== License ==

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

http://www.gnu.org/licenses/gpl.html
