<?php



class i18nMessages {
    
   

    const pattern = "/\sp\(.*\)/";
    const copyright = "/** \n* This file is generated with 'i18nMessages.php' \n* Website: http://github.com/M-jerez/ \n* Author : m-jerez \n*/";
    const languagesDir = '/lang/';
    const oldDir = '/lang/old/';
    const phpExtension = '.php';
    
    public static $locale = 'es';
    public static $languages = array('en', 'es');

    private $oldTranslations;
    private $newTranslations;
    private $messages;

    function __construct() {
        $languagesDir = __DIR__ . self::languagesDir;
        $this->messages = self::initLangArray($languagesDir);
    }

    /**
     * Sets the default language 'Locale' to work with. Messages are translated tho this
     * language whe the p() function is called. 
     * @param type $lang
     */
    public static function setWorkingLanguage($lang) {
        self::$locale = $lang;
    }

    /**
     * Sets the range of available languages by the system.
     * @param type $languagesArray
     */
    public static function setlanguages($languagesArray) {
        self::$languages = $languagesArray;
    }
    
    /** 
     * Creates and initilize a language array with the existing languages files.
     * This function makes use of require() to load lang files, so it throws a fatal error
     * if the lang file doesn't exist. you must use of the compile() function of this class
     * to create an empty language file and avoid this error.
     * 
     * @param type $directory the directory withing the languages files
     */
    private static function initLangArray($directory) {
        $langsArray = self::createLangArray();
        $ext = self::phpExtension;     
        $phpFiles = glob("$directory*$ext");
        
        foreach ($phpFiles as $filename) {
            foreach ($langsArray as $lang) {
                $langFilename = $languagesDir . $lang . $ext;
                if ($filename == $langFilename) {
                    $aux = require($langFilename);
                    if (is_array(aux))
                        $array[$lang] = $aux;
                }
            }
        }        
        return $langsArray;
    }
    
    
    /** Creates an empty Languages array with the next structure.
     * array(n) {
     *  ['en']=> array(0){}
     *  ['es']=> array(0){}
     *  ..........
     *  ['lang_N']=> array(0){}
     * } 
     * @return array  the new empty Languages Array.
     */
    private static function createLangArray(){
        $langArray = array();
        $languages = self::$languages;
        foreach ($languages as $lang) {
            $langArray[$lang] = array();
        }
        return $langArray;
    }
    
    /**
     * Scans all php files searching for calls to the p() function and creates 
     * languages files from the calls found.
     * 
     * @param type $rootDir
     */
    public function compile($rootDir) {
        $languagesDir = __DIR__ . self::languagesDir;
        $oldDir = __DIR__ . self::oldDir;
        $this->newTranslations = self::initLangArray($languagesDir);        
        $this->oldTranslations = self::initLangArray($oldDir);
        $this->readSources();
        $this->save();
    }    
    
    /**
     * Scans all php files and creates a Language Array from all p() function calls
     * found.
     * To do so, this function uses file_get_contents($filename) that transform php source files into tokens.
     * @link http://php.net/manual/en/function.token-get-all.php
     */
    private function readSources() {
        $ext = self::phpExtension;
        $sorceFiles = glob(__DIR__ . DIRECTORY_SEPARATOR . "*$ext");
 
        foreach ($sorceFiles as $filename) {
            if (__FILE__ == $filename)
                continue;
            $content = file_get_contents($filename);
            $tokens = token_get_all($content);
            self::dump_tokens($tokens);
            for ($index = 0; $index < count($tokens); $index++) {
                $token = &$tokens[$index];
                if(is_array($token) && $token[0] === T_STRING && $token[1] === p){
                    // this is the token of the 'p' function.
                    $index = $this->scanMessage($tokens, $index, $filename, $token[2]);
                } 
            }
        }
    }
    
    /**
     * function used for debug only
     */
    private static function dump_tokens($tokens){
        foreach ($tokens as &$value) {
            if(is_array($value))
              $value[0] = token_name ($value[0]); 
        }
        var_dump($tokens);
    }
    
    /**
     * Search for the first argument of the p() function, wich corresponds to the 
     * message to be translated and set a new entry in the languages Array
     * This funcion Retrieves a tokenized array and an index where the p() function starts
     * in the given tokenized array.  
     * 
     * @param type $tokens the tokenized array 
     * @param type $index the index where starts the p() function call in the $tokens array
     */
    private function scanMessage(&$tokens, $index, $filename, $lineNum){    
        $shortFilename = str_replace(__DIR__, '', $filename);
        do{
            $index++;
        }while($tokens[$index]=="(" || $tokens[$index][0]==T_WHITESPACE);
        
        if($tokens[$index][0]==T_CONSTANT_ENCAPSED_STRING){
            $message = $tokens[$index][1];
            $this->setNewEntry($message, $lineNum, $shortFilename);
        }else{
            trigger_error ("php variables are not alowed in messages to 
                translate in $filename : line $lineNum", E_ERROR) ;
        }                 
        return $index;
    }
    
    /**
     * Sets a new entry in each language in the $newTranslations Languages array.
     * @param type $message the message to be translated 'the key in the lang array'.
     * @param type $lineNum the line number where it appears in source code.
     * @param type $filename the file name where it appears.
     */
    private function setNewEntry($message, $lineNum, $filename) {
        foreach (self::$languages as $lang) {
            $this->newTranslations[$lang][$message] = "$filename : Line $lineNum";
        }
    }

    
    /**
     * Saves the Languages Arrays into a languages files.
     * A language file is the array written in php code.
     * One file is generated by each language in the language array.
     * A copy of old messages is stored in the /old directory.
     */
    private function save() {
        $LangDir = __DIR__ . "/" . self::languagesDir;
        $LangDirOld = self::languagesDir . self::oldDir;
        if (!file_exists($LangDir)) {
            mkdir($LangDir, 0777, true);
        }
        if (!file_exists($LangDirOld)) {
            mkdir($LangDirOld, 0777, true);
        }

        foreach (self::$languages as $lang) {
            $ext = self::phpExtension;
            $filename = "$lang$ext";
            // NEW FILE CREATION
            $copy = self::copyright;
            $result = "<?php \n$copy \nreturn array( \n\n";
            foreach ($this->newTranslations[$lang] as $key => $value) {          
                $message = $this->messages[$lang][$key] ?: "";
                If (empty($message) && isset($this->oldTranslations[$lang][$key]))
                    $message = $this->oldTranslations[$lang][$key];                
                $linenum = $value;
                $delimiter = $key[0];
                $result .= "/* $linenum */\n$key \n => \n$delimiter$message$delimiter \n, \n\n";
            }
            $result .= ");";
            file_put_contents($LangDir . $filename, trim($result));

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

    /**
     * Returns the translated message or the message itself if there is no translation for the message. 
     * @param type $message the message to tranlate
     * @return type the translated message.
     */
    public function getMessage($message) {
        if (isset($this->messages[self::$locale][$message]))
            return (empty($this->messages[self::$locale][$message])) ? $message : $this->messages[self::$locale][$message];
        else {
            return $message;
        }
    }

}

/**
 * Prints the translated message or the message itself if there is no translation for the message. 
 * This function can be used both as print() or printf().
 * p("hello world") or p("%s %s", 'hello', 'world').
 * @staticvar null $messages
 * @param type $string the string to tranlates
 */
function p($string){
    static $messages = null;
    if ($messages==null)
        $messages = new i18nMessages();
    if (count(func_get_args()) > 1) {
        $args = func_get_args();
        unset($args[0]);
        vprintf($messages->getMessage($string), $args);
    } else {
        echo $messages->getMessage($string);
    }
}