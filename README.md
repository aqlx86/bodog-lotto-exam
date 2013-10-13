About
=====
A lotto application programming application for senior php developer.

This uses websockets for communication between the client and the servers.
tested and working on top of debian based linux, apache server, PHP 5.4.4 (no framework) , and google chrome browser.


Components
==========
* NotORM - used for database manipulation. <http://www.notorm.com/>
* PHP-Websockets - for socket communication. <https://github.com/ghedipunk/PHP-Websockets>


Setup / Installation 
====================
* put the lotto folder in your document or create a vhost.
* create a db then import the sql dump from the /sql folder.
* modify the database, game settings in /server/config.php file.
* change file permission of start-server.php and start-draw.php (```chmod +x start-server.php start-draw.php```).
* setup a cron job for the draw server, use the start-draw.php script.


Running
=======
* run the lotto game server in/only CLI (```./start-server.php```).
* in your browser open index.php. once it is connected to the server you can start making your combinations.


Notes
=====
* to generate the winning numbers manually, run (```./start-draw.php```) in CLI or in the game UI there is a button to trigger drawing of winning numbers.
