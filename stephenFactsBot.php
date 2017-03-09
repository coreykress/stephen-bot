<?php
// Prevent PHP from stopping the script after 30 sec
set_time_limit(0);

// Edit these settings
$chan = "";
$server = "127.0.0.1";
$port = 6667;
$nick = "Stephen_Facts";

// STOP EDITTING NOW.
$socket = fsockopen("$server", $port);
fputs($socket,"USER $nick $nick $nick $nick :$nick\n");
fputs($socket,"NICK $nick\n");
fputs($socket,"JOIN ".$chan."\n");

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

            if ($rawcmd[1] == "!fact") {
                fputs($socket, "PRIVMSG ".$channel." :".$args." \n");
            }
        elseif ($rawcmd[1] == "!md5") {
            fputs($socket, "PRIVMSG ".$channel." :MD5 ".md5($args)."\n");
        }
    }
}
?>