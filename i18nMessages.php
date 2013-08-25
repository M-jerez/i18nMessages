<?php 

class i18nMessages{
	
	const pattern = "/p\(.*\)/";
	const copyright = "/** \n* This file is generated with 'i18nMessages.php' \n* Website: http://github.com/M-jerez/ \n* Author : m-jerez \n*/";
	 
	public static $locale = 'es';
	public static $languages = array('en','es');
	public static $languagesDir='lang/';
	public static $oldDir='old/';
	public static $sourceExtension = 'php';
	

	private $oldTranslations;
	private $newTranslations;
	private $messages;
	
	function __construct(){
            $this->init($this->messages);
	}
	
	public static function setlanguage($lang) {
        self::$locale = $lang;
    }	
	public static function setlanguages($languagesArray){
		self::$languages = $languagesArray;
	}
	public static function setlanguagesDir($languagesDir) {
        self::$languagesDir = $languagesDir;
    }	
	public static function setsourceExtension($sourceExtension){
		self::$sourceExtension = $sourceExtension;
	}
	
	private function init(&$array, $parse=true, $subdir=null){
		$languages = self::$languages; 
		$languagesDir = __DIR__."/".self::$languagesDir;
		if($subdir!=null)$languagesDir = $languagesDir.$subdir;
		$array = array();
		$languageFiles = glob("$languagesDir*.php");
		foreach ($languages as $lang) {
			$array[$lang] = array();
		}
		if(!$parse) return;	
		foreach ($languageFiles as  $filename) {
			foreach ($languages as $lang) {
				if($filename=="$languagesDir$lang.php"){
					if (file_exists("$languagesDir$lang.php")){	
						if(is_array($aux = require("$languagesDir$lang.php"))) $array[$lang] = $aux;
					}
    									
				}				
			}
		}		
	}
	
	public function compile(){
		
		$this->init($this->oldTranslations, true, self::$oldDir);
		$this->init($this->newTranslations, false);		
		$this->readNewSources();
		$this->save();		
			
	}
	
	private function readNewSources(){
		$sourceExtension = self::$sourceExtension;
		$sorceFiles = glob(__DIR__. DIRECTORY_SEPARATOR ."*.$sourceExtension");
        $file = __FILE__;
		foreach ($sorceFiles as $filename) {
			if($file==$filename) continue;
			$content = file_get_contents($filename);
			preg_match_all(self::pattern, $content, $match,PREG_OFFSET_CAPTURE);
			foreach ($match[0] as $entry) {
				$quote = "'";
				$args = trim($entry[0], 'p()');
				$enclosure = $args[0] ;
				$args = str_getcsv($args,',',$enclosure) ;
				$message = trim($args[0], "'\"");				
				list($before) = str_split($content, $entry[1]); 
				$lineNum = strlen($before) - strlen(str_replace("\n", "", $before)) + 1;
                $filename = str_replace(__DIR__, '', $filename);
				$this->setNewEntry($message,$lineNum, $filename);
			}
		}
	}
	
	
	
	private function setNewEntry($message,$lineNum, $filename){
		foreach (self::$languages as $lang) {			
				$this->newTranslations[$lang][$message] = "$filename : Line $lineNum";
		}			
	}
	
	
	
	
	private function save(){
		$LangDir = __DIR__."/".self::$languagesDir;
		$LangDirOld = self::$languagesDir.self::$oldDir;
		if (!file_exists($LangDir)) {
    			mkdir($LangDir, 0777, true);
		}
		if (!file_exists($LangDirOld)) {
    			mkdir($LangDirOld, 0777, true);
		}
		
		foreach (self::$languages as $lang) {			
			$filename = "$lang.php";
			
			// NEW FILE CREATION
			$copy = self::copyright;
			$result = "<?php \n$copy \nreturn array( \n\n";
			foreach ($this->newTranslations[$lang] as $key => $value) {
				$aux = isset($this->messages[$lang][$key]) ? $this->messages[$lang][$key]: "";
				If(empty($aux) && isset($this->oldTranslations[$lang][$key])) $aux = $this->oldTranslations[$lang][$key];
				$message = addcslashes($aux, '"') ;
				$linenum = $value;
				$result .= "/* $linenum */\n\"$key\" \n => \n\"$message\" \n, \n\n";
			}
			$result .= ");";
			file_put_contents($LangDir.$filename, trim($result));
			
			//BACKUP OLD VALUES
			/*
			$result = "<?php \n$copy \nreturn array( \n\n";
			$old = array_diff_key($this->messages[$lang], $this->newTranslations[$lang]);
			foreach ($old as $key => $value) {
				if(empty($value)) continue;
				$this->oldTranslations[$lang][$key] = $value;
			}
			ksort($this->oldTranslations[$lang]);
			foreach ($this->oldTranslations[$lang] as $key => $value) {
				$message = addcslashes($value, '"');
				$result .= "\"$key\" \n => \n\"$message\" \n, \n";
			}
			$result .= ");";
			file_put_contents($LangDirOld.$filename, trim($result));	
			 */
		}		
	}	
	
	
	
	
	public function getMessage($message){
		if(isset($this->messages[self::$locale][$message]))
			return (empty($this->messages[self::$locale][$message])) ? $message : $this->messages[self::$locale][$message];
		else {
			return $message;
		}
	}
	
}



function p($string){	
	static $messages;
	if(!isset($messages)) $messages = new i18nMessages();	
	
	if(count(func_get_args())>1){
		$args = func_get_args();
		unset($args[0]);
		vprintf($messages->getMessage($string), $args);
	}else{
            echo $messages->getMessage($string);
        }
			
	
}




 ?>