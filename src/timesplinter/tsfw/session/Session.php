<?php

namespace timesplinter\tsfw\session;

/**
 * @author Pascal Muenst <dev@timesplinter.ch>
 * @copyright Copyright (c) 2014, TiMESPLiNTER Webdevelopment
 */
class Session implements SessionInterface {
	/**
	 * Start new or resume existing session
	 * @return bool This function returns true if a session was successfully started, otherwise false.
	 */
	public function start() {
		if($this->getStatus() === PHP_SESSION_ACTIVE)
			return true;

		return session_start();
	}

	/**
	 * Destroys all data registered to a session
	 * @return bool Returns TRUE on success or FALSE on failure.
	 */
	public function destroy() {
		return session_destroy();
	}

	/**
	 * Write session data and end session
	 */
	public function close() {
		session_write_close();
	}

	/**
	 * Update the current session id with a newly generated one
	 * @param bool $deleteOldSession Whether to delete the old associated session file or not.
	 * @return bool true on success or false on failure.
	 */
	public function regenerateID($deleteOldSession = false) {
		return session_regenerate_id($deleteOldSession);
	}

	/**
	 * Returns the current session status
	 * @return int
	 */
	public function getStatus() {
		return session_status();
	}

	/**
	 * Get the current session id
	 * @return string The current session id
	 */
	public function getID() {
		return session_id();
	}

	/**
	 * Set the current session id
	 * @param string $id The new session id
	 * @return string The current session id
	 */
	public function setID($id) {
		return session_id($id);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return session_name();
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function setName($name) {
		return session_name($name);
	}

	/**
	 * Get the current session save path
	 * @return string
	 */
	public function getSavePath() {
		return session_save_path();
	}

	/**
	 * Set the current session save path
	 * @param string $savePath The new session save path
	 * @return string The current session save path
	 */
	public function setSavePath($savePath) {
		return session_save_path($savePath);
	}

	/**
	 *
	 * @param string|int $key The key where to store the value
	 * @param mixed $value The value to store
	 */
	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * @param string|int $key The key for the stored value to get
	 * @param mixed $default Default value if data is not registered
	 * @return mixed The stored value
	 */
	public function get($key, $default = null) {
		return (array_key_exists($key, $_SESSION) === true)?$_SESSION[$key]:$default;
	}
}

/* EOF */ 