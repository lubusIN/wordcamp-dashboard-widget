**Wordcamp Dashboard Widget**
-----------------------------

Display upcoming WordCamps on your wp-admin dashboard

>**Disclaimer:** Plugin development is in progress & we are constantly improving the code. If you think code can be improved or have any suggestion feel free to send a PR or [open an issue](https://github.com/lubusIN/wordcamp-dashboard-widget/issues).

**Screenshot**
![Plugin Screenshot](https://raw.githubusercontent.com/lubusIN/wordcamp-dashboard-widget/master/assets/screenshot-1.gif)

**About**

We have created this plugin out of personal need to have quick access to list of upcoming WordCamps right from the wp-admin. Plugin displays a widget on dashboard containing the list of upcoming WordCamps with options to search, sort & navigate through list along with direct link to respective wordcamp website. Data is fetched using JSON API (from https://central.wordcamp.org/wp-json/posts?type=wordcamp) and stored in transient for better performance, which is refreshed every day to reflect new data.

**Features coming in v1.0**

 1. Option to refresh data
 2. Re-write plugin code
 3. Improve data fetching
 4. <del >Creating shortcode to display wordcamp list anywhere on website front-end</de>
 5. Option to filter WordCamps based on users location/country
 6. localization

If you have any suggestions/Feature request that you would like to see in the upcoming releases , feel free to let us know in the [issues section](https://github.com/lubusIN/wordcamp-dashboard-widget/issues)


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

**Usage**
----------------
- Simply activate and widget will displayed on dashboard
- To display elsewhere use the Shortcode `[wordcamps]`
- If your using visual composer you widget for the same is available

**Contributing**
----------------

Thank you for considering contributing to the WordCamp Dashboard Widget. You can read the contribution guide lines [here](CONTRIBUTING.md)

**Security**
------------

If you discover any security related issues, please email to [ajit@lubus.in](mailto:ajit@lubus.com) instead of using the issue tracker.

**Credits**
------------

- [Ratnesh Sonar](https://twitter.com/ratneshsonar) Idea / Concept
- [Ajit Bohra](http://https://twitter.com/ajitbohra) Development
- [Meher Bala](https://twitter.com/meherbala) Helping with issue Reporting & troubleshooting

**About LUBUS**
---------------
LUBUS is a WordPress & Laravel agency based in Mumbai. More about us could be found here [on our website](http://lubus.in).

**License**
-----------
WordCamp Dashboard Widget is open-sourced software licensed under the [GPL 3.0 license](LICENSE)

**Changelog**
-------------
Please see the [Changelog](https://github.com/lubusIN/wordcamp-dashboard-widget/blob/master/CHANGELOG.md) for the details
