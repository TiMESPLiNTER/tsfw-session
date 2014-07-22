<?php

namespace timesplinter\tsfw\session;

/**
 * @author Pascal Muenst <dev@timesplinter.ch>
 * @copyright Copyright (c) 2014, TiMESPLiNTER
 */
interface SessionInterface {
	/**
	 * Start new or resume existing session
	 * @return bool This function returns true if a session was successfully started, otherwise false.
	 */
	public function start();

	/**
	 * Destroys all data registered to a session
	 * @return bool Returns TRUE on success or FALSE on failure.
	 */
	public function destroy();

	/**
	 * Write session data and end session
	 */
	public function close();

	/**
	 * Update the current session id with a newly generated one
	 * @param bool $deleteOldSession Whether to delete the old associated session file or not.
	 * @return bool true on success or false on failure.
	 */
	public function regenerateID($deleteOldSession = false);

	/**
	 * Returns the current session status
	 * @return int
	 */
	public function getStatus();

	/**
	 * Get the current session id
	 * @return string The current session id
	 */
	public function getID();

	/**
	 * Set the current session id
	 * @param string $id The new session id
	 * @return string The current session id
	 */
	public function setID($id);

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return string
	 */
	public function setName($name);

	/**
	 * Get the current session save path
	 * @return string
	 */
	public function getSavePath();

	/**
	 * Set the current session save path
	 * @param string $savePath The new session save path
	 * @return string The current session save path
	 */
	public function setSavePath($savePath);

	/**
	 *
	 * @param string|int $key The key where to store the value
	 * @param mixed $value The value to store
	 */
	public function set($key, $value);

	/**
	 * @param string|int $key The key for the stored value to get
	 * @param mixed $default Default value if data is not registered
	 * @return mixed The stored value
	 */
	public function get($key, $default = null);
}

/* EOF */ 