<?php
/**
 *
 * @abstract This component is define to Perform Some common task used in Eton Application
 * @Package components
 * @category Component
 * @author Logicspice(info@logicspice.com)
 * @since 1.0.0 02-Jul-2011
 * @copyright Copyright & Copy ; 2011, Logicspice Consultancy Pvt. Ltd., Jaipur
 */
class CommonComponent extends Component{

/**
 *
 *  @abstract This function is written to check a Unique Record in Model
 *  @access Public
 *  @param string $conditions,$model_class
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
	function isRecordUnique($conditions=array(),$model_class){

		App::import("Model",$model_class);
		$modelObject = new $model_class();
		$result=$modelObject->find('count',array('conditions'=>$conditions));
		if($result){
			return false;
		}else{
			return true;
		}
	}

/**
 *
 *  @abstract This function is written for email validation
 *  @access Public
 *  @param string $email
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
	function check_email_address($email) {

		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email)){
			return false;
		}else{
			return true;
		}
	}

/**
 *
 *  @abstract This function is written to convert a Title
 *  @access Public
 *  @param string $string
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
	function convertTitle($string=null){
        $specialCharacters = array('#','$','%','@','.','+','=','\\','/',' ',"'",':','~','`','!','^','*','(',')','|');
        $toReplace="-";
    	$string=str_replace($specialCharacters,$toReplace,$string);
        $replace=str_replace("&","and",$string);
        return strtolower($replace);
    }

/**
 *
 *  @abstract This function is written to send a mail
 *  @access Public
 *  @param string $to,$subject,$message
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
    function sendEmail($to, $subject, $message)
   {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: IQ Certified IQ Score<admin@iqtesting.com>\r\n";

		mail($to,$subject, $message, $headers);
   }

/**
 *
 *  @abstract This function is written for file upload feature
 *  @access Public
 *  @param string $fileArray,$folder,$types
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
   function upload($fileArray, $folder="", $types="") {

   	if(!$fileArray['name']) return array('','No file specified');
   	$file_title = $fileArray['name'];
   	//Get file extension
   	$ext_arr = split("\.",basename($file_title));
   	$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
   	//Not really uniqe - but for all practical reasons, it is
   	$uniqer = substr(md5(uniqid(rand(),1)),0,5);
   	$file_name = $uniqer . '_' . $file_title;//Get Unique Name
   	$all_types = explode(",",strtolower($types));
   	if($types) {
   		if(in_array($ext,$all_types));
   		else {
   			$result = "'".$fileArray['name']."' is not a valid file."; //Show error if any.
   			return array('',$result);
   		}
   	}
   	//Where the file must be uploaded to
   	if($folder) $folder .= '/';//Add a '/' at the end of the folder
   	$uploadfile = $folder . $file_name;
   	$result = '';
   	//Move the file from the stored location to the new location
   	if (!move_uploaded_file($fileArray['tmp_name'], $uploadfile)) {
   		$result = "Cannot upload the file '".$fileArray['name']."'"; //Show error if any.
   		if(!file_exists($folder)) {
   			echo $folder;
   			$result .= " : Folder don't exist.";
   		} elseif(!is_writable($folder)) {
   			$result .= " : Folder not writable.";
   		}
   		elseif(!is_writable($uploadfile)) {
   			$result .= " : File not writable.";
   		}
   		$file_name = '';

   	} else {
   		if(!$fileArray['size']) { //Check if the file is made
   			@unlink($uploadfile);//Delete the Empty file
   			$file_name = '';
   			$result = "Empty file found - please use a valid file."; //Show the error message
   		} else {
   			chmod($uploadfile,0777);//Make it universally writable.
   		}
   	}
   	return array($file_name,$result);
   }

/**
 *
 *  @abstract This function is written to generate a Output link
 *  @access Public
 *  @param string $file,$mime_type,$name
 *  @since 1.0.0 02-Jul-2011
 *  @author logicspice(info@logicspice.com)
 */
 public function output_file($file, $name, $mime_type=''){
   	if(!is_readable($file)) die('File not found or inaccessible!');
   	$size = filesize($file);
   	$name = rawurldecode($name);
   	/* Figure out the MIME type (if not specified) */
   	$known_mime_types=array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg"=> "image/jpg",
            "jpg" =>  "image/jpg",
            "csv" =>  "application/excel",
            "php" => "text/plain"
            );
            
            if($mime_type==''){
             $file_extension = strtolower(substr(strrchr($file,"."),1));
             if(array_key_exists($file_extension, $known_mime_types)){
             	$mime_type=$known_mime_types[$file_extension];
             } else {
             	$mime_type="application/force-download";
             };
            };

            @ob_end_clean(); //turn off output buffering to decrease cpu usage

            // required for IE, otherwise Content-Disposition may be ignored
            if(ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header("Content-Transfer-Encoding: binary");
            header('Accept-Ranges: bytes');
            /* The three lines below basically make the
             download non-cacheable */
            header("Cache-control: private");
            header('Pragma: private');
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

            // multipart-download and download resuming support
            if(isset($_SERVER['HTTP_RANGE']))
            {
            	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
            	list($range) = explode(",",$range,2);
            	list($range, $range_end) = explode("-", $range);
            	$range=intval($range);
            	if(!$range_end) {
            		$range_end=$size-1;
            	} else {
            		$range_end=intval($range_end);
            	}

            	$new_length = $range_end-$range+1;
            	header("HTTP/1.1 206 Partial Content");
            	header("Content-Length: $new_length");
            	header("Content-Range: bytes $range-$range_end/$size");
            } else {
            	$new_length=$size;
            	header("Content-Length: ".$size);
            }

            /* output the file itself */
            $chunksize = 1*(1024*1024); //you may want to change this
            $bytes_send = 0;
            if ($file = fopen($file,'r'))
            {
            	if(isset($_SERVER['HTTP_RANGE']))
            	fseek($file, $range);

            	while(!feof($file) &&
            	(!connection_aborted()) &&
            	($bytes_send<$new_length)
            	)
            	{
            		$buffer = fread($file, $chunksize);
            		print($buffer); //echo($buffer); // is also possible
            		flush();
            		$bytes_send += strlen($buffer);
            	}
            	fclose($file);
            } else die('Error - can not open file.');

            die();
   }

} //end of component Class
?>