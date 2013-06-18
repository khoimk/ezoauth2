<?php namespace Khoimk\Ezoauth2;
use Illuminate\Support\ServiceProvider;
use \Session as Session;
class Ezoauth2{

	private $config;

	private $client;

	private $oauth2;

	public function setConfig($provider){
		$configs = include app_path() . '/config/ezoauth2.php';
		$this->config = $configs[$provider];
	}

//Facebook
	private function facebookAuthenticate(){
		$authUrl = 'https://www.facebook.com/dialog/oauth?client_id='.$this->config['key']['id'].
		'&redirect_uri='.$this->config['returnUrl'];

		if(isset($_GET['error_description'])){
			return null;
		}

		if (isset($_GET['code'])) {
			$url = 'https://graph.facebook.com/oauth/access_token?client_id='.$this->config['key']['id'].
			'&redirect_uri='.urlencode($this->config['returnUrl']).
			'&client_secret='.$this->config['key']['secret'].
			'&code='.$_GET['code'];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$response = curl_exec($ch);
			curl_close($ch);
			parse_str($response, $param);
			//var_dump($param);
			Session::set('access_token',$param['access_token']);
			$authUrl = $this->config['returnUrl'];
		}

		if (Session::get('access_token')) {
			$authUrl = null;
		}
		return $authUrl;
	}

	public function facebookGetUserInfo(){
		if(Session::get('access_token')){
			$url = 'https://graph.facebook.com/me?access_token='.Session::get('access_token');
			//var_dump($url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$response = curl_exec($ch);
			curl_close($ch);
			//var_dump($response);
			return json_decode($response);
		}
		return null;
	}
//Google
	public function logout(){
		if ($_REQUEST && $_REQUEST['logout']) {
			Session::forget('token');
			$this->client->revokeToken();
		}
	}

	

	private function googleAuthenticate(){
		$this->client = new \Google_Client();
		$this->client->setApplicationName("Tinh gọn");
		$this->client->setClientId($this->config['key']['id']);
		$this->client->setClientSecret($this->config['key']['secret']);
		$this->client->setRedirectUri($this->config['returnUrl']);
		$this->oauth2 = new \Google_Oauth2Service($this->client);

		if (isset($_GET['code'])) {
			$this->client->authenticate($_GET['code']);
			Session::set('token',$this->client->getAccessToken());
			return $this->config['returnUrl'];
		}

		if (Session::get('token')) {
		 	$this->client->setAccessToken(Session::get('token'));
		}

		if (!$this->client->getAccessToken()) {
			$authUrl = $this->client->createAuthUrl();
			return $authUrl;
		}
	}
	public function googleGetUserInfo(){
		if ($this->client->getAccessToken()) {
			$user = $this->oauth2->userinfo->get();
			// The access token may have been updated lazily.
			Session::set('token', $this->client->getAccessToken());
			return $user;
		}
		return null;
	}
//general
	public function authenticate(){
		switch ($this->config['name']) {
			case 'google':
				return $this->googleAuthenticate();
				break;
			case 'facebook':
				return $this->facebookAuthenticate();
				break;
		}
	}

	public function getUserInfo(){
		switch ($this->config['name']) {
			case 'google':
				return $this->googleGetUserInfo();
				break;
			case 'facebook':
				return $this->facebookGetUserInfo();
				break;
		}
	}
}