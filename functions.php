<?php
/**
 * <pre>
 * SFApi 2.0
 * functions file
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */

namespace SFBOT;

////// THIS FUNCTION REPLACES THE CUSTOM COMMUNICATION FORMAT TO THE POPULAR JSON FORMAT
function sfjson($ret){
	$obj = array();
		foreach(explode("&", $ret) as $row){
			$arr = explode(":", $row);
			$index = "";
			$key = "";
			if(Count($arr) == 2){
				$index = $arr[0];
				$key = $arr[1];
			}
			//ownplayersaveplayersave
			$index = str_replace(array(" ", ".", "E", "S", "#"), array("", "", "e", "s", ""), $index);
			$index = str_replace(array("ownplayersaveplayersave"), array("ownplayersave"), $index);
			$obj[$index] = $key;
		}
	return ($obj);
}

function setnewkeys($server){
		
	//serverversion:1818&cryptoid:0-0p7o70Wv41ai37&cryptokey:m1MLt1Ob7534rR87
	
	$query = $server . "/req.php?req=0-00000000000000iY0Ap3B2omAw8Cx-hkM-uVt5DjJgRqZHqWLBMPNmGmXpSvoRQGeSi3zEVDt9TMdSNpQZFPTGJHFrtK6eMA8AnA==&rnd=".time();
	$ret = file_get_contents($query);
	$data = sfjson($ret);
	
	if(isset($data['serverversion'])){
		$_SESSION['cryptokey'] = $data['cryptokey'];
		$_SESSION['cryptoid'] = $data['cryptoid'];
		return true;
	}
	return false;
}

function getnewkeys($server){
		
	//serverversion:1818&cryptoid:0-0p7o70Wv41ai37&cryptokey:m1MLt1Ob7534rR87
	//serverversion:1846&cryptoid:0-0L6sg18l9XirO2&cryptokey:qWF04E692lu64400

	$query = "http://" . $server . "/req.php?req=0-00000000000000iY0Ap3B2omAw8Cx-hkM-uVt5DjJgRqZHqWLBMPNmGmXpSvoRQGeSi3zEVDt9TMdSNpQZFPTGJHFrtK6eMA8AnA==&rnd=".time();
	$ret = file_get_contents($query);
	$data = sfjson($ret);
	
	if(isset($data['serverversion'])){

		if(isset($data['cryptoid']) and isset($data['cryptokey'])){
			return array($data['cryptoid'], $data['cryptokey']);
		}
	}
	return array("error", "cannot download crypto keys");
}

function validateserver($addr){
	
	$serverlist = array(
	"s1.sfgame.ae",
	"s2.sfgame.ae",
	"s1.sfgame.com.br",
	"s2.sfgame.com.br",
	"s1.sfgame.ca",
	"s1.sfgame.cl",
	"s2.sfgame.cl",
	"s1.sfgame.cz",
	"s2.sfgame.cz",
	"s3.sfgame.cz",
	"s4.sfgame.cz",
	"s5.sfgame.cz",
	"s6.sfgame.cz",
	"s7.sfgame.cz",
	"s8.sfgame.cz",
	"s9.sfgame.cz",
	"s10.sfgame.cz",
	"s11.sfgame.cz",
	"s12.sfgame.cz",
	"s13.sfgame.cz",
	"s14.sfgame.cz",
	"s15.sfgame.cz",
	"s16.sfgame.cz",
	"s17.sfgame.cz",
	"s18.sfgame.cz",
	"s19.sfgame.cz",
	"s20.sfgame.cz",
	"s21.sfgame.cz",
	"s22.sfgame.cz",
	"s23.sfgame.cz",
	"s24.sfgame.cz",
	"s25.sfgame.cz",
	"s26.sfgame.cz",
	"s27.sfgame.cz",
	"s28.sfgame.cz",
	"s29.sfgame.cz",
	"s30.sfgame.cz",
	"s31.sfgame.cz",
	"s32.sfgame.cz",
	"s33.sfgame.cz",
	"s34.sfgame.cz",
	"s1.sfgame.de",
	"s2.sfgame.de",
	"s3.sfgame.de",
	"s4.sfgame.de",
	"s5.sfgame.de",
	"s6.sfgame.de",
	"s7.sfgame.de",
	"s8.sfgame.de",
	"s9.sfgame.de",
	"s10.sfgame.de",
	"s11.sfgame.de",
	"s12.sfgame.de",
	"s13.sfgame.de",
	"s14.sfgame.de",
	"s15.sfgame.de",
	"s16.sfgame.de",
	"s17.sfgame.de",
	"s18.sfgame.de",
	"s19.sfgame.de",
	"s20.sfgame.de",
	"s21.sfgame.de",
	"s22.sfgame.de",
	"s23.sfgame.de",
	"s24.sfgame.de",
	"s25.sfgame.de",
	"s26.sfgame.de",
	"s27.sfgame.de",
	"s28.sfgame.de",
	"s29.sfgame.de",
	"s30.sfgame.de",
	"s31.sfgame.de",
	"s32.sfgame.de",
	"s33.sfgame.de",
	"s34.sfgame.de",
	"s35.sfgame.de",
	"s36.sfgame.de",
	"w1.sfgame.net",
	"w2.sfgame.net",
	"w3.sfgame.net",
	"s37.sfgame.de",
	"w4.sfgame.net",
	"w5.sfgame.net",
	"w6.sfgame.net",
	"w7.sfgame.net",
	"w8.sfgame.net",
	"w9.sfgame.net",
	"w10.sfgame.net",
	"w11.sfgame.net",
	"w12.sfgame.net",
	"w13.sfgame.net",
	"w14.sfgame.net",
	"w15.sfgame.net",
	"w16.sfgame.net",
	"w17.sfgame.net",
	"w18.sfgame.net",
	"w19.sfgame.net",
	"w20.sfgame.net",
	"w21.sfgame.net",
	"w22.sfgame.net",
	"w23.sfgame.net",
	"w24.sfgame.net",
	"s38.sfgame.de",
	"w25.sfgame.net",
	"w26.sfgame.net",
	"w27.sfgame.net",
	"w28.sfgame.net",
	"w30.sfgame.net",
	"w31.sfgame.net",
	"s39.sfgame.de",
	"w32.sfgame.net",
	"w33.sfgame.net",
	"w34.sfgame.net",
	"w35.sfgame.net",
	"s40.sfgame.de",
	"w36.sfgame.net",
	"w37.sfgame.net",
	"w38.sfgame.net",
	"w39.sfgame.net",
	"s41.sfgame.de",
	"w40.sfgame.net",
	"w41.sfgame.net",
	"w42.sfgame.net",
	"w43.sfgame.net",
	"s42.sfgame.de",
	"w44.sfgame.net",
	"w45.sfgame.net",
	"w46.sfgame.net",
	"buffed.sfgame.de",
	"rtl2.sfgame.de",
	"gamona.sfgame.de",
	"xchar.sfgame.de",
	"ingame.sfgame.de",
	"sevengames.sfgame.de",
	"rtl.sfgame.de",
	"speed.sfgame.net",
	"s1.sfgame.dk",
	"s2.sfgame.dk",
	"tv2.sfgame.dk",
	"s1.sfgame.es",
	"s2.sfgame.es",
	"s3.sfgame.es",
	"s4.sfgame.es",
	"s5.sfgame.es",
	"s6.sfgame.es",
	"s7.sfgame.es",
	"s8.sfgame.es",
	"s9.sfgame.es",
	"s10.sfgame.es",
	"s11.sfgame.es",
	"s12.sfgame.es",
	"minijuegos.sfgame.es",
	"s1.sfgame.fr",
	"s2.sfgame.fr",
	"s3.sfgame.fr",
	"s4.sfgame.fr",
	"s5.sfgame.fr",
	"s6.sfgame.fr",
	"s7.sfgame.fr",
	"s8.sfgame.fr",
	"s9.sfgame.fr",
	"s10.sfgame.fr",
	"s11.sfgame.fr",
	"s12.sfgame.fr",
	"s13.sfgame.fr",
	"s14.sfgame.fr",
	"s15.sfgame.fr",
	"s1.sfgame.co.uk",
	"s2.sfgame.co.uk",
	"s1.sfgame.gr",
	"s2.sfgame.gr",
	"s3.sfgame.gr",
	"s4.sfgame.gr",
	"s5.sfgame.gr",
	"s6.sfgame.gr",
	"123playgames.sfgame.gr",
	"s1.sfgame.hu",
	"s2.sfgame.hu",
	"s3.sfgame.hu",
	"s4.sfgame.hu",
	"s5.sfgame.hu",
	"s6.sfgame.hu",
	"s7.sfgame.hu",
	"s8.sfgame.hu",
	"s9.sfgame.hu",
	"s10.sfgame.hu",
	"s11.sfgame.hu",
	"s12.sfgame.hu",
	"s13.sfgame.hu",
	"s14.sfgame.hu",
	"s15.sfgame.hu",
	"s16.sfgame.hu",
	"s17.sfgame.hu",
	"s18.sfgame.hu",
	"s19.sfgame.hu",
	"s1.sfgame.in",
	"s1.sfgame.it",
	"s2.sfgame.it",
	"s3.sfgame.it",
	"s4.sfgame.it",
	"s5.sfgame.it",
	"s6.sfgame.it",
	"s7.sfgame.it",
	"s8.sfgame.it",
	"s1.sfgame.jp",
	"s1.sfgame.mx",
	"s1.sfgame.nl",
	"s2.sfgame.nl",
	"s3.sfgame.nl",
	"s1.sfgame.pl",
	"s2.sfgame.pl",
	"s3.sfgame.pl",
	"s4.sfgame.pl",
	"s5.sfgame.pl",
	"s6.sfgame.pl",
	"s7.sfgame.pl",
	"s8.sfgame.pl",
	"s9.sfgame.pl",
	"s10.sfgame.pl",
	"s11.sfgame.pl",
	"s12.sfgame.pl",
	"s13.sfgame.pl",
	"s14.sfgame.pl",
	"s15.sfgame.pl",
	"s16.sfgame.pl",
	"s17.sfgame.pl",
	"s18.sfgame.pl",
	"s19.sfgame.pl",
	"s20.sfgame.pl",
	"s21.sfgame.pl",
	"s22.sfgame.pl",
	"s23.sfgame.pl",
	"s24.sfgame.pl",
	"s25.sfgame.pl",
	"s26.sfgame.pl",
	"s27.sfgame.pl",
	"s28.sfgame.pl",
	"s29.sfgame.pl",
	"s30.sfgame.pl",
	"s31.sfgame.pl",
	"s32.sfgame.pl",
	"s33.sfgame.pl",
	"s34.sfgame.pl",
	"s35.sfgame.pl",
	"s36.sfgame.pl",
	"s37.sfgame.pl",
	"s38.sfgame.pl",
	"s39.sfgame.pl",
	"wp.sfgame.pl",
	"s1.sfgame.com.pt",
	"s2.sfgame.com.pt",
	"s3.sfgame.com.pt",
	"s4.sfgame.com.pt",
	"s5.sfgame.com.pt",
	"s6.sfgame.com.pt",
	"s7.sfgame.com.pt",
	"s8.sfgame.com.pt",
	"s1.sfgame.ro",
	"s1.sfgame.ru",
	"s1.sfgame.se",
	"s2.sfgame.se",
	"s1.sfgame.sk",
	"s2.sfgame.sk",
	"s3.sfgame.sk",
	"s1.sfgame.web.tr",
	"s2.sfgame.web.tr",
	"s3.sfgame.web.tr",
	"s4.sfgame.web.tr",
	"s5.sfgame.web.tr",
	"s6.sfgame.web.tr",
	"s1.sfgame.us",
	"s2.sfgame.us",
	"s3.sfgame.us",
	"s4.sfgame.us",
	"s5.sfgame.us",
	"s6.sfgame.us",
	"s7.sfgame.us",
	"s8.sfgame.us",
	"s9.sfgame.us",
	"s10.sfgame.us");

	$validate = false;
	foreach($serverlist as $server){
		
		if($server == $addr){
			$validate = true;
		}
	}
	
	return $validate;
}
?>
