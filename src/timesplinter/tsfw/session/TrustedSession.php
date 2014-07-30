<?php

namespace timesplinter\tsfw\session;

/**
 * This is an extension to the basic Session class which ensures that the currently active session is one that can
 * be trusted.
 * @author Pascal Muenst <dev@timesplinter.ch>
 * @copyright Copyright (c) 2014, TiMESPLiNTER Webdevelopment
 */
class TrustedSession extends Session {
	protected $idlingTime = 1800;

	/**
	 * Starts the session and checks if the current session isn't a trusted one or if the current session is expired
	 * (caused by too long idling @see $this->idlingTime). If yes the session will be destroyed, restarted and signed.
	 * @return bool True on successful start of a trusted session else false
	 */
	public function start()
	{
		if(parent::start() === false)
			return false;
		
		if($this->isTrusted() === false || $this->isExpired() === true) {
			if($this->destroy() === false || parent::start() === false || $this->regenerateID(true) === false)
				return false;
		}
		
		$this->updateLastActivity();

		return true;
	}

	public function regenerateID($deleteOldSession = false)
	{
		if(parent::regenerateID($deleteOldSession) === false)
			return false;

		$this->set('TRUSTED_SID', true);
		$this->set('TRUSTED_REMOTE_ADDR', isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null);
		$this->set('PREV_USER_AGENT', isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null);

		return true;
	}

	public function isTrusted()
	{
		$requestRemoteAddress = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
		$requestUserAgent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;

		$trustedRemoteAddress = $this->get('TRUSTED_REMOTE_ADDR');
		$prevUserAgent = $this->get('PREV_USER_AGENT');

		return !($this->get('TRUSTED_SID') !== true || $trustedRemoteAddress !== $requestRemoteAddress || $prevUserAgent !== $requestUserAgent);
	}
	
	public function isExpired()
	{
		return (($lastActivity = $this->get('LAST_ACTIVITY')) === null || (time() - $lastActivity) > $this->idlingTime);
	}
	
	public function updateLastActivity() {
		if($this->getStatus() !== PHP_SESSION_ACTIVE)
			return;

		$this->set('LAST_ACTIVITY', time());
	}

	/**
	 * Set the idling time in seconds
	 * @param int $idlingTime New diling time in seconds
	 */
	public function setIdlingTime($idlingTime)
	{
		$this->idlingTime = $idlingTime;
	}

	/**
	 * Get the current idling time in seconds
	 * @return int Current idling time in seconds
	 */
	public function getIdlingTime()
	{
		return $this->idlingTime;
	}
}

/* EOF */