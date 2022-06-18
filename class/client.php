<?php
/**
 * <pre>
 * SFApi 2.0
 * client class
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */

namespace SFBOT;


class client{

    private $salt = "ahHoj2woo1eeChiech6ohphoB7Aithoh"; // DEF PASSWORD SALT
    private $logincount = 0;

    //public $lvl, $exp, $mush, $silver, $status, $statusextra, $statustime, $quests = array(array(), array(), array()), $thirst, $beers, $timestamp, $timeglass; 

    public function init($oko){

        global $resp, $clientip;

        $session_name = md5($clientip);

        switch($oko->act){

            case "update":
                $resp->requeststatus = "success";
                $resp->api_server = "http://localhost";
            break;
            case "broadcast":
                $resp->requeststatus = "error";
                $resp->message = "Testowa wiadomosc";
            break;
            case "login":

                if(!isset($oko->nick) OR !isset($oko->pass)){
                    $resp->message = "missing nick, pass and server";
                    break;
                }

                $nick = $oko->nick;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                if(strlen($oko->pass) != 40){
					$pass = sha1($oko->pass . $this->salt);
				}
                else {
                    $pass = $oko->pass;
                }

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                $version = "unity3d_webglplayer"; // unity3d_webglplayer, flash , html
				$pattern = "00000000000000000000000000000000|AccountLogin:".$oko->nick."/".sha1($pass . $this->logincount)."/".$this->logincount."/$version/not_initialized/56|||||||||";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));
                
				if(isset($ret['error'])){

                    $resp->message = $ret['error'];
                }
				else{
                    $this->loaduserdata($ret, $oko);
                    $resp = $this;
                    $resp->requeststatus = "success";
				}

            break;
            case "logout":
                $resp->requeststatus = "success";
            break;
            case "startquest":

                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                if(!isset($oko->questid) OR $oko->questid > 3 OR $oko->questid < 1){
					$resp->message = "incorrect quest id";
                    break;
                }
                
                $questid = $oko->questid;

                $ignorefullbackpack = 1;// 0 = no

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                $pattern = $sessionid . "|PlayerAdventureStart:".$questid."/$ignorefullbackpack|||||||1";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));

        
				if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
                    $this->loaduserdata($ret, $oko);
					$resp = $this;
					$resp->requeststatus = "success";
				}

            break;
            case "stopquest":

                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                $pattern = $sessionid . "|PlayerAdventureStop:|||||||";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));

                if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
                    $this->loaduserdata($ret, $oko);
                    $resp = $this;
                    $resp->requeststatus = "success";
				}
            break;
            case "finishquest":
                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

				if(isset($oko->skiptype) and $oko->skiptype != 1 and $oko->skiptype != 2 and $oko->skiptype != 0){
                    // 1 = mush 2 = klepsydra 
					$resp->message = "missing valid skip type";
					break;
                }
                
                if($oko->skiptype != 0){
                    if(!$this->license){
                        $resp->message = "this option not available on Free version";
                        break;
                    }
                }
                
                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                $pattern = $sessionid . "|PlayerAdventureFinished:".$oko->skiptype."||||||1";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));

				if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
                    $this->loaduserdata($ret, $oko);
					$resp = $this;
					$resp->requeststatus = "success";
                }
            break;
            case "startwork":
                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                if(!isset($oko->workhours) or $oko->workhours > 10 or $oko->workhours < 1 or !is_int($oko->workhours)){
                    $resp->message = "missing work hours";
                    break;
                }

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }
                $pattern = $sessionid . "|PlayerWorkStart:" . $oko->workhours;
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));

				if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
					$resp = $this;
					$resp->requeststatus = "success";
                }      
            break;
            case "stopwork":
                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                $pattern = $sessionid . "|PlayerWorkStop:1";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));
                
        
				if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
                    $resp->requeststatus = "success";
                    $resp->status = 0;
                    $resp->statustime = 0;
                    $resp->statusextra = 0;
                }      
            break;
            case "finishwork":
                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }

                //ownplayersave.playerSave:13/$reward/45/0/47/0";
                $pattern = $sessionid . "|PlayerWorkFinished:1";
                $ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));

				if(isset($ret['error'])){
					$resp->message = $ret['error'];
                }
                else{
                    $resp->silver = (int) $ret['workreward'] * 100;
					$resp->requeststatus = "success";
                }

            break;
            case "buybeer":
                if(!isset($oko->sessionid) or strlen($oko->sessionid) != 32){
					$resp->message = "missing session id";
					break;
                }

                $sessionid = $oko->sessionid;

                if(!isset($oko->server) OR !validateserver($oko->server)){
                    $resp->message = "incorrect server address";
                    break;
                }

                $server = $oko->server;

                $cryptokeys = getnewkeys($server);
                if($cryptokeys[0] == "error"){
                    $resp->message = "cannot download crypto keys";
                    break;
                }
                $pattern = $sessionid . "|PlayerBeerBuy:|1";
				$ret = Socket::send($server, $cryptokeys[0] . Crypto::encrypt($pattern, $cryptokeys[1]));
                
				if(isset($ret['error'])){
                    //you are not thirsty
                    $resp->message = $ret['error'];
                    break;
                }
                else{
                    $this->loaduserdata($ret, $oko);
					$resp = $this;
					$resp->requeststatus = "success";
				}                       
            break;
        }
    }

    private function loaduserdata($ret, $oko){

        ////// index 542 = timeglass'es
    
    
        $playersave = explode('/', "0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0/0");
    
        $count = (int) Count(explode('/', $ret['ownplayersave']));
    
        if($oko->act == "login" OR ($oko->act == "startquest" AND $count == 752) OR ($oko->act == "finishquest" AND $count > 400)){
            $playersave = explode('/', $ret['ownplayersave']);
    
            if($oko->act == "login"){
                $this->sessionid = (string) $ret['sessionid'];
            }

            if($oko->act == "login" OR $oko->act == "startquest" OR $oko->act == "finishquest"){
                $this->lvl = (int) $playersave[7];
                $this->exp = (int) $playersave[8];
                //$this->expnext = (int) $playersave[9];
                $this->mush = (int) $playersave[14];
                $this->silver = (int) round($playersave[13] /100);
                $this->quests[0]['time'] = (int) $playersave[241];
                $this->quests[1]['time'] = (int) $playersave[242];
                $this->quests[2]['time'] = (int) $playersave[243];
        
                $this->quests[0]['exp'] = (int) $playersave[280];
                $this->quests[1]['exp'] = (int) $playersave[281];
                $this->quests[2]['exp'] = (int) $playersave[282];
        
                $this->quests[0]['silver'] = (int) $playersave[283];
                $this->quests[1]['silver'] = (int) $playersave[284];
                $this->quests[2]['silver'] = (int) $playersave[285];
                $this->beers = (int) $playersave[457];
                $this->timeglass = (int) $playersave[542];
                $this->timestamp = (int) $ret['timestamp'];
            }

            $status = $playersave[45];
            $this->status = (int) ($status - 1643118592) >= 0 ? ($status - 1643118592) : 0;
    
            $statusextra = $playersave[46];
            $this->statusextra = (int) ($statusextra - 35913728) >= 0 ? ($statusextra - 35913728) : 0;
    
            $this->statustime = (int) $playersave[47];
        
            $this->thirst = (int) $playersave[456];
        }
        else if($oko->act == "startquest" OR $oko->act == "stopquest" OR $oko->act == "finishquest"){
    
            $ret = explode('/', $ret['ownplayersave']);
    
            for($i=0;$i<$count;$i+=2){
                $playersave[$ret[$i]] = $ret[$i+1];
            }
    
            $status = $playersave[45];
            $this->status = (int) ($status - 1643118592) >= 0 ? ($status - 1643118592) : 0;
    
            $statusextra = $playersave[46];
            $this->statusextra = (int) ($statusextra - 35913728) >= 0 ? ($statusextra - 35913728) : 0;
    
            $this->statustime = (int) $playersave[47];
            
            if($oko->act != "finishquest"){
                $this->thirst = (int) $playersave[456];
            }

            if($oko->act == "finishquest"){
                $this->exp = (int) $playersave[8];
            
                if($playersave[14] > 0){
                    $this->mush = (int) $playersave[14];
                }
                $this->silver = (int) round($playersave[13] /100);
                $this->quests[0]['time'] = (int) $playersave[241];
                $this->quests[1]['time'] = (int) $playersave[242];
                $this->quests[2]['time'] = (int) $playersave[243];

                $this->quests[0]['exp'] = (int) $playersave[280];
                $this->quests[1]['exp'] = (int) $playersave[281];
                $this->quests[2]['exp'] = (int) $playersave[282];

                $this->quests[0]['silver'] = (int) $playersave[283];
                $this->quests[1]['silver'] = (int) $playersave[284];
                $this->quests[2]['silver'] = (int) $playersave[285];
            }
        }
        
    }
}



?>