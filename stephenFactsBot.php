<?php
set_time_limit(0);

require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

use ApiPlugins\ChuckNorrisFactGuzzler;
use Phergie\Irc\Generator;
use Phergie\Irc\Parser;
require 'src/plugins/chuckNorrisFactGuzzler.php';

$socket = fsockopen($serverName, $port);
if (!$socket) {
    echo 'socket failed to open';
    exit(1);
}
fputs($socket,"NICK $nick\n");
fputs($socket,"USER $nick $serverName $realName");
fputs($socket,"JOIN ".$chan."\n");

$baseURI = "https://api.chucknorris.io/jokes/";
$name = 'Stephen';
$factGuzzler = new ChuckNorrisFactGuzzler($baseURI, $name);

$parser = new Parser();
$generator = new Generator();
$alreadyJoined = false;

function sendData($cmd, $msg = null, $socket) {
    if($msg == null) {
        fputs($socket, $cmd."\r\n");
        echo "<strong>" . $cmd . "</strong><br />";
    } else {
        #
        fputs($socket, $cmd . " " . $msg . "\r\n");
        echo "<strong>" . $cmd . " :" . $msg . "</strong><br />";
    }
}

//PRIVMSG #roomName :text

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
            if($message['command'] == "PING"){
                sendData("PONG", $message['params']['all'], $socket);
            }
            else if ($message['command'] == "PRIVMSG") {
                $text = $message['params']['text'];
                if (preg_match('/'.$nick.'\:(?P<action>\w+)/', $text, $matches)) {
                    $action = $matches['action'];
                    if ($action == 'random') {
                        $fact = $factGuzzler->getRandomFact();
                        $message = $generator->ircPrivmsg($chan, $fact);
                        fputs($socket, $message);
                    }
                }
            }
        }
    }
}
