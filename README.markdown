# FH Kufstein InfoScreen Webservice

Live Version (running stable branch): [http://infoscreen.stud.ailoo.net/](http://infoscreen.stud.ailoo.net/)

## Requirements

* PHP >= 5.2

### Optional

* A fast cache backend for production mode. See [Zend_Cache](http://framework.zend.com/manual/de/zend.cache.backends.html) documentation for available options (tested with Memcached)

## Installation

* Clone the repository and point your web server to the "public" directory
* Make sure your web server user has write access to the repository
  * If not, create the <code>var</code> directory manually and give your web server write access. E.g. <code>mkdir var && chown www-data var</code>
* Copy configuration files (copy them instead of moving to ensure a clean upgrade on later releases)
  * <code>public/.htaccess.dist</code> to <code>public/.htaccess</code>
  * <code>application/configs/application.ini.dist</code> to <code>application/configs/application.ini</code>
* Take a look at <code>application/configs/application.default.ini</code> and override settings in <code>application/configs/application.ini</code> (do not edit the default file as setting would be overwritten on upgrades)
* If the application runs in a subdirectory of your web server, change the RewriteBase in <code>.htaccess</code>
* Open the app in a browser and check if all works properly (this should also create the needed directories in <code>var</code> to store logs and caches
* If all works properly, switch to production mode in <code>.htaccess</code>