=== Plugin Name ===
Contributors: karzin
Tags: file rename, upload, renaming, file, rename
Requires at least: 3.0.1
Tested up to: 3.5.2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U3MFXJRKLYDMC
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It renames files on upload to fix filename with accents, special characters. Improves your SEO and more. 

== Description ==

WordPress can't properly render files with accents and some special characters on file name (e.g., Brazilian, French, ...). 
This plugin renames files just before the upload moment so they can be exhibited without problems.

= For now, you can choose these options: =

* **Add Site url:** Inserts "www.yoursite.com" at the beggining of the file name. Ex: yoursite.com_filename.jpg. It is good for your SEO
* **File renaming based on post title:** The postTitle is for example:"I like icecream". You upload an featured ICE image. And it will be renamed to "I-like-icecream.jpg"
* **Remove string parts from site url:** Can be used to remove top-level domains(tld), www, subdomains or whatever you want.
* **Replace file name by datetime:** Replaces filename by datetime, like "2013-07-18_21-48-19"
* **Lowercase:** Converts all characters to lowercase
* **Remove accents**
* **Remove special chars:** Removes these special chars: ? + [ ] / \ = < > : ; , ' " & $ # * ( ) | ~ ` ! { }

Please [help me and my company with some bucks](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=U3MFXJRKLYDMC) and i'll gladly keep developing this plugin

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==


== Screenshots ==

1. Customize how the plugin works

== Changelog ==

= 1.2 =
* Added an option to renames files based on post title
* Fixed a bug where some strings were not properly removed from site url

= 1.1 =
* Added an option to remove string parts from url

= 1.0.1 =
* Admin page class renamed

= 1.0 =
* Plugin's creation