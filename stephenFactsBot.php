<?php
set_time_limit(0);

//use Bot\ChuckNorrisFactGuzzler;

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
//$factGuzzler = new ChuckNorrisFactGuzzler($baseURI, $name);

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

        echo $data;

        if(!$alreadyJoined) {
            sendData("JOIN", $chan, $socket);
            $alreadyJoined = true;
        }

        $ex = explode(' ', $data);
        var_dump($ex);
        if($ex[0] == "PING"){
            fputs($socket, "PONG ".$ex[1]."\n");
        }

        if (!isset($ex[3]) && $ex[2] !== "JOIN") {
            var_dump('DIED HERE');
            var_dump($ex);die;
        }
        $rawcmd = explode(':', $ex[3]);
//        $oneword = explode('<br>', $rawcmd);
        $channel = $ex[2];
        $nicka = explode('@', $ex[0]);
        $nickb = explode('!', $nicka[0]);
        $nickc = explode(':', $nickb[0]);

//        $host = $nicka[1];
//        $nick = $nickc[1];

        $args = NULL; for ($i = 4; $i < count($ex); $i++) { $args .= $ex[$i] . ' '; }

            if ($rawcmd[1] == "!random") {
                /** @var string $fact */
//                $fact = $factGuzzler->getRandomFact();

                fputs($socket, "PRIVMSG ".$channel." :".$fact." \n");
            }
        elseif ($rawcmd[1] == "!md5") {
            fputs($socket, "PRIVMSG ".$channel." :MD5 ".md5($args)."\n");
        }
    }
}
