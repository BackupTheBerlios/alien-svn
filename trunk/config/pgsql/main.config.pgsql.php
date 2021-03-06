<?php
/* Alien configuration file. You can change all of this options via installation script or
   in the admin inteface. If you prefer to change them manually in this file, then please be
   carefull and read all the comments provided to a separate option. If any of these options
   will be set wrong, your site most likely will be not accessible. */

/* Database host. That is a host where your database server is running. (usually it is localhost)
   If you don't know exactly it's address, please, ask your server administrator. */
    
define('__ALIEN_DBHOST', 'localhost');

/* Username to log into the database server. If you don't know, ask
   your server administrator */
	
define('__ALIEN_DBUSER','postgres');

/* Password to use with username (see below) */

define('__ALIEN_DBPASSWORD', 'postgres');

/* Database name where all system tables are stored */

define('__ALIEN_DBNAME', 'alien');

define('__ALIEN_TABLEPREFIX', 'alien_');

/* 
	Number of the port where a database server is running. Change it only if you
   can not connect to database, but you are sure that all other database options
   are correct
*/	
define('__ALIEN_DBPORT', '5432');

/* Please, set to FALSE on production */
define('__ALIEN_DEBUG', TRUE);
define('__ALIEN_ENABLECACHE', TRUE);
define('__ALIEN_DBCODEPAGE', 'cp1251');
define('__ALIEN_ERRORLEVEL', 'E_ALL');
define('__ALIEN_PATH', 'E:/WebServers/home/alien.com/www/');
define('__ALIEN_LIBSDIR', __ALIEN_PATH.'libs/');
define('__ALIEN_SYSTEMDIR', __ALIEN_PATH.'system/');
define('__ALIEN_TEMPLATESDIR', __ALIEN_PATH.'templates/');
define('__ALIEN_SYSTEMLIBSDIR', __ALIEN_SYSTEMDIR.'/libs/');
define('__ALIEN_PLUGINSDIR', __ALIEN_PATH.'plugins/');
define('__ALIEN_INTERFACESDIR', __ALIEN_SYSTEMDIR.'interfaces/');
define('__ALIEN_LANGSDIR', __ALIEN_PATH.'langs/');
define('__ALIEN_DATADIR', __ALIEN_PATH.'data/');
define('__ALIEN_SESSPATH', __ALIEN_DATADIR.'sessions/');
define('__ALIEN_LANGUAGE', 'Russian');
?>