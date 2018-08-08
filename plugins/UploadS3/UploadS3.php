<?php
/**
 * WebEngine
 *
 * PHP version 7
 *
 * @category  Plugin
 * @package   WebEngine
 * @author    Paolo Savoldi <paooolino@gmail.com>
 * @copyright 2018 Paolo Savoldi
 * @license   https://github.com/paooolino/WebEngine/blob/master/LICENSE 
 *            (Apache License 2.0)
 * @link      https://github.com/paooolino/WebEngine
 */
namespace WebEngine\Plugin;

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
/**
 * Upload class
 *
 * A class grouping useful methods to manage file uploads.
 *
 * @category Plugin
 * @package  WebEngine
 * @author   Paolo Savoldi <paooolino@gmail.com>
 * @license  https://github.com/paooolino/WebEngine/blob/master/LICENSE 
 *           (Apache License 2.0)
 * @link     https://github.com/paooolino/WebEngine
 */
class UploadS3 {
  private $_engine;
  private $_uploadpath;
	private $bucketName;
	private $IAM_KEY;
	private $IAM_SECRET;
  private $s3;
  public $add_date_to_uploadpath;
  
  public function __construct($engine) {
    $this->_engine = $engine;
    $this->_uploadpath = "uploads/";
    $this->add_date_to_uploadpath = true;
    
    $this->bucketName = getenv('S3_BUCKET_NAME');
    $this->IAM_KEY = getenv('S3_IAM_KEY');
    $this->IAM_SECRET = getenv('S3_IAM_SECRET');
    
    $this->s3 = new S3Client([
      'credentials' => [
        'key' => $this->IAM_KEY,
        'secret' => $this->IAM_SECRET
      ],
      'version' => 'latest',
      'http' => [
        'verify' => false
      ],
      'region'  => 'us-east-1'
    ]);
    $this->s3->registerStreamWrapper();
  }
  
  public function setUploadPath($uploadpath) {
    $this->_uploadpath = $uploadpath;
  }
  
  /**
   *  A function to detect if the PHP post max size setting has been reached
   *
   *  In such case, the PHP will not raise any exception but _POST and _FILES
   *  array are empty, having the content length bigger than zero.
   */
  public function detectPostMaxSizeExceeded() {
    $r = $this->_engine->getRequest();
    if (
      $r["SERVER"]["REQUEST_METHOD"] == "POST" && 
      empty($r["POST"]) &&
      empty($r["FILES"]) && 
      $r["SERVER"]["CONTENT_LENGTH"] > 0 
    ) {
      return true;
    }
    return false;
  }
  
  public function get($path) {
    return "http://s3.amazonaws.com/" . $this->bucketName . "/" . $path;
  }

  public function put($path, $image) {
    //var_dump($image->stream()->detach());die();
    $this->s3->put($path, $image->stream()->detach(), 'public');
    die("f");
  }
  
  public function file_exists_in_bucket($uploadpath) {
    return $this->s3->doesObjectExist($this->bucketName, $uploadpath);
  }
  
  /**
   *  Perform the file upload.
   *
   *  The file will be uploaded in the uploads/ directory by default.
   *  In case of success, the returning array will have the following keys:
   *    "result" => "OK"
   *    "filename" => <file path relative to the project web root>
   *  In case of failure, instead:
   *    "result" => "KO"
   *    "errname" => <a string describing the upload error>
   *    "dump" => a dump of the _FILES array
   *
   *  @return array    
   */
  public function upload($filearr) {
    if ($filearr["error"] == 0) {
      $d = $this->add_date_to_uploadpath ? date("d-m-Y") . "/" : "";
      $uploadpath = $this->_uploadpath . $d;
      //if (!$this->file_exists_in_bucket($uploadpath)) {
        //mkdir($uploadpath, 0777, true);
      //}
      $uploadfile = $uploadpath . basename($filearr['name']);
      $file_info = pathinfo($uploadfile);
      
      $n = 1;
      while ($this->file_exists_in_bucket($uploadfile)) {
        $newname = $file_info["filename"] . "_" . $n . "." . $file_info["extension"]; 
        $uploadfile = $uploadpath . $newname;
        $n++;
      }
      
      $uploader = new MultipartUploader($this->s3, $filearr["tmp_name"], [
        'bucket' => $this->bucketName,
        'key'    => $uploadfile,
        'ACL'    => 'public-read'
      ]);
      
      try {
        $result = $uploader->upload();
        return ["result" => "OK", "filename" => $result['Key']];
        //echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
      } catch (MultipartUploadException $e) {
        //echo $e->getMessage() . PHP_EOL;
        return [
          "result" => "KO", 
          "errname" => $e->getMessage(), 
          "dump" => $result
        ];
      }
    } else {
      return [
        "result" => "KO", 
        "errname" => $this->_getUploadErrName($filearr["error"]), 
        "dump" => $filearr
      ];
    }
  }
  
  private function _getUploadErrName($errno)
  {
    $errors = [
      1 => "upload-err-ini-size",
      2 => "upload-err-form-size",
      3 => "upload-err-partial",
      4 => "upload-err-no-file",
      5 => "upload-err-no-tmp-dir",
      6 => "upload-err-cant-write",
      7 => "upload-err-extension"
    ];
    return $errors[$errno];
  }
}

/*
	private $bucketName;
	private $IAM_KEY;
	private $IAM_SECRET;
  
  $this->bucketName = getenv('S3_BUCKET_NAME');
  $this->IAM_KEY = getenv('S3_IAM_KEY');
  $this->IAM_SECRET = getenvt('S3_IAM_SECRET');
    
  public function upload($FILE, $table, $id) {
    if ($FILE["error"] == 0) {
      $uploadpath = "uploads/$table/original/";
      
      $s3 = new S3Client([
        'credentials' => [
          'key' => $this->IAM_KEY,
          'secret' => $this->IAM_SECRET
        ],
        'version' => 'latest',
        'http' => [
          'verify' => false
        ],
        'region'  => 'us-east-1'
      ]);
      
      $uploader = new MultipartUploader($s3, $FILE["tmp_name"], [
        'bucket' => $this->bucketName,
        'key'    => $uploadpath . $FILE["name"]
      ]);

      // Perform the upload.
      try {
        $result = $uploader->upload();
        return ["result" => "OK", "filename" => $result['ObjectURL']];
        //echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
      } catch (MultipartUploadException $e) {
        //echo $e->getMessage() . PHP_EOL;
        return [
          "result" => "KO", 
          "errname" => $e->getMessage(), 
          "dump" => $result
        ];
      }
    } else {
      return [
        "result" => "KO", 
        "errname" => $FILE["error"], 
        "dump" => $FILE
      ];
    }
    
 */