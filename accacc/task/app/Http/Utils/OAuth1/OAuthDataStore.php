<?php

namespace App\Http\Utils\OAuth1;

/**
 *
 * @ignore
 *
 */
class OAuthDataStore {
	function lookup_consumer($consumer_key) {
		// implement me
	}
	function lookup_token($consumer, $token_type, $token) {
		// implement me
	}
	function lookup_nonce($consumer, $token, $nonce, $timestamp) {
		// implement me
	}
	function new_request_token($consumer) {
		// return a new token attached to this consumer
	}
	function new_access_token($token, $consumer) {
		// return a new access token attached to this consumer
		// for the user associated with this token if the request token
		// is authorized
		// should also invalidate the request token
	}
}