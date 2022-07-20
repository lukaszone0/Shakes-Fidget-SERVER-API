<?php
/**
 * <pre>
 * SFApi 1.0
 * response class
 * Last Updated: $Date: 2022-07-20
 * </pre>
 *
 * @author 		lukaszone0
 * @package		SFApi
 * @version		1.0
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
