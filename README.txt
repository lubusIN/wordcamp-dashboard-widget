=== Plugin Name ===
Contributors: lubus,ajitbohra  
Donate link: http://www.lubus.in  
Tags: admin, dashboard, widget, wordcamp 
Requires at least: 3.0.1  
Tested up to:  4.6.1  
Stable tag: 0.5
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Display upcoming WordCamps on your wp-admin dashboard


== Description ==
>**Disclaimer:** Plugin development is in progress & we are constantly improving the code. If you think code can be improved or have any suggestion feel free to send a PR or [open an issue](https://github.com/lubusIN/wordcamp-dashboard-widget/issues).

We have created this plugin out of personal need to have quick access to list of upcoming WordCamps right from the wp-admin. Plugin displays a widget on dashboard containing the list of upcoming WordCamps with options to search, sort & navigate through list along with direct link to respective wordcamp website. Data is fetched using JSON API (from https://central.wordcamp.org/wp-json/posts?type=wordcamp) and stored in transient for better performance, which is refreshed every day to reflect new data.

----------

**Features coming in v1.0**

1.Option to refresh data<br>
2.Re-write plugin code<br>
3.Improve data fetching<br>
4.Creating shortcode to display WordCamp list anywhere on website<br>
5.Option to filter wordcamps based on users location/country<br>
6.localization 

If you have any suggestions/Feature request that you would like to see in the upcoming releases , feel free to let us know in the [issues section](https://github.com/lubusIN/wordcamp-dashboard-widget/issues) 

**Credits**

- [Ratnesh Sonar](https://twitter.com/ratneshsonar) Idea / Concept<br>
- [Ajit Bohra](http://https://twitter.com/ajitbohra) Development<br>
- [Meher Bala](https://twitter.com/meherbala) Issue Reporting<br>

== Installation ==
***From your WordPress dashboard***

1.Visit 'Plugins > Add New'<br>
2.Search for 'WordCamp Dashboard Widget'  or upload zip file<br>
3.Activate "Wordcamp Dashboard Widget" from your Plugins page

***Manual Installation*** 

1.Download "WordCamp Dashboard Widget"<br>
2.Upload the 'wordcamp-dashboard-widget' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)<br>
3.Activate "WordCamp Dashboard Widget" from your Plugins page. 

== Screenshots ==

1. Widget in action

== Changelog ==

***v0.5***

Released on 22nd November 2016

- Fixed UI to to be inline with WordPress
- Added Twitter profile and hashtag for the WordCamps
- Fixed fatal error on date conversion

***v0.2***

Released on 10th October 2016

 - Fixed fatal error on plugin activation
 - Fixed wp_remote_get() fatal error to catch and show user friendly error message
 - Funcitons, text & comments renaming/typos
 - Code comments added at required places
 - Developer friendly error message for easy troubleshooting
 - Fixed cURL error 51 (On some servers)
 - Fixed incorrect WordCamp data sorting (Sorting by date)
 - Fixed cURL error 28: Operation timed out after 5000 milliseconds
 - Fixed scripts & styles to load only on main dashboard page

***v0.1.1***

Released on 6th October 2016

 - Functions renamed to be unique (as suggested by wordpress.org plugin review)
 - Datatables jquery plugin included locally to remove dependency (as suggested by wordpress.org plugin review)
 - wordpress.org plugin page assets removed
 - Readme updated
 
***v0.1*** 

Released on 28th September 2016

 - Initial plugin release