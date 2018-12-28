<?php 

namespace Faytranevozter\Zenziva;

use Curl\Curl;

/**
 * 	Zenziva SMS Library
 */
class Sms {

	/**
	 * Available Type SMS
	 */
	const AVAILABLE_TYPE = [
		'reguler'      => [
			'subdomain' => 'reguler',
			'path'      => 'apps/smsapi.php',
		],
		'masking'      => [
			'subdomain' => 'alpha',
			'path'      => 'apps/smsapi.php',
		],
		'sms_center'   => [
			'subdomain' => '',
			'path'      => 'apps/smsapi.php',
		],
	];

	/**
	 * Username Zenziva
	 * @var string
	 */
	private $username;
	
	/**
	 * Password Zenziva
	 * @var string
	 */
	private $password;

	/**
	 * Type/Package of Zenziva sms from AVAILABLE_TYPE
	 * @var string
	 */
	public $type = 'reguler';

	/**
	 * Receiver number phone
	 * @var string with prefix 0 (08xxxxxxx)
	 */
	private $to;

	/**
	 * Message to send
	 * @var string
	 */
	private $message;

	/**
	 * URL Scheme
	 */
	const SCHEME = 'https';

	/**
	 * Domain name with no trailing slash
	 */
	const DOMAIN = 'zenziva.net';

	/**
	 * Subdomain
	 * @var string
	 */
	protected $subdomain = 'reguler';

	/**
	 * Full url to send
	 * @var string
	 */
	protected $url = '';

	/**
	 * Constructor
	 * @param string $user zenziva username
	 * @param string $pass zenziva password
	 * @param string $type package zenziva sms
	 */
	function __construct($user='', $pass='', $type='reguler') {
		$this->username($user);
		$this->password($pass);
		$this->type($type);
	}

	/**
	 * Set username
	 * @param string $user
	 * @return $this
	 */
	public function username($user='') {
		$this->username = $user;
		return $this;
	}

	/**
	 * Set password
	 * @param string $pass
	 * @return $this
	 */
	public function password($pass='') {
		$this->password = $pass;
		return $this;
	}

	/**
	 * Set Type
	 * @param string $type
	 * @return $this
	 */
	public function type($type='') {
		if ( ! isset(self::AVAILABLE_TYPE[$type])) {
			throw new \Exception('Type/Package should (' . implode(', ', array_keys(self::AVAILABLE_TYPE)) . ').');
		} else {
			$this->type = $type;
		}
		return $this;
	}

	/**
	 * Set Subdomain
	 * @param  string $value
	 * @return $this
	 */
	public function subdomain($value='') {
		$this->subdomain = $value;
		return $this;
	}

	/**
	 * Set receiver number
	 * @param  string $number with prefix 0 (08xxxxxxx)
	 * @return this
	 */
	public function to($number) {
		if (empty($number)) {
			throw new \Exception('Receiver number is not set.');
		} else {
			$this->to = $number;
		}
		return $this;
	}

	/**
	 * Set message
	 * @param  string $text 
	 * @return this
	 */
	public function message($text) {
		if (empty($text)) {
			throw new \Exception('Message is not set.');
		} else {
			$this->message = $text;
		}
		return $this;
	}

	/**
	 * Send message
	 * @param  string $number Receiver number (optional)
	 * @param  string $text   Message (optional)
	 * @return array          Data
	 */
	public function send($number='', $text='') {

		if ( ! empty($number)) { $this->to($number); }
		if ( ! empty($text)) { $this->message($text); }

		if (empty($this->to)) {
			throw new \Exception('Receiver number is not set.');
		}

		if (empty($this->message)) {
			throw new \Exception('Message is not set.');
		}

		$url = self::get_url();
		$ch = new Curl\Curl();
		$ch->post($url, [
			'userkey' => $this->username,
			'passkey' => $this->password,
			'nohp' => $this->to,
			'pesan' => $this->message,
		]);

		$response = $ch->response;
		$error    = $ch->error;
		$ch->close();

		if ($error) {
			return $ch->error_code;
		} else {
			return simplexml_load_string($response);
		}
	}

	/**
	 * Get url to zenziva
	 * @return string url
	 */
	private function get_url() {

		if ($this->type == 'sms_center') {
			if (empty($this->subdomain)) {
				throw new \Exception('Subdomain is not set.');
			}
		} else {
			// override reguler and masking subdomain
			$this->subdomain(self::AVAILABLE_TYPE[$this->type]['subdomain']);
		}

		$path = self::AVAILABLE_TYPE[$this->type]['path'];

		$this->url = self::SCHEME . '://' . $this->subdomain . '.' . self::DOMAIN . '/' . $path;

		return $this->url;
	}
}
