<?php

namespace timesplinter\tsfw\session\test;

use timesplinter\tsfw\session\TrustedSession;

class TrustedSessionTest extends \PHPUnit_Framework_TestCase {
	protected function setUp()
	{
		// Start a new default (untrusted) session
		session_start();
		session_destroy();
		session_regenerate_id(true);

		// Mock up trusted session identifiers for PHPs CLI
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$_SERVER['HTTP_USER_AGENT'] = 'PHPUnit';
	}


	public function testTrustedSession() {
		$currentSessionId = session_id();

		$trustedSession = new TrustedSession();

		$this->assertEquals(false, $trustedSession->isTrusted(), 'Is session trusted (hope not)');
		$this->assertEquals(true, $trustedSession->start(), 'Start session');

		$this->assertEquals(true, $trustedSession->isTrusted(), 'Is session trusted (now it should)');
		$this->assertNotEquals($currentSessionId, $trustedSession->getID(), 'Session ID should be different');

		$currentSessionId = $trustedSession->getID();

		// Simulate a session hijacking
		$_SERVER['REMOTE_ADDR'] = '192.168.1.102';

		// Hijacker will do a session start with a new request
		$this->assertEquals(true, $trustedSession->start(), 'Start hijacked session');
		$this->assertNotEquals($currentSessionId, $trustedSession->getID(), 'Hijacked session should get a new ID');
		$this->assertEquals(true, $trustedSession->isTrusted(), 'Is hijacked session trusted (it should because it\'s a new session)');
	}
}