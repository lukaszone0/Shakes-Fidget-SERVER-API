<?php
/**
 * <pre>
 * SFApi 2.0
 * socket class
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */

namespace SFBOT;

final class Socket{

    public function send($server, $data){
        
        $query = "http://" . $server . "/req.php?req=" . $data ."&rnd=".time();
        
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0\r\n" .
                "Accept-Language: pl,en-US;q=0.7,en;q=0.3\r\n" 
            )
          );
          

        $context = stream_context_create($opts);
        
        $ret = file_get_contents($query, false, $context);
        
        $obj = sfjson($ret);

        return $obj;
    }
}
?>