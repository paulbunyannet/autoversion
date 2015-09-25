<?php
namespace Pbc\AutoVersion;

/**
 * Class AutoVersion
 * @package Pbc\AutoVersion
 */
class AutoVersion {

    /**
     * @var string $documentRoot
     */
    protected $documentRoot;

    public function __construct($documentRoot=null)
    {
        $this->setDocumentRoot($documentRoot);
    }

    /**
     * @param $file
     * @param string $method method cache busting, default is regular expression injection of file mod time
     * @return mixed
     */
    public function file($file, $method='regexp')
    {
        if(strpos($file, '/') !== 0 || !file_exists($this->getDocumentRoot() . $file))
            return $file;

        switch($method) {
            case('r'):
            case('regexp'):
                return $this->parseFileRegExp($file);
            case('q'):
            case('query'):
            default:
                return $this->parseFileQuery($file);
        }
    }

    /**
     * @return mixed
     */
    private function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    /**
     * @param mixed $documentRoot
     */
    private function setDocumentRoot($documentRoot)
    {
        $this->documentRoot = $documentRoot;
    }

    /**
     * @param $file
     * @return mixed
     */
    private function parseFileRegExp($file)
    {
        $mtime = $this->version($file);
        return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
    }

    /**
     * @param $file
     * @return string
     */
    private function parseFileQuery($file)
    {
        $mtime = $this->version($file);
        return $file .'?'.$mtime;

    }

    /**
     * @param $file
     * @return int|string
     */
    private function version($file)
    {
        return file_exists($this->getDocumentRoot() . $file) ? filemtime($this->getDocumentRoot() . $file) : '';

    }

} 