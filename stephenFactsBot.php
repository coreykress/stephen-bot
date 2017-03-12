<?php
set_time_limit(0);

use Bot\ChuckNorrisFactGuzzler;

// Edit these settings
include ('config.php');

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
$factGuzzler = new ChuckNorrisFactGuzzler($baseURI, $name);

while(1) {
    while($data = fgets($socket)) {
            echo nl2br($data);
            flush();

            $ex = explode(' ', $data);
        $rawcmd = explode(':', $ex[3]);
        $oneword = explode('<br>', $rawcmd);
            $channel = $ex[2];
        $nicka = explode('@', $ex[0]);
        $nickb = explode('!', $nicka[0]);
        $nickc = explode(':', $nickb[0]);

        $host = $nicka[1];
        $nick = $nickc[1];
            if($ex[0] == "PING"){
                fputs($socket, "PONG ".$ex[1]."\n");
            }

        $args = NULL; for ($i = 4; $i < count($ex); $i++) { $args .= $ex[$i] . ' '; }

            if ($rawcmd[1] == "!random") {
                /** @var string $fact */
                $fact = $factGuzzler->getRandomFact();

                fputs($socket, "PRIVMSG ".$channel." :".$fact." \n");
            }
        elseif ($rawcmd[1] == "!md5") {
            fputs($socket, "PRIVMSG ".$channel." :MD5 ".md5($args)."\n");
        }
    }
}
