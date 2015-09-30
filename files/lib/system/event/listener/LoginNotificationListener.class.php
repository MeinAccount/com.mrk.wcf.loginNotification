<?php
namespace wcf\system\event\listener;
use wcf\data\user\follow\UserFollow;
use wcf\system\push\PushHandler;
use wcf\system\WCF;

/**
 * Sends login notifications to users that follow you.
 *
 * @author    Magnus Kühn
 * @copyright 2015 Magnus Kühn
 * @package   com.mrk.wcf.loginNotification
 */
class LoginNotificationListener implements IParameterizedEventListener {
	/**
	 * Executes this action.
	 *
	 * @param \wcf\form\LoginForm $eventObj    Object firing the event
	 * @param string              $className   class name of $eventObj
	 * @param string              $eventName   name of the event fired
	 * @param array               &$parameters given parameters
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$sql = "SELECT	userID
			FROM	".UserFollow::getDatabaseTableName()."
			WHERE	followUserID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(WCF::getUser()->userID));

		// collect users
		$userIDs = array();
		while ($row = $statement->fetchArray()) {
			$userIDs[] = $row['userID'];
		}

		// send push notifications
		if ($userIDs) {
			PushHandler::getInstance()->sendDeferredMessage('com.mrk.wcf.loginNotification', $userIDs, array('username' => WCF::getUser()->username));
		}
	}
}
