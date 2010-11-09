<?php
/*
Plugin Name: SimpleRegexReplace
Plugin URI: http://greg.gannicott.co.uk
Description: Replaces content of posts based on regex set by the user.
Version: 0.1
Author: Greg Gannicott
Author URI: http://greg.gannicott.co.uk
License: GPL2
*/
?>
<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

// register the filter
add_filter ( 'the_content', 'filter_simpleregexreplace');

/**
 * Replaces output based on the patterns and replacements provided by the user.
 * @param <type> $content
 * @return <type>
 */
function filter_simpleregexreplace($content) {
   $patterns = array('/(incident) ([0-9]{1,6})/i');
   $replacements = array('<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>');
   $content = preg_replace($patterns, $replacements, $content);
   return $content;
}
?>