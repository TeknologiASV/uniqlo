<?php
require_once 'abstract_api.php';

class MyAPI extends API
{
    private $database = NULL;
    
    public function __construct($request, $origin, $db) {
        parent::__construct($request);
        $this->database = $db;
        // Add authentication, model initialization, etc here
    }
    
    public function authorizeToken($token){
        if(!empty($token)){

        }
        
        return false;
    }

    protected function login(){
        $response = array();
        
		if($this->method != "POST"){
			$response = array (
                "response" => "Failed",
                "message" => "Only accepts POST requests"
            );
        }
		else{
		    $username=$_POST['userEmail'];
            $password=$_POST['userPassword'];
    		
    		if(!empty($email) and !empty($password)){
                $stmt = $this->database->prepare("SELECT * from users where username= ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if(($row = $result->fetch_assoc()) !== null){
                    $password = hash('sha512', $password . $row['salt']);
                    
                    if($password == $row['password']){
                        $_SESSION['userID']=$row['id'];
                        $_SESSION['name']=$row['name'];
                        $_SESSION['role']=$row['role_code'];
                        $token = base64_encode("$email:$password");

                        if ($stmt2 = $this->database->prepare("UPDATE users SET token=? WHERE id=?")) {
                            $stmt2->bind_param('ss', $token, $id);
                            
                            if($stmt2->execute()){
                                $response = array(
                                    "response" => "Success",
                                    "message" => "Login Successfully"
                                );
                            }
                            else{
                                $response = array(
                                    "response" => "Failed",
                                    "message" => "Failed to update token"
                                );
                            }
                        }
                        else{
                            $response = array(
                                "response" => "Failed",
                                "message" => "Failed to Create Token"
                            );
                        }
                    } 
                    else{
                        $response = array (
                            "response" => "Failed",
                            "message" => "Login unsuccessful, password or username is not matched"
                        );
                    }
                }
    		}
    		else{
    		    $response = array (
                    "response" => "Failed",
                    "message" => "Missing Attributes"
                );
    		}
		}
		
		return $response;
	}
    
    protected function keyChecking(){
        $response = array();
        
		if($this->method != "POST"){
			$response = array (
                "status" => "failed",
                "message" => "Only accepts POST requests"
            );
        }
		else{
		    if(!isset($_POST['key'])){
		        $response = array (
                    "status" => "failed",
                    "message" => "Missing Keys"
                );
		    }
		    else{
		        $key = $_POST['key'];
		        
		        if(!empty($key)){
                    $stmt = $this->database->prepare("SELECT tbl_keys.id, users.status, users.role_code FROM tbl_keys, users WHERE key_value = ? AND users.id = tbl_keys.user_id AND users.status = 'active'");
                	$stmt->bind_param('s', $key);
                	$stmt->execute();
                	$stmt->store_result();
                	
                	if ($stmt->num_rows > 0){
                	    if(isset($_POST['check'])){
                	        $check = $_POST['check'];
                	        $stmt->bind_result($id, $status, $role_code);
        	                $stmt->fetch();
                	        
                	        if($check == 'A'){
                                if( 
                                    $role_code == "PRIADMIN" ||
                                    $role_code == "ADMIN" ||
                                    $role_code == "TR" ||
                                    $role_code == "TR1" ||
                                    $role_code == "TR2" ||
                                    $role_code == "TR3" ||
                                    $role_code == "TR4" ||
                                    $role_code == "TR5" ||
                                    $role_code == "TR6" ||
                                    $role_code == "TR7" ||
                                    $role_code == "TR8" ||
                                    $role_code == "TR9" ||
                                    $role_code == "New_Cuber" ||
                                    $role_code == "New_Cuber_1" ||
                                    $role_code == "New_Cuber_2" ||
                                    $role_code == "New_Cuber_3" ||
                                    $role_code == "TR10" ||
                                    $role_code == "TR11" ||
                                    $role_code == "New_Cuber_4" ||
                                    $role_code == "New_Cuber_5" ||
                                    $role_code == "TR12" ||
                                    $role_code == "TR13" ||
                                    $role_code == "TR14" ||
                                    $role_code == "TR15" ||
                                    $role_code == "TR16" ||
                                    $role_code == "TR17" ||
                                    $role_code == "TEST" ||
                                    $role_code == "GIVEAWAY"
                                    ){
                    	            $response = array (
                                        "status" => "success",
                                        "message" => "Successfully login",
                                        "page" => "1"
                                    );
                    	        }
                    	        else{
                    	            $response = array (
                                        "status" => "success",
                                        "message" => "Successfully login",
                                        "page" => "2"
                                    );
                    	        }
                	        }
                	        else if($check == 'C'){
                                if( 
                                    $role_code == "PRIADMIN" ||
                                    $role_code == "ADMIN" ||
                                    $role_code == "TR" ||
                                    $role_code == "TR1" ||
                                    $role_code == "TR2" ||
                                    $role_code == "TR3" ||
                                    $role_code == "TR4" ||
                                    $role_code == "TR5" ||
                                    $role_code == "TR6" ||
                                    $role_code == "TR7" ||
                                    $role_code == "TR8" ||
                                    $role_code == "TR9" ||
                                    $role_code == "New_Cuber" ||
                                    $role_code == "New_Cuber_1" ||
                                    $role_code == "New_Cuber_2" ||
                                    $role_code == "New_Cuber_3" ||
                                    $role_code == "TR10" ||
                                    $role_code == "TR11" ||
                                    $role_code == "New_Cuber_4" ||
                                    $role_code == "New_Cuber_5" ||
                                    $role_code == "TR12" ||
                                    $role_code == "TR13" ||
                                    $role_code == "TR14" ||
                                    $role_code == "TR15" ||
                                    $role_code == "TR16" ||
                                    $role_code == "TR17" ||
                                    $role_code == "TEST" ||
                                    $role_code == "GIVEAWAY"
                                    ){

                    	            $response = array (
                                        "status" => "success",
                                        "message" => "Successfully login"
                                    );
                    	        }
                    	        else{
                    	            $response = array (
                                        "status" => "failed",
                                        "message" => "Sorry! This version is not eligible for your role."
                                    );
                    	        }
                	        }
                	    }
                	    else{
                	        $response = array (
                                "status" => "success",
                                "message" => "Successfully login"
                            );
                	    }
                	}
                	else{
                	    $response = array (
                            "status" => "failed",
                            "message" => "No key found!"
                        );
                	}
                }
                else{
                    $response = array (
                        "status" => "failed",
                        "message" => "Missing Keys"
                    );
                }
		    }
		}
		
		return $response;
	}
    
    protected function logoutDevice(){
        $response = array();
        
        if(isset($_POST['key'])){
        	$key = $_POST['key'];
        	$stmt = $this->database->prepare("UPDATE tbl_keys SET device = null, hostname = null WHERE key_value = ?");
        	$stmt->bind_param('s', $key);
        	
        	if($stmt->execute()){
        		$response = array (
                    "status" => "success",
                    "message" => "Logged Out"
                );
        	} else{
        		$response = array (
                    "status" => "failed",
                    "message" => $stmt->error
                );
        	}
        } 
        else{
        	$response = array (
                "status" => "failed",
                "message" => "Missing Keys"
            );
        }
        
        return $response;
    }
    
    protected function keyConnect(){
        $response = array();
        
        if(isset($_POST['key'], $_POST['device'], $_POST['hostname'])){
            $key = $_POST['key'];
            $device_in = $_POST['device'];
            $hostname_in = $_POST['hostname'];
            $lastDate = date("Y-m-d H:i:s");
            
            $stmt = $this->database->prepare("SELECT tbl_keys.id, tbl_keys.device, tbl_keys.hostname, users.status, users.role_code FROM tbl_keys, users WHERE key_value = ? AND users.id = tbl_keys.user_id");
        	$stmt->bind_param('s', $key);
        	$stmt->execute();
        	$stmt->store_result();
        	
        	if ($stmt->num_rows > 0){
        	    $stmt->bind_result($keyid, $device, $hostname, $userStatus, $role_code);
        	    $stmt->fetch();
        	    
        	    if($device != null && $device != "" && $hostname!=$hostname_in){
        	        $response = array (
                        "status" => "failed",
                        "message" => "Key has been logged in at ".$device
                    );
        	    }
        	    else if($userStatus == "inactive"){
        	        $response = array (
                        "status" => "failed",
                        "message" => "This key has been deactivated"
                    );
        	    }
        	    else{
        	        if ($stmt2 = $this->database->prepare("UPDATE tbl_keys SET device=?, hostname=?, last_update=? WHERE key_value=?")) {
            			$stmt2->bind_param('ssss', $device_in, $hostname_in, $lastDate, $key);
            			
            			if($stmt2->execute()){
            				$stmt2->close();
            				$this->database->close();
            				
            				if(isset($_POST['check'])){
            				    $check = $_POST['check'];
            				    
            				    if($check == 'A'){
            				        if( $role_code == "PRIADMIN" ||
                                        $role_code == "ADMIN" ||
                                        $role_code == "TR" ||
                                        $role_code == "TR1" ||
                                        $role_code == "TR2" ||
                                        $role_code == "TR3" ||
                                        $role_code == "TR4" ||
                                        $role_code == "TR5" ||
                                        $role_code == "TR6" ||
                                        $role_code == "TR7" ||
                                        $role_code == "TR8" ||
                                        $role_code == "TR9" ||
                                        $role_code == "New_Cuber" ||
                                        $role_code == "New_Cuber_1" ||
                                        $role_code == "New_Cuber_2" ||
                                        $role_code == "New_Cuber_3" ||
                                        $role_code == "TR10" ||
                                        $role_code == "TR11" ||
                                        $role_code == "New_Cuber_4" ||
                                        $role_code == "New_Cuber_5" ||
                                        $role_code == "TR12" ||
                                        $role_code == "TR13" ||
                                        $role_code == "TR14" ||
                                        $role_code == "TR15" ||
                                        $role_code == "TR16" ||
                                        $role_code == "TR17" ||
                                        $role_code == "TEST" ||
                                        $role_code == "GIVEAWAY"
                				    ){
                        	            $response = array (
                                            "status" => "success",
                                            "message" => "Successfully login",
                                            "page" => "1"
                                        );
                        	        }
                        	        else{
                        	            $response = array (
                                            "status" => "success",
                                            "message" => "Successfully login",
                                            "page" => "2"
                                        );
                        	        }
            				    }
            				    else if($check == 'C'){
            				        if( $role_code == "PRIADMIN" ||
                                    $role_code == "ADMIN" ||
                                    $role_code == "TR" ||
                                    $role_code == "TR1" ||
                                    $role_code == "TR2" ||
                                    $role_code == "TR3" ||
                                    $role_code == "TR4" ||
                                    $role_code == "TR5" ||
                                    $role_code == "TR6" ||
                                    $role_code == "TR7" ||
                                    $role_code == "TR8" ||
                                    $role_code == "TR9" ||
                                    $role_code == "New_Cuber" ||
                                    $role_code == "New_Cuber_1" ||
                                    $role_code == "New_Cuber_2" ||
                                    $role_code == "New_Cuber_3" ||
                                    $role_code == "TR10" ||
                                    $role_code == "TR11" ||
                                    $role_code == "New_Cuber_4" ||
                                    $role_code == "New_Cuber_5" ||
                                    $role_code == "TR12" ||
                                    $role_code == "TR13" ||
                                    $role_code == "TR14" ||
                                    $role_code == "TR15" ||
                                    $role_code == "TR16" ||
                                    $role_code == "TR17" ||
                                    $role_code == "TEST" ||
                                    $role_code == "GIVEAWAY" 
                                    ){
                    	            	$response = array (
                                            "status"=> "success", 
                                	        "message"=> "ok"
                                        );
                        	        }
                        	        else{
                        	            $response = array (
                                            "status" => "failed",
                                            "message" => "Sorry! This version is not eligible for your account."
                                        );
                        	        }
            				    }
                    	    }
                    	    else{
                    	        $response = array (
                                    "status"=> "success", 
                        	        "message"=> "ok"
                                );
                    	    }
            			} 
            			else{
            			    $response = array (
                                "status" => "failed",
                                "message" => $stmt2->error
                            );
            			}
            		}
        	    }
        	}
        	else{
        	    $response = array (
                    "status" => "failed",
                    "message" => "Key not match"
                );
        	}
        }
        else{
            $response = array (
                "status" => "failed",
                "message" => "Failed to validate key"
            );
        }
        
        return $response;
    }
    
    protected function syncTime(){
        $response = array();
        
		if($this->method != "POST"){
			$response = array (
                "status" => "failed",
                "message" => "Only accepts POST requests"
            );
        }
		else{
		    if(!isset($_POST['key'])){
		        $response = array (
                    "status" => "failed",
                    "message" => "Missing Keys"
                );
		    }
		    else{
		        $key = $_POST['key'];
		        
		        $stmt = $this->database->prepare("SELECT users.default_time FROM tbl_keys, users WHERE key_value = ? AND users.id = tbl_keys.user_id");
            	$stmt->bind_param('s', $key);
            	$stmt->execute();
            	$stmt->store_result();
		        
		        if ($stmt->num_rows > 0){
		            $stmt->bind_result($default_time);
        	        $stmt->fetch();
		            
		            $response = array (
                        "status" => "success",
                        "message" => $default_time
                    );
                }
                else{
                    $response = array (
                        "status" => "failed",
                        "message" => "Key not found"
                    );
                }
		    }
		}
		
		return $response;
	}

    protected function getEntry(){
        $response = array();
        
		if($this->method != "POST"){
			$response = array (
                "status" => "failed",
                "message" => "Only accepts POST requests"
            );
        }
        else{
		    if(!isset($_POST['reason'], $_POST['data'], $_POST['productId'], $_POST['webhookURL'])){
		        $response = array (
                    "status" => "failed",
                    "message" => "Missing Keys"
                );
		    }
		    else{
		        $reason = $_POST['reason']; 
		        $datafrom = $_POST['data'];
		        $productId = $_POST['productId'];
		        $webhookurl = $_POST['webhookURL'];
		        $entryResult = array();
		        $productName;
		        $timestamp = date("c", strtotime("now"));
		        
		        if($reason == 'launchEntryErrorCodes'){
		            $stmt = $this->database->prepare("SELECT * FROM shoes WHERE product_id = ?");
		            $stmt->bind_param('s', $productId);
                	$stmt->execute();
                	$result = $stmt->get_result();
                	
                	if ($row = $result->fetch_assoc()){
                	    $launchdate = strtotime($row['launch_date']);
                        $newlaunchdate = date("Y-m-d H:i:sa", $launchdate);
                        $productName = $row['product_name'];
                        $image = $row['product_image'];
                        
                        $embed = array(
                            "title" => $productName,
                            "type" => "rich",
                            "description" => 'Entry Time : '.$newlaunchdate.PHP_EOL.'Status: ENTRY SUBMISSION FAILED'.PHP_EOL.'Reason: '.$datafrom,
                            "timestamp" => $timestamp,
                            "color" => hexdec( "3366ff" ),
                             "footer" => [
                                "text" => "CubeACO 3.0.2",
                                "icon_url" => "https://sneakercube.io/images/logo-cube.png"
                            ],
                            "thumbnail" => [
                                "url" => $image
                            ],
                            "author" => [
                                "name" => "Failed to Submit Entry",
                                "url" => ""
                            ]
                        );
                        
                        array_push($entryResult, $embed);
            	    }
		        }
		        else{
		            $stmt = $this->database->prepare("SELECT * FROM shoes WHERE product_id = ?");
                	$stmt->execute();
                	$result = $stmt->get_result();
                	
                	if ($row = $result->fetch_assoc()){
                	    $data = json_decode($row['data']);
                	    $entryDetails = json_decode($datafrom);
                	    $found = false;
                	    $size = '';
                	    
                	    foreach ($data as $product) {
                	        for($i=0; $i<count($product->skuDetails); $i++){
                	            if($product->skuDetails[$i]->skuId == $entryDetails->skuId){
                	                $size = $product->skuDetails[$i]->size;
                	                $found = true;
                	                break;
                	            }
                            }
                            
                            if($found){
                                $milliseconds = substr($entryDetails->creationDate,20,3);
                                $launchdate = strtotime($entryDetails->creationDate);
                                $ampm = date("a", $launchdate);
                                $newlaunchdate = date("Y-m-d H:i:s", $launchdate);
                                $newlaunchdate = $newlaunchdate.'.'.$milliseconds.' '.$ampm;
                                $productName = $product->productName;
                                $email = '';
                                $reason = 'Reason : ';
                                
                                if($entryDetails->email != null){
                                    $email = $entryDetails->email;
                                }
                                else{
                                    $stmt2 = $this->database->prepare("SELECT users.email FROM tbl_keys, users WHERE key_value = ? AND users.id = tbl_keys.user_id AND users.status = 'active'");
                                	$stmt2->bind_param('s', $entryDetails->key);
                                	$stmt2->execute();
                                	$result2 = $stmt2->get_result();
                                	
                                	if ($row2 = $result2->fetch_assoc()){
                                        $email = $row2['email'];
                                	}
                                }
                                
                                if($entryDetails->status == 'NON_WINNER'){
                                    $reason = $reason.$entryDetails->reason;
                                }
                                else{
                                    $reason = 'Payment : ';
                                    $reason = $reason.$entryDetails->payment;
                                }
                                
                                $embed = array(
                                    "title" => $productName,
                                    "type" => "rich",
                                    "description" => 'Email : '.$email.PHP_EOL.'Size : '.$size.PHP_EOL.'Submit Time : '.$entryDetails->submitTime.PHP_EOL.'Entry Time : '.$newlaunchdate.PHP_EOL.'Status : '.$entryDetails->status.PHP_EOL.$reason,
                                    "timestamp" => $timestamp,
                                    "color" => hexdec( "3366ff" ),
                                     "footer" => [
                                        "text" => "CubeACO 3.0.2",
                                        "icon_url" => "https://sneakercube.io/images/logo-cube.png"
                                    ],
                                    "thumbnail" => [
                                        "url" => $product->image
                                    ],
                                    "author" => [
                                        "name" => $entryDetails->status,
                                        "url" => ""
                                    ]
                                );
                                
                                array_push($entryResult, $embed);
                                
                                $insert_stmt = $this->database->prepare("INSERT INTO entries (keyValue, email, model, entryDate, createdAt, status, reason) VALUES (?,?,?,?,?,?,?)");
                	            $insert_stmt->bind_param('sssssss', $entryDetails->key, $email, $productName, $entryDetails->submitTime, $newlaunchdate, $entryDetails->status, $reason);
                	            
                	            if($insert_stmt->execute()){
                	                break;
                	            }
                	            else{
                	                break;
                	            }
                            }
            	        }
                	}
		        }
		        
		        if(count($entryResult) > 0){
		            $json_data = json_encode([
                        "content" => "Your entry result for ".$productName,
                        "username" => "CubeACO Bot",
                        "avatar_url" => "https://sneakercube.io/images/logo-cube.png",
                        "tts" => false,
                        "embeds" => $entryResult
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                    
                    $ch = curl_init( $webhookurl );
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                    curl_setopt( $ch, CURLOPT_POST, 1);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt( $ch, CURLOPT_HEADER, 0);
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    
                    $response = curl_exec( $ch );
                    curl_close( $ch );
                    
                    if($response == ""){
                        return array ("status" => "success");
                    }
                    else{
                        return array ("status" => "failed");
                    }
		        }
		        else{
		            return array ("status" => "failed");
		        }
		    }
        }
    }
    
    protected function sendEntry(){
        $response = array();
        
		if($this->method != "POST"){
			$response = array (
                "status" => "failed",
                "message" => "Only accepts POST requests"
            );
        }
        else{
            if(!isset($_POST['skuId'], $_POST['webhookURL'])){
		        $response = array (
                    "status" => "failed",
                    "message" => "Missing Parameters"
                );
		    }
		    else{
		        $skuId = $_POST['skuId']; 
		        $webhookURL = $_POST['webhookURL'];
		        $entries = array();
		        
		        $stmt = $this->database->prepare("SELECT data FROM miscellaneous WHERE id = 2");
            	$stmt->execute();
            	$result = $stmt->get_result();
            	
            	if ($row = $result->fetch_assoc()){
            	    $data = json_decode($row['data']);
            	    $found = false;
            	    $size;
            	    
            	    foreach ($data as $product) {
            	        for($i=0; $i<count($product->skuDetails); $i++){
            	            if($product->skuDetails[$i]->skuId == $skuId){
            	                $size = $product->skuDetails[$i]->size;
            	                $found = true;
            	                break;
            	            }
                        }
                        
                        if($found){
                            $productName = $product->productName;
                            
                            $embed = array(
                                "title" => "Successful Entry!!!",
                                "type" => "rich",
                                "description" => 'Product : '.$productName.PHP_EOL.'Size : '.$size,
                                "timestamp" => $timestamp,
                                "color" => hexdec( "3366ff" ),
                                 "footer" => [
                                    "text" => "CubeACO 3.0.2",
                                    "icon_url" => "https://sneakercube.io/images/logo-cube.png"
                                ],
                                "thumbnail" => [
                                    "url" => $product->image
                                ],
                                "author" => [
                                    "name" => ""
                                ]
                            );
                            
                            array_push($entries, $embed);
                            
                            break;
                        }
            	    }
            	    
            	    $json_data = json_encode([
                        "content" => "You have a successful entry",
                        "username" => "CubeACO",
                        "avatar_url" => "https://sneakercube.io/images/logo-cube.png",
                        "tts" => false,
                        "embeds" => $entries
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                    
                    $ch = curl_init( $webhookURL );
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                    curl_setopt( $ch, CURLOPT_POST, 1);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt( $ch, CURLOPT_HEADER, 0);
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    
                    $response = curl_exec( $ch );
                    curl_close( $ch );
                    
                    if($response == ""){
                        $response = array (
                            "status" => "success",
                            "message" => "Successfully sent to webhook"
                        );
                    }
                    else{
                        $response = array (
                            "status" => "failed",
                            "message" => "Failed to send to webhook"
                        );
                    }
            	}
		    }
        }
        
        return $response;
    }
}
?>