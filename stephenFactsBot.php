<?php
set_time_limit(0);

require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

use Phergie\Irc\Parser;
//use Bot\ChuckNorrisFactGuzzler;

$socket = fsockopen($serverName, $port);
if (!$socket) {
    echo 'socket failed to open';
    exit(1);
}
fputs($socket,"NICK $nick\n");
// username, hostname, servername, realname
fputs($socket,"USER $nick $serverName $realName");

fputs($socket,"JOIN ".$chan."\n");

$baseURI = 'https://api.chucknorris.io/jokes';
$name = 'Stephen';
//$factGuzzler = new ChuckNorrisFactGuzzler($baseURI, $name);

$parser = new Parser();
$alreadyJoined = false;

function sendData($cmd, $msg = null, $socket) {
    if($msg == null) {
        fputs($socket, $cmd."\r\n");
        echo "<strong>".$cmd."</strong><br />";
    } else {
        fputs($socket, $cmd." ".$msg."\r\n");
        echo "<strong>".$cmd." ".$msg."</strong><br />";
    }
}

while(1) {
    while($data = fgets($socket)) {
            echo nl2br($data);
            flush();

        $message = $parser->parse($data);
        if(!$alreadyJoined) {
            sendData("JOIN", $chan, $socket);
            $alreadyJoined = true;
        } else {
            var_dump($message);
            $ex = explode(' ', $data);
            if($ex[0] == "PING"){
                var_dump($ex[1]);
                fputs($socket, "PONG ".$ex[1]."\n");
            }
        }
    }
}
