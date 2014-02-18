<?php
/*
ApplicationKey: d8ea6b06-bc45-e9a4-fd7d-54af16f05922
Shared Secret (Token Bypass): 8rkv0VPa2m
Shared Secret (User Proxy): 4jYEJWYezA
*/
class RestAPI
{
	private $_data;
	private $_appKey;
	private $_appSecretBypass;
	private $_appSecretProxy;
	private $_username;
	private $_header_type;

	public function __construct($params = array())
	{
		$this->_data = $params;
		/* not to be shared or given out */
		$this->_appKey = "d8ea6b06-bc45-e9a4-fd7d-54af16f05922";
		$this->_appSecretBypass = "8rkv0VPa2m";
		$this->_appSecretProxy = "4jYEJWYezA";
		/*********************************/

		$this->_username = $this->proxyUser.":".$this->proxyCompany;
		//$this->setSecret("c61216e1a58e1574d88f75ec48ced365");
		$this->_header_type = isset($params['type']) ? $params['type'] : "proxy";

		if(!isset($this->_endpoint))
		{
			$this->_endpoint = "https://api.omniture.com/admin/1.3/rest/";
			$this->_endpoint = str_replace('"', "", stripslashes($this->call("Company.GetEndpoint", array(array("company"=>$this->proxyCompany)))));
		}
	}
	
	public function __call($name, $arguments)
	{
		//var_dump($name." does not exist");
	}

	public function __get($name)
	{
		if(!isset($this->_data[$name]))
			return false;
		return $this->_data[$name];
	}

	public function __isset($name)
	{
		return isset($this->_params[$name]);
	}

	private function _get_headers()
	{
		$nonce = md5(rand(), true);
		$base64nonce = base64_encode($nonce);
		if($this->_header_type == "bypass")
		{
			$created = gmdate('Y-m-dTH:i:sZ');
			$digest = base64_encode(sha1($nonce.$created.$this->secret,true));

			$appNonce = md5(rand(), true);
			$appDigest = base64_encode(sha1($appNonce.$this->_appSecretBypass,true));
			$appBase64nonce = base64_encode($appNonce);

			$ret_val = array(
				'X-WSSE: UsernameToken Username="'.$this->_username.'", PasswordDigest="'.$digest.'", Nonce="'.$base64nonce.'", Created="'.$created.'" AppKey="'.$this->_appKey.'", AppDigest="'.$appDigest.'", AppNonce="'.$appBase64nonce.'"'
			);
		}
		else if($this->_header_type == "proxy")
		{
			$digest = base64_encode(sha1($nonce.$this->_appSecretProxy,true));

			$ret_val = array(
				'X-WSSE-APPLICATION: UsernameToken Username="'.$this->_appKey.'", AppDigest="'.$digest.'", Nonce="'.$base64nonce.'"',
				'X-PROXY-COMPANY: ' . $this->proxyCompany,
				'X-PROXY-USER: ' . $this->proxyUser,
			);
		}
		else
		{
			echo "ERROR: Invalid header typ requested\n";
			exit;
		}
		return $ret_val;
	}

	public function call($method = "Company.GetTokenCount", $params = array())
	{
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $this->_endpoint."?method=".$method);

		// return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// set WSSE header
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_get_headers());
		
		if($params && count($params))
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			foreach($params as $param)
			{
				curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($param));
			}
		}

		// $output contains the output string
		$output = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);
		
		return $output;
	}
}

?>
