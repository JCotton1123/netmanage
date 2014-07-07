<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class TftpFile extends AppModel {

   public $name = 'TftpFile';
   public $useTable = false;
 
   public static $tftp_directory = '/home/tftp';

   public function find($filter=false){

	$filter = ($filter === false ? ".*" : $filter);
	$tftp_dir = new Folder(self::$tftp_directory);
	$files = $tftp_dir->find($filter);

	foreach($files as $index => $filename){
		$file = new File(self::$tftp_directory . DS . $filename);
		$files[$index] = array(
			'filename' => $filename,
			'size' => $file->size(),
			'perms' => $file->perms(),
			'mod_date' => date('Y-m-d H:i:s',$file->lastChange())
		);
		$file->close();
	}

	return $files;
   }

   public function read($file){

	$file = new File(self::$tftp_directory . DS . $file);
	$contents = $file->read();
	$file->close();

	return $contents;
   }

   public function create($file,$content){
	$file = new File(self::$tftp_directory . DS . $file);
	$file->write($content);
	$file->close();
   }

}
