<?php
$main_account_token = "MAIN-TOKEN";
$main_gauge_id = "MAIN-ID";
$member_account_token = "SUB-ACCOUNT-TOKEN";
$member_gauge_id = "SUB-ACCOUNT-ID";

// Get information from Gauges
$my_info = $this->get_gauges_info($main_account_token);		
echo "my_info<pre>"; var_dump($my_info); echo "</pre>";
if (is_numeric($my_info)) {
	$error = $this->gauges_api_errors($my_info);
	echo "ERROR: ".$error."<br />";
}

// Get information from Gauges
/*$new_info = array("first_name" => "John");
$my_new_info = $this->set_gauges_info($main_account_token, $new_info);		
echo "my_new_info<pre>"; var_dump($my_new_info); echo "</pre>";
if (is_numeric($my_new_info)) {
	$error = $this->gauges_api_errors($my_new_info);
	echo "ERROR: ".$error."<br />";
}
$new_info = array("last_name" => "Doe");
$my_new_info = $this->set_gauges_info($main_account_token, $new_info);		
echo "my_new_info<pre>"; var_dump($my_new_info); echo "</pre>";
if (is_numeric($my_new_info)) {
	$error = $this->gauges_api_errors($my_new_info);
	echo "ERROR: ".$error."<br />";
}*/

// Create a new client API
/*$new_client = "my-website";
$new_api_client = $this->create_gauges_api_client($main_account_token, $new_client);
echo "new_api_client<pre>"; var_dump($new_api_client); echo "</pre>";
if (is_numeric($new_api_client)) {
	$error = $this->gauges_api_errors($new_api_client);
	echo "ERROR: ".$error."<br />";
}*/

// Delete an existing client API
/*$temp_key = "TEST-KEY";
$delete_client = $this->delete_gauges_api_client($main_account_token, $temp_key);
echo "delete_client<pre>"; var_dump($delete_client); echo "</pre>";
if (is_numeric($delete_client)) {
	$error = $this->gauges_api_errors($delete_client);
	echo "ERROR: ".$error."<br />";
}*/
		
// Get client api information from Gauges
$my_api_clients_info = $this->get_gauges_api_clients($main_account_token);		
echo "my_api_clients_info<pre>"; var_dump($my_api_clients_info); echo "</pre>";
if (is_numeric($my_api_clients_info)) {
	$error = $this->gauges_api_errors($my_api_clients_info);
	echo "ERROR: ".$error."<br />";
}

// Create new gauge
/*$title = "your-website";
$time_zone = "Central Time (US %26 Canada)";
$new_gauge = $this->create_gauge($main_account_token, $title, $time_zone);
echo "new_gauge<pre>"; var_dump($new_gauge); echo "</pre>";*/

// Delete a gauge
$delete_gauge = $this->delete_gauge($member_account_token, $member_gauge_id);
echo "delete_gauge<pre>"; var_dump($delete_gauge); echo "</pre>";

// Get gauge info
$my_gauge = $this->get_gauge_info($main_account_token, $main_gauge_id);
echo "my_gauge<pre>"; var_dump($my_gauge); echo "</pre>";

// end index()
}	

public function get_gauges_info($token="") {
	if ($token!="") {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/me'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function set_gauges_info($token="",$items=array()) {
	//Only one item can be changed at a time
	if (($token!="") && (count($items)==1)) {
		$qry_str = "";
		$values = count($items);
		$count = 0;
		foreach($items AS $a => $b) {
			$qry_str .= $a.'='.$b;
			$count++;
			if ($count<$values) { $qry_str .= ","; }
		}

		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/me'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function get_gauges_api_clients($token="") {
	if ($token!="") {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/clients'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function check_gauges_api_client_name($token="",$new_client="") {
	if (($token!="") && ($new_client!="")) {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/clients'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 		

		$result = json_decode($data);
	
		foreach($result->clients AS $client) {
			if ($client->description == $new_client) { return true; }
		}
	
		// not found
		return -3;
	}

	return -1;
}

public function check_gauges_api_client_id($token="",$api_client="") {
	if (($token!="") && ($api_client!="")) {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/clients'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 		

		$result = json_decode($data);
	
		foreach($result->clients AS $client) {
			if ($client->key == $api_client) { return true; }
		}
	
		// not found
		return -4;
	}

	return -5;
}

public function create_gauges_api_client($token="",$description="") {
	$check_api_client = $this->check_gauges_api_client_name($token, $description);
	if (is_numeric($check_api_client)) { return $check_api_client; }

	if (($token!="") && ($description!="") && ($check_api_client)) {
		$qry_str = "description=".$description;
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/clients');
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$data = curl_exec($ch); 
		curl_close($ch);
	
		return json_decode($data);
	}
	else {
		return -2;
	}

	return false;
}

public function delete_gauges_api_client($token="",$api_key="") {
	$check_api_client_id = $this->check_gauges_api_client_id($token, $api_key);
	if (is_numeric($check_api_client_id)) { return $check_api_client_id; }

	if (($token!="") && ($api_key!="")) {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/clients/'.$api_key); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function get_gauge_info($token="", $gauge_id) {
	if (($token!="") && ($gauge_id!="")) {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/gauges/'.$gauge_id); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function create_gauge($token="", $title="", $time_zone="") {
	if (($token!="") && ($title!="") && ($time_zone!="")) {
		$qry_str = "title=".$title."&tz=".$time_zone;
	
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/gauges'); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function delete_gauge($token, $gauge_id) {
	if (($token!="") && ($gauge_id!="")) {
		$headers = array("X-Gauges-Token: ".$token);
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'https://secure.gaug.es/gauges/'.$gauge_id); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		$data = curl_exec($ch); 
		curl_close($ch); 		

		return json_decode($data);
	}

	return false;
}

public function gauges_api_errors($error=0) {
	$result = "";

	switch($error) {
		case -1:
			$result = "Token/API Client description is empty.";
			break;
		case -2:
			$result = "1) Token/API Client description is empty, or 2) API client name error.";
			break;
		case -3:
			$result = "API client name not present.";
			break;
		case -4:
			$result = "API client key not present.";
			break;
		case -5:
			$result = "Token/API Client key is empty.";
			break;
		default:
			break;
	}

	return $result;
}
?>