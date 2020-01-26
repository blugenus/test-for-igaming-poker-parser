<?php
/**
 * some initial configuaration
 */
ignore_user_abort(false); // aborts if the user connection is closed/aborted.
set_time_limit(30); // limit the maximum execution time.
date_default_timezone_set('UTC'); // ensuring the php time is UTC.

/**
 * adding composer autoloader
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error/Exception Handling
 */
error_reporting(E_ALL);
$error = new App\Error();
set_error_handler( [ $error, 'errorHandler' ] );
set_exception_handler( [ $error, 'exceptionHandler' ] );

$sqls = [
    "
        CREATE TABLE `users` (
          `userId` int(11) NOT NULL AUTO_INCREMENT,
          `username` varchar(50) NOT NULL,
          `password` varchar(255) NOT NULL,
          PRIMARY KEY (`userId`),
          UNIQUE KEY `username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",
    "
        CREATE TABLE `uploads` (
          `uploadId` int(11) NOT NULL AUTO_INCREMENT,
          `userId` int(11) DEFAULT NULL,
          `startDateTime` datetime DEFAULT '1900-01-01 00:00:00',
          `hands` int(11) DEFAULT '0',
          `completed` tinyint(1) DEFAULT '0',
          `completedDateTime` datetime DEFAULT '1900-01-01 00:00:00',
          PRIMARY KEY (`uploadId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",
    "
        CREATE TABLE `uploads_hands` (
          `uploadId` int(11) NOT NULL,
          `handId` int(11) NOT NULL,
          `player1hand` varchar(14) DEFAULT NULL,
          `player1Rank` int(11) DEFAULT NULL,
          `player2hand` varchar(14) DEFAULT NULL,
          `player2Rank` int(11) DEFAULT NULL,
          `result` int(11) DEFAULT NULL,
          PRIMARY KEY (`uploadId`,`handId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",
    "
        CREATE TABLE `ranks` (
          `rankId` int(11) NOT NULL,
          `name` varchar(20) DEFAULT NULL,
          PRIMARY KEY (`rankId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ",
    "
        INSERT INTO `users` (`username`, `password`) 
        VALUES ( 'admin', '" . password_hash( '123', PASSWORD_BCRYPT, ['cost' => 12] ) ."' );
    ",
    "
        INSERT INTO `ranks` (`rankId`, `name`) VALUES 
        (0,'Invalid'),
        (1,'High card'),
        (2,'One Pair'),
        (3,'Two Pair'),
        (4,'Three of a Kind'),
        (5,'Straight'),
        (6,'Flush'),
        (7,'Full house'),
        (8,'Four of a Kind'),
        (9,'Straight Flush'),
        (10,'Royal Flush');
    "
];

// execute all out sql statements
//for( $coun=0; $coun<sizeof( $sql ); $coun++ ){
foreach( $sqls as $sql ){
    \App\Database::execute( $sql );
}

\App\Database::disconnect();

?><p>We are done with setting it up :)</p>
<p><a href="/login">Click Here</a> to go to the login page</p>