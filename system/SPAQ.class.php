<?php

/**
 * The SPAQ class. It's for reading (and writing) .spaq files.
 */
class SPAQ {
    /**
     * True = autolock on __destruct(). False = do nothing
     * @var boolean
     */
    private $autolock = false;
    /**
     * Password for locking the sPaq
     * @var mixed
     */
    private $lock_pass = '';
    
    /**
     * Path to the original loaded file
     * @var string
     */
    private $original_file_path;
    /**
     * ZipArchive class for current .sPaq file
     * @var ZipArchive
     */
    private $zip;
    /**
     * Path to the current tempdir
     * @var string
     */
    private $tmpdir;
    
    /**
     * Parsed about.ini file
     * @var array
     */
    private $current_file_aboutIni = array();
    
    /**
     * Constructor for SPAQ class
     * @return void
     */
    function __construct() {
        $this->zip    = new ZipArchive;
        $this->tmpdir = dirname(__FILE__) . '/tmp/' . md5(time()) . mt_rand();
        mkdir($this->tmpdir);
    }
    /**
     * Load a sPaq file.
     * @param string $file Path to the sPaq file.
     * @param boolean $new True  = create new sPaq.<br/>
     *                     False = open sPaq file only.
     * @return boolean
     */
    function load($file, $new = false) {
        $this->original_file_path = $file;
        if ($new) {
            copy($file, $this->tmpdir . '/spaqfile.spaq');
            if ($this->prepare_new($this->tmpdir . '/spaqfile.spaq')) {
                //a new file is prepared! now load the file
                //$this->load($file);
                return true;
            } else {
                return false;
            }
        } else {
            copy($file, $this->tmpdir . '/spaqfile.spaq');
            if ($this->zip->open($this->tmpdir . '/spaqfile.spaq')) {
                $this->zip->extractTo($this->tmpdir . '/extract');
                if (file_exists($this->tmpdir . '/extract/about.ini')) {
                    $this->current_file_aboutIni = parse_ini_file($this->tmpdir . '/extract/about.ini', true);
                }
            } else {
                return false;
            }
        }
    }
    /**
     * Create a new, or overwrite an existing .sPaq file.<br/>
     * De default files are put in a new .sPaq file. This files are here located:
     * ./SPAQ/
     * @param string $file Path to .sPaq file
     * @return void
     */
    private function prepare_new($file) {
        $this->zip->open($file, ZipArchive::CREATE);
        $this->zip->addFile(dirname(__FILE__) . '/SPAQ/base.ini', 'about.ini');
        $this->zip->addEmptyDir('databunk');
        $this->zip->addEmptyDir('plugin');
        $this->current_file_aboutIni = parse_ini_file(dirname(__FILE__) . '/SPAQ/base.ini', true);
        $this->zip->setArchiveComment('This is a sPaq file.');
    }
    /**
     * Destructor for SPAQ class. This function:<br/>
     * - Writes <code>$this->current_file_aboutIni</code> to <code>about.ini</code><br/>
     * - Closes the ZipArchive class<br/>
     * - Copys the edited .sPaq file to the original location<br/>
     * - Removes the temp dir
     * @return void
     */
    function __destruct() {
        if ($this->autolock && is_string($this->lock_pass) && !empty($this->lock_pass)) {
            $this->zip->setArchiveComment($this->lock_pass);
            $this->set_about('permissions', 'able to edit', false);
        }
        
        $aboutIni = $this->arr2ini($this->current_file_aboutIni);
        
        $this->zip->addFromString('about.ini', $aboutIni);
        
        $this->zip->close();
        copy($this->tmpdir . '/spaqfile.spaq', $this->original_file_path);
        $this->rrmdir($this->tmpdir);
    }
    /**
     * Get a
     * @param string $section
     * @param mixed $key
     * @return boolean
     */
    function get_about($section, $key) {
        if (!empty($section) and !empty($key)) {
            return $this->current_file_aboutIni[$section][$key];
        } else {
            return false;
        }
    }
    /**
     * Get all information of about.ini
     * @param string $section Specify a section if you want to get only one section
     * @return array
     */
    function get_about_all($section = '') {
        if (!empty($section)) {
            return $this->current_file_aboutIni[$section];
        } else {
            return $this->current_file_aboutIni;
        }
    }
    /**
     * Edit the about.ini file. ONLY if the .sPaq file is NOT write-protected.
     * @param mixed $section Section of the about.ini file to set
     * @param mixed $key Name of the key to set
     * @param string $value Value to set to the $key
     * @return boolean
     */
    private function set_about($section = '', $key = '', $value = '') {
        if ($this->get_about('permissions', 'able to edit')) {
            $this->current_file_aboutIni[$section][$key] = $value;
            return true;
        } else {
            return false;
        }
    }
    /**
     * Remove a not-empty dir (like <code>rmdir -rf &lt;dir&gt;</code>)
     * @param string $dir
     * $return void
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    /**
     * Inversed function of <code>parse_ini_file()</code>
     * @param array $a
     * @param array $parent
     * @return string
     */
    function arr2ini($a, $parent = array()) {
        $out = '';
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                //subsection case
                //merge all the sections into one array...
                $sec = array_merge((array) $parent, (array) $k);
                //add section information to the output
                $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
                //recursively traverse deeper
                $out .= $this->arr2ini($v, $sec);
            } else {
                //plain key->value case
                $out .= "$k=$v" . PHP_EOL;
            }
        }
        return $out;
    }
    /**
     * Delete a section or only a key from the about.ini.
     * If the sPaq is write-protected, it returns <code>false</code>
     * @param string $section
     * @param string $key
     * @return boolean
     */
    function del_about($section = '', $key = '') {
        if (!$this->get_about('permissions', 'able to edit'))
            return false;
        if (!empty($section) and empty($key)) {
            unset($this->current_file_aboutIni[$section]);
            return true;
        } elseif (!empty($section) and !empty($key)) {
            unset($this->current_file_aboutIni[$section][$key]);
            return true;
        } else {
            return false;
        }
    }
    /**
     * Set the type of this sPaq if the sPaq file isn't locked
     * @param string $type
     * @return boolean
     */
    function set_type($type) {
        if (!$this->get_about('permissions', 'able to edit'))
            return false;
        $types = array(
            'plugin',
            'databunk'
        );
        if (in_array($type, $types)) {
            $this->current_file_aboutIni['about']['type'] = $type;
            return true;
        } else {
            return false;
        }
    }
    /**
     * Get the type of this sPaq file. It can be:<br/>
     * - <b>databunk</b> - the folder databunk/ 
     * - <b>plugin</b> - 
     * @return string
     */
    function get_type() {
        return $this->get_about('about', 'type');
    }
    /** 
     * Add files and sub-directories in a folder to zip file. 
     * @param string $folder 
     * @param ZipArchive $zipFile 
     * @param int $exclusiveLength Number of text to be exclusived from the file path. 
     * @return void
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
        $handle = opendir($folder);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath  = "$folder/$f";
                // Remove prefix from file path before add to zip. 
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory. 
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }
    
    /** 
     * Zip a folder (include itself). 
     * Usage: 
     *   $this->zipDir('/path/to/sourceDir', '/path/to/out.zip'); 
     * 
     * @param string $sourcePath Path of directory to be zip. 
     * @param string $outZipPath Path of output zip file. 
     */
    private static function zipDir($sourcePath, $outZipPath) {
        $pathInfo   = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName    = $pathInfo['basename'];
        $z          = new ZipArchive();
        $z->open($outZipPath, ZIPARCHIVE::CREATE);
        $z->addEmptyDir($dirName);
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $z->close();
    }
    /**
     * Invoke SPAQ. This will return a zipped folder.
     * If the sPaq file is a 'plugin', there is a folder called 'plugin' inside
     * the .zip folder. This is same if the sPaq is a 'databunk'
     * @return string The path to the file wich is generated
     * @return boolean Only if no sPaq type is specified. You can set it with <code>->set_type(string $type)</code>
     */
    function __invoke() {
        switch ($this->get_type()) {
            case 'plugin':
                self::zipDir($this->tmpdir . '/extract/plugin', $this->tmpdir . '/plugin.zip');
                return $this->tmpdir . '/plugin.zip';
                break;
            case 'databunk':
                self::zipDir($this->tmpdir . '/extract/databunk', $this->tmpdir . '/databunk.zip');
                return $this->tmpdir . '/databunk.zip';
                break;
            default:
                return false;
                break;
        }
    }
    /**
     * Lock the current sPaq file. But, the locking
     * @param mixed $pass Password for writing
     * @param boolean $autolock Lock the sPaq automatically on __destruct
     * @return boolean
     */
    function lock($pass = '') {
        if (empty($pass)) {
            return false;
        }
        $this->set_about('permissions', 'able to edit', false);
        $this->zip->setArchiveComment(md5($pass));
        return true;
    }
    /**
     * Unlock 
     * @param mixed $pass Password for unlocking
     * @param boolean $autolock Lock the sPaq automatically on __destruct
     * @return boolean
     */
    function unlock($pass = '', $autolock=false) {
        if (empty($pass)) {
            return false;
        }
        if (md5($pass) == $this->zip->getArchiveComment()) {
            $this->current_file_aboutIni['permissions']['able to edit'] = true;
            return true;
        } else {
            return false;
        }
        $this->lock_pass =  md5($pass);
        $this->autolock  =  (boolean)$autolock;
    }
    function set_version($verson=0) {
        if ($version != 0 and $this->get_about('permissions', 'able to edit')) {
            
        } else {
            return false;
        }
    }
}