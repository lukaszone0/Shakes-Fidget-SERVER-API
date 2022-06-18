<?php
/**
 * <pre>
 * SFApi 2.0
 * response class
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */
namespace SFBOT;

final class response{

	public $message = "null";
	public $requeststatus = "error";

	public function set($msg, $status = "error"){
		$this->message = $msg;
		$this->requeststatus = $status;
	}
}
?>
