<?php
/**
 * ownCloud - Context
 *
 * @author Marc DeXeT
 * @copyright 2014 DSI CNRS https://www.dsi.cnrs.fr
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\User_Servervars2\Service\Impl;

use OCA\User_Servervars2\Service\Tokens;

class RemoteTokens implements Tokens {

	function __construct($appConfig) {
		// no use of AppConfig yet
	}

 	/**
 	 * Return the identity provider ( as 'https://idp.example.org/idp/shibboleth')
 	 * @return provider name or false if none
 	 */
 	public function getProviderId(){
 		return $_SERVER['Shib-Identity-Provider'];
 	}
 	/**
 	 * undocumented function
 	 *
 	 * @return user id or false is none
 	 * @author 
 	 **/
 	public function getUserId() {
 		return $this->idx($_SERVER, 'eppn');
 	}

 	public function getDisplayName(){
 		return $_SERVER['displayName'];
 	}

 	public function getEmail() {
 		return $_SERVER['mail'];
 	}

 	public function getGroups() {
 		return array( $_SERVER['ou'] );
 	}
 	/**
 	* @param array array
 	* @param String key
 	* @return value or false
 	*/
 	public function idx(array $array, $key) {
 		if ( isset($array[ $key ] ))  return $array[ $key ];
 		return false;
 	}


	public function  __toString() {
		return "<ProviderID=".$this->getProviderId().", userID=".$this->getUserId()." ".$this->getDisplayName()." mail=".$this->getEmail()." ".$this->getGroups().">";
	}

 }
