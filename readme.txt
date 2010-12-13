=== Plugin Name ===
Contributors: greggannicott
Donate link: http://example.com/
Tags: regex, regular expressions, replace
Requires at least: 3.0
Tested up to: 3.02
Stable tag: trunk

This plugin enables you to convert the output of your posts based on custom written regular expressions.

== Description ==

With SimpleRegexReplace you can write regular expressions against the output of your posts, enabling you to replace text on the fly.

Example use cases include:

* Use case 1
* Use case 2
* Use case 3
* Use case 4

== Installation ==

1. Download the plugin and unzip it.
1. Upload the simpleregexreplace folder to the /wp-content/plugins/ directory of your web site.
1. Activate the plugin in WordPress Admin.
1. To manage your replacements, go to 'Settings -> Simple Regex Replace' in WordPress Admin.

== Frequently Asked Questions ==

= How do I create my first replacement? =

1. Login to your WordPress Admin
1. Go to 'Settings -> Simple Regex Replace'
1. Enter a description that best describes your new entry (eg. "INSERT EXAMPLE")
1. Enter the pattern you would like to check for (eg. "INSERT EXAMPLE")
1. Enter the replacement text. (eg. "INSERT EXAMPLE") To include the first matched item in your 'Pattern to match', use "\1", and "\2" for your second item etc.
1. Click 'Add'

= How do I update an existing item? =

= How do I delete an existing item? =

= Which regex engine does this plugin use? =

The PHP function ([preg_replace](http://php.net/manual/en/function.preg-replace.php)) is used in this plugin, which in turn uses ([PCRE](http://php.net/manual/en/book.pcre.php)). Once you know this, you can find tutorials to help you write regular expressions with this engine in mind.

= Are the pattern matches case sensitive? =

No. They are run with the 'i' flag.

= I'm new to this regular expression stuff. A little help? =

Learning regular expressions go way (way) beyond the scope of this documentation. However I can offer some resources and tools to get you started.

Learning & Reference:

* ([regular-expressions.info](http://www.regular-expressions.info/)) - Tutorials, Examples and Reference
* ([PHP's Regular Expressions Perl-Compatible Page](http://php.net/manual/en/book.pcre.php)) - Information specific to writing regular expressions in PHP

Tools:

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

= 0.1 =
* Initial Release
