<?php

namespace timesplinter\tsfw\session;

/**
 * This is an extension to the basic Session class which ensures that the currently active session is one that can
 * be trusted in it.
 * @author Pascal Muenst <dev@timesplinter.ch>
 * @copyright Copyright (c) 2014, TiMESPLiNTER Webdevelopment
 */
class TrustedSession extends Session {
	public function start()
	{
		if(parent::start() === false)
			return false;

		if($this->isValid() === false) {
			if($this->regenerateID(true) === false)
				return false;
		}

		return true;
	}

	public function regenerateID($deleteOldSession = false)
	{
		if($this->isTrusted() !== true && $this->getStatus() === PHP_SESSION_ACTIVE) {
			if(parent::destroy() === false || parent::start() === false)
				return false;
		}

		if(parent::regenerateID($deleteOldSession) === false)
			return false;

		$this->set('TRUSTED_SID', true);
		$this->set('TRUSTED_REMOTE_ADDR', isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null);
		$this->set('PREV_USERAGENT', isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null);

		return true;
	}

	protected function isValid() {
		$requestRemoteAddress = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:null;
		$requestUserAgent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;

		$trustedRemoteAddress = $this->get('TRUSTED_REMOTE_ADDR');
		$prevUserAgent = $this->get('PREV_USERAGENT');

		return !($trustedRemoteAddress !== $requestRemoteAddress || $prevUserAgent !== $requestUserAgent);
	}

	public function isTrusted() {
		return ($this->get('TRUSTED_SID') === true);
	}
}

/* EOF */