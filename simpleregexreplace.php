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

/*  Copyright 2010  Greg Gannicott  (email : greg@gannicott.co.uk)

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


// Include the config file
require_once("wp-config.php");

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
   
   // Prepare patterns to search for
   $patterns = array(
       '/(incident) ([0-9]{1,6})/i'
       , '/inc ([0-9]{1,6})/i'
       , '/(issue) ([0-9]{1,6})/i'
       , '/(problem) ([0-9]{1,6})/i'
       , '/p([0-9]{1,6})/i'
       , '/(change) ([0-9]{1,6})/i'
       , '/(change request) ([0-9]{1,6})/i'
   );

   // Prepare replacements for when pattern is matched.
   $replacements = array(
       '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'         // incident xxxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$1">incident $1</a>'       // inc xxxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=iss+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'      // issue xxxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'       // problem xxxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$1">problem $1</a>'  // pxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=chg+SKIPLIST=1+QBE.EQ.chg_ref_num=$2">$1 $2</a>'  // change xxxxxx
       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=chg+SKIPLIST=1+QBE.EQ.chg_ref_num=$2">$1 $2</a>'  // change request xxxxxx
   );

   // Perform the replacement
   $content = preg_replace($patterns, $replacements, $content);

   // Return the updated content
   return $content;
}

/** Admin Page **/

add_action('admin_menu', 'simple_regex_replace_menu');

function simple_regex_replace_menu() {

  add_options_page('Simple Regex Replace Options', 'Simple Regex Replace', 'manage_options', basename(__FILE__), 'simple_regex_replace_options_page');

}

function simple_regex_replace_options_page() {

   // Read in the list of existing regexs
   $simple_regex_replace_options = get_option('simple_regex_replace_options');

   // Handle any form action which has taken place (eg. an addition or edit)

   // ADD
   if (isset($_POST['simple_regex_replace_action']) && $_POST['simple_regex_replace_action'] == 'add') {
      $new = array();
      $new['id'] = time();
      $new['description'] = $_POST['simple_regex_replace_description'];
      $new['pattern'] = $_POST['simple_regex_replace_pattern'];
      $new['replace'] = $_POST['simple_regex_replace_replace'];

      // Add the new entry:
      $simple_regex_replace_options['simple_regex_replace'][$new['id']] = $new;
      update_option('simple_regex_replace_options',$simple_regex_replace_options);
      echo "<div id=\"message\" class=\"updated fade\"><p>SimpleRegexReplace Options Updated</p></div>\n";
   }

   // EDIT
   if (isset($_POST['simple_regex_replace_action']) && $_POST['simple_regex_replace_action'] == 'update') {

   }

   // DELETE
   if (isset($_GET['simple_regex_replace_action']) && $_GET['simple_regex_replace_action'] == 'delete') {

   }

   echo '<div class="wrap">';

      // Title (wp standard is 'h2')
      echo '<h2>SimpleRegexReplace Options</h2>';

      echo '<h3>Add New Entry</h3>';

      // Start the form
      echo '<form name="simple_regex_replace_options" method="post">';

         // Include two hidden fields which automatically help to check that the user can update options and also redirect the user back
         wp_nonce_field('update-options');

         // Start the table -- this uses a standard look n feel for WP
         print '<table class="form-table">';

            // Description
            print '<tr valign="top">';
            print '<th scope="row">Description</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_description" value="'.get_option('simple_regex_replace_description').'" /></td>';
            print '</tr>';

            // Pattern to Match
            print '<tr valign="top">';
            print '<th scope="row">Pattern to Match</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_pattern" value="'.get_option('simple_regex_replace_pattern').'" /></td>';
            print '</tr>';

            // Replace With
            print '<tr valign="top">';
            print '<th scope="row">Replace With</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_replace" value="'.get_option('simple_regex_replace_replace').'" /></td>';
            print '</tr>';

         print '</table>';

         // Required:
         print '<input type="hidden" name="simple_regex_replace_action" value="add" />';

         // Contains a list of all the options that should be saved to the db (comma seperated)
         print '<input type="hidden" name="page_options" value="simple_regex_replace_description, simple_regex_replace_pattern, simple_regex_replace_replace   " />';

         print '<p class="submit">';
         print '<input type="submit" class="button-primary" value="Add" />'; // the e_() handles localization
         print '</p>'
         ;
      print '</form>';

      // List existing entries:

      echo '<h3>Existing Entries</h3>';

      // Create the HTML to display all entries
      $allHTML = "";
      foreach($searchReplace_options['simple_regex_replace'] as $entry) {
         $html = "<tr><td>".$entry['description']."</td>";
         $html = "<tr><td>".$entry['pattern']."</td>";
         $html = "<tr><td>".$entry['replace']."</td>";
         $html = "<tr><td>Options</td>";
      }

      $allHTML = $html;

      print '<table class="widefat" width="100%" cellpadding="5px">
         <tr>
            <th width="50%">Description</th>
            <th width="20%%">Pattern</th>
            <th width="20%">Replace</th>
            <th width="10%">Options</th>
         </tr>
         ' .$allHTML. '
      </table>';

      // BLAh

   print '</div>';

}

?>