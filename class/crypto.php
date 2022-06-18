<?php
/**
 * <pre>
 * SFApi 2.0
 * crypto class
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */

namespace SFBOT;

final class Crypto{

	public function encrypt($data, $cryptokey){
		
		$array = openssl_get_cipher_methods();
		$cipher = "aes-128-cbc";
		if (in_array($cipher, openssl_get_cipher_methods()))
		{
			
			$iv = 'jXT#/vz]3]5X7Jl\\';
			// OPENSSL_RAW_DATA return as BITES // when 0 return as base64
			return strtr(openssl_encrypt($data, $cipher, $cryptokey, 0, $iv), '+/', '-_');
		}
	}
}
?>