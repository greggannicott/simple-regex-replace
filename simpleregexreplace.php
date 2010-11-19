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

// register the filter
add_filter ( 'the_content', 'filter_simpleregexreplace');

/**
 * Replaces output based on the patterns and replacements provided by the user.
 * @param <type> $content
 * @return <type>
 */
function filter_simpleregexreplace($content) {
   
//   // Prepare patterns to search for
//   $patterns = array(
//       '/(incident) ([0-9]{1,6})/i' // incident xxxxxx
//       , '/inc ([0-9]{1,6})/i' // inc xxxxxx
//       , '/(issue) ([0-9]{1,6})/i' // issue xxxxxx
//       , '/(problem) ([0-9]{1,6})/i' // problem xxxxxx
//       , '/p([0-9]{1,6})/i' // pxxxx
//       , '/(change) ([0-9]{1,6})/i' // change xxxxxx
//       , '/(change request) ([0-9]{1,6})/i' // change request xxxxxx
//   );
//
//   // Prepare replacements for when pattern is matched.
//   $replacements = array(
//       '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'         // incident xxxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$1">incident $1</a>'       // inc xxxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=iss+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'      // issue xxxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$2">$1 $2</a>'       // problem xxxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=cr+SKIPLIST=1+QBE.EQ.ref_num=$1">problem $1</a>'  // pxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=chg+SKIPLIST=1+QBE.EQ.chg_ref_num=$2">$1 $2</a>'  // change xxxxxx
//       , '<a href="http://bdhw3kf4/CAisd/pdmweb.exe?OP=SEARCH+FACTORY=chg+SKIPLIST=1+QBE.EQ.chg_ref_num=$2">$1 $2</a>'  // change request xxxxxx
//   );
//

   // Declare some variables
   $patterns = array();
   $replacements = array();

   // Read in the patterns and replacements from the database
   $simple_regex_replace_options = get_option('simple_regex_replace_options');

   // Check that we have entries.
   if (count($simple_regex_replace_options['entries']) > 0) {

      // Loop through the entries
      foreach ($simple_regex_replace_options['entries'] as $entry) {
         array_push($patterns,'/'.$entry['pattern'].'/i');  // i = case insensetive
         array_push($replacements, stripcslashes($entry['replace']));
      }

      // Perform the replacement
      $content = preg_replace($patterns, $replacements, $content);

   }

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
   if (isset($_POST['action']) && $_POST['action'] == 'add') {
      
      // Create a new entry to add to the original array
      $new = array();
      $new['id'] = time();
      $new['description'] = $_POST['simple_regex_replace_description'];
      $new['pattern'] = $_POST['simple_regex_replace_pattern'];
      $new['replace'] = $_POST['simple_regex_replace_replace'];

      // Add the new entry:
      $simple_regex_replace_options['entries'][$new['id']] = $new;
      update_option('simple_regex_replace_options',$simple_regex_replace_options);

      // Output the confirmation
      echo "<div id=\"message\" class=\"updated fade\"><p>SimpleRegexReplace Options Updated - Entry Added</p></div>\n";

   }

   // EDIT
   if (isset($_POST['action']) && $_POST['action'] == 'update') {

      // Create a copy of the original entry
      $updated = $simple_regex_replace_options['entries'][$_POST['id']];

      // Update the values of the copy
      $updated['id'] = $_POST['id'];
      $updated['description'] = $_POST['simple_regex_replace_description'];
      $updated['pattern'] = $_POST['simple_regex_replace_pattern'];
      $updated['replace'] = $_POST['simple_regex_replace_replace'];

      // Reintroduce the changes to the original array
      $simple_regex_replace_options['entries'][$_POST['id']] = $updated;

      // Apply the changes to the database
      update_option('simple_regex_replace_options',$simple_regex_replace_options);

      // Output confirmation
      echo "<div id=\"message\" class=\"updated fade\"><p>SimpleRegexReplace Options Updated - Entry Updated</p></div>\n";

   }

   // DELETE
   if (isset($_GET['action']) && $_GET['action'] == 'delete') {

      // Remove the entry from the array
      unset($simple_regex_replace_options['entries'][$_GET['id']]);

      // Apply the changes to the database
      update_option('simple_regex_replace_options',$simple_regex_replace_options);

      // Output comfirmation
      echo "<div id=\"message\" class=\"updated fade\"><p>SimpleRegexReplace Options Updated - Entry Removed</p></div>\n";
   }

   echo '<div class="wrap">';

      // Title (wp standard is 'h2')
      echo '<h2>SimpleRegexReplace Options</h2>';

      // Set Heading (either add or update)
      if (isset($_GET['action']) && $_GET['action'] == 'update') {
         echo '<h3>Update Entry</h3>';
      } else {
         echo '<h3>Add New Entry</h3>';
      }

      // Grab the values for the item we're updating
      $current = $simple_regex_replace_options['entries'][$_GET['id']];

      // Start the form
      echo '<form name="simple_regex_replace_options" method="post">';

         // Include two hidden fields which automatically help to check that the user can update options and also redirect the user back
         wp_nonce_field('update-options');

         // Start the table -- this uses a standard look n feel for WP
         print '<table class="form-table">';

            // Description
            print '<tr valign="top">';
            print '<th scope="row">Description:</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_description" value="'.$current['description'].'" /></td>';
            print '</tr>';

            // Pattern to Match
            print '<tr valign="top">';
            print '<th scope="row">Pattern to match:</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_pattern" value="'.$current['pattern'].'" /></td>';
            print '</tr>';

            // Replace With
            print '<tr valign="top">';
            print '<th scope="row">Replace with:</th>';
            print '<td><input size="50" type="text" name="simple_regex_replace_replace" value="'.$current['replace'].'" /></td>';
            print '</tr>';

         print '</table>';

         // Determine whether this form is updating or adding. Name submit button and set
         // hidden field appropriately
         print '<p class="submit">';
         if (isset($_GET['action']) && $_GET['action'] == 'update') {
            print '<input type="submit" class="button-primary" value="Update" />';
            print '<input type="hidden" name="action" value="update" />';
            print '<input type="hidden" name="id" value="'.$current['id'].'" />';
         } else {
            print '<input type="submit" class="button-primary" value="Add" />';
            print '<input type="hidden" name="action" value="add" />';
         }
         print '</p>';

      print '</form>';

      // List existing entries:

      echo '<h3>Existing Entries</h3>';

      // Create the HTML to display all entries
      $rows_html = "";
      $alt = true;
      if (count($simple_regex_replace_options['entries']) > 0) {
         foreach($simple_regex_replace_options['entries'] as $entry) {

            // Alternate the row colours:
            if ($alt) {
               $class="class='alternate'";
               $alt = false;
            } else {
               $class="class=''";
               $alt = true;
            }

            // Generate the HTML for each row:
            $rows_html .= "<tr ".$class."><td>".$entry['description']."</td>";
            $rows_html .= "<td>".stripslashes($entry['pattern'])."</td>";
            $rows_html .= "<td>".htmlentities(stripslashes($entry['replace']))."</td>";
            $rows_html .= '<td><a href="'.get_bloginfo( "wpurl").'/wp-admin/options-general.php?page='.array_pop(explode("/",__FILE__)).'&action=update&id='.$entry['id'].'">Edit</a> | <a href="'.get_bloginfo( "wpurl").'/wp-admin/options-general.php?page='.array_pop(explode("/",__FILE__)).'&action=delete&id='.$entry['id'].'">delete</a></td></tr>';
         }
      } else {
         $rows_html = '<tr><td rowspan=4>No Entries</td></tr>';
      }

      print '<fieldset class="options">';
      print '<table class="widefat" width="100%" cellpadding="5px">
         <tr>
            <th width="30%">Description</th>
            <th width="30%">Pattern to Match</th>
            <th width="30%">Replace With</th>
            <th width="10%">Options</th>
         </tr>
         ' .$rows_html. '
      </table>';
      print '</fieldset>';

   print '</div>';

}

?>