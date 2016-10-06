=== Plugin Name ===
Contributors: lubus,ajitbohra  
Donate link: http://www.lubus.in  
Tags: wordcamp, widget, dashboard  
Requires at least: 3.0.1  
Tested up to:  4.6.1  
Stable tag: 0.1.1  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Display upcoming wordcamps on your wp-admin dashboard


== Description ==
>**Disclaimer:** This initial release v0.1 is written quickly in few odd hours, will be rewritten as the plugin progresses. If you think code can be improved or have any suggestion feel free to send a PR or [open an issue](https://github.com/lubusonline/wordcamp-dashboard-widget/issues).

We have created this plugin out of personal need to have quick access to list of upcoming wordcamps right from the wp-admin. Plugin displays a widget on dashboard containing the list of upcoming wordcamps with options to search, sort & navigate through list along with direct link to respective wordcamp website. Data is fetched using JSON API (from https://central.wordcamp.org/wp-json/posts?type=wordcamp) and stored in transient for better performance, which is refreshed every day to reflect new data.

----------

**Features coming in v1.0**

1.Option to refresh data<br>
2.Re-write plugin code<br>
3.Improve data fetching<br>
4.Creating shortcode to display wordcamp list anywhere on website<br>
5.Option to filter wordcamps based on users location/country<br>
6.localization 

If you have any suggestions/Feature request that you would like to see in the upcoming releases , feel free to let us know in the [issues section](https://github.com/lubusonline/wordcamp-dashboard-widget/issues) 


== Installation ==
***From your WordPress dashboard***

1.Visit 'Plugins > Add New'<br>
2.Search for 'Wordcamp Dashboard Widget'  or upload zip file<br>
3.Activate "Wordcamp Dashboard Widget" from your Plugins page

***Manual Installation*** 

1.Download "Wordcamp Dashboard Widget"<br>
2.Upload the 'wordcamp-dashboard-widget' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)<br>
3.Activate "Wordcamp Dashboard Widget" from your Plugins page. 

== Screenshots ==

1. Widget in action

== Changelog ==

***v0.1*** 

Released on 28th September 2016

 - Initial plugin release
