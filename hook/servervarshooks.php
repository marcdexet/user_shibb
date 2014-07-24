<?php
/**
 * ownCloud - 
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

namespace OCA\User_Servervars2\Hook;

use \OCP\User;

/**
 *
 */
class ServerVarsHooks {

	var $tokenService;
	var $userAndGroupService;


	function __construct($tokenService, $userAndGroupService, $logger=null) {
		$this->tokenService = $tokenService;
		$this->userAndGroupService = $userAndGroupService;
	}


	function onPostLogin($user, $password) {

		$justCreatedUser = null;
		$uag = $this->userAndGroupService;
		$uid = $user->getUID();

		if ( $uid === $this->tokenService->checkTokens() ) {

			if ( $uag->isAutoCreateUser() ) {

				$justCreatedUser = $uag->provisionUser($uid);
			}

			if ( $justCreatedUser || $uag->isUpdateUserData() ) {

				$uag->updateDisplayName( $uid, 	$this->tokenService->getDisplayName() );
				$uag->updateMail( 		$uid ,  $this->tokenService->getEmail());
				$uag->updateGroup( 		$uid, 	$this->tokenService->getGroupsFromToken() );

			}
		} 
	}


	function register($userSession) {
		$userSession->listen('\OC\User', 'postLogin', function($user, $password) { 
			return $this->onPostLogin($user, $password); 
		});
	}
}