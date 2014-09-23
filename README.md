GoogleAnalyticApi3ToGraphite
============================
Google Analytics API V3 now supports OAuth2 tokens returned by a .p12-signed JWT request. That is, we can now use the Analytics API w/ service accounts.

1.  Go to the [GAConsole](https://code.google.com/apis/console/) and create a new app.
2.  In the API tab, enable Analytics API.
3.  In Credential tab click Create an OAuth2.0 Client ID, then download your private key
4.  Now you're back on the API Access page. You'll see a section called Service account with a Client ID and Email address:
   * Copy the email address (something like ####@developer.gserviceaccount.com)
   * Visit your GA Admin and add this email as a user to your properties
   * This is a must; you'll get cryptic errors otherwise.
5.  Get the latest [Google PHP Client API](https://github.com/google/google-api-php-client) via Github.
6.  Add your setting to config.php
7.  Edit path to libraries in request.php
8.  Finally task to cron like this:

*/1 * * * *    /usr/bin/php -q /opt/request.php > /dev/null 2>&1

