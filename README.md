**Wordcamp Dashboard Widget**
-----------------------------

Display upcoming WordCamps on your wp-admin dashboard

>**Disclaimer:** This initial release v0.1 is written quickly in few odd hours, will be rewritten as the plugin progresses. If you think code can be improved or have any suggestion feel free to send a PR or [open an issue](https://github.com/lubusonline/wordcamp-dashboard-widget/issues).

**Screenshot**
![Plugin Screenshot](https://raw.githubusercontent.com/lubusonline/wordcamp-dashboard-widget/master/assets/screenshot-1.gif)

**About**

We have created this plugin out of personal need to have quick access to list of upcoming WordCamps right from the wp-admin. Plugin displays a widget on dashboard containing the list of upcoming WordCamps with options to search, sort & navigate through list along with direct link to respective wordcamp website. Data is fetched using JSON API (from https://central.wordcamp.org/wp-json/posts?type=wordcamp) and stored in transient for better performance, which is refreshed every day to reflect new data.

**Features coming in v1.0**

 1. Option to refresh data
 2. Re-write plugin code
 2. Improve data fetching
 3. Creating shortcode to display wordcamp list anywhere on website front-end 
 4. Option to filter WordCamps based on users location/country
 5. localization 

If you have any suggestions/Feature request that you would like to see in the upcoming releases , feel free to let us know in the [issues section](https://github.com/lubusonline/wordcamp-dashboard-widget/issues) 


**Installation**
----------------
***From your WordPress dashboard***
 1. Visit 'Plugins > Add New' 
 2. Search for 'Wordcamp Dashboard Widget'  or upload zip file
 3. Activate "Wordcamp Dashboard Widget" from your Plugins page

***Manual Installation*** 
 1. [Download](https://wordpress.org/plugins/wc-dashboard-widget/) "WordCamp Dashboard Widget".
 2. Upload the 'wordcamp-dashboard-widget' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
 3. Activate "Wordcamp Dashboard Widget" from your Plugins page. 

**Change Log**
--------------
***v0.1.1***

Released on 6th October 2016

 - Functions renamed to be unique (as suggested by wordpress.org plugin review)
 - Datatables jquery plugin included locally to remove dependency (as suggested by wordpress.org plugin review)
 - wordpress.org plugin page assets removed
 - Readme updated
 
***v0.1***

Released on 28th September 2016

 - Initial plugin release
