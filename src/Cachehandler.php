<?php

namespace Ftembe\Cachehandler;

class Cachehandler
{

    private static  $duration = '60 minutes';
    private  string $folder;

    /**
     * __construct
     *
     * @param  mixed $folder
     * @return void
     */
    public function __construct($folder = null)
    {
        $this->setFolder($folder ?? sys_get_temp_dir());
    }

    /**
     * setFolder
     *
     * @param  mixed $folder
     * @return void
     */
    protected function setFolder($folderName): void
    {

        if (file_exists($folderName) && is_dir($folderName) && is_writable($folderName))
            $this->folder = $folderName;
        else
            trigger_error('Unable to access cache folder', E_USER_NOTICE);
    }
    /**
     * generateFile
     *
     * @param  mixed $fileName
     * @return string
     */
    protected function generateFile($fileName): string
    {
        $fileName = trim($fileName);
        return $this->folder . DIRECTORY_SEPARATOR . sha1($fileName) . '.tmp';
    }

    /**
     * createCacheFile
     *
     * @param  mixed $fileName
     * @param  mixed $content
     * @return bool
     */
    protected function createCacheFile($fileName, $content): bool
    {
        $fileName = $this->generateFile($fileName);
        return file_put_contents($fileName, $content) ?? false;
    }
    /**
     * store
     *
     * @param  mixed $fileName
     * @param  mixed $content
     * @param  mixed $duration
     * @return bool
     */
    public function store($fileName, $content, $duration = null): bool
    {

        $duration = strtotime($duration ?? self::$duration);

        $content = serialize(array(
            'expires' => $duration,
            'content' => $content
        ));

        return $this->createCacheFile($fileName, $content);
    }

    /**
     * get
     *
     * @param  mixed $fileName
     */
    public function get($fileName)
    {
        $fileName = $this->generateFile($fileName);
        if (file_exists($fileName) && is_readable($fileName)) {
            $cache = unserialize(file_get_contents($fileName));
            if ($cache['expires'] > time()) {
                return $cache['content'];
            } else {
                unlink($fileName);
            }
        }
        return null;
    }

    /**
     * getDataLength
     *
     * @param  mixed $fileName
     * @return int
     */
    public function getDataLength($fileName): int
    {

        $data = $this->get($fileName);

        if (is_string($data))
            return strlen($data);

        if (is_array($data))
            return count($data);

        if (is_object($data))
            return count(get_object_vars($data));

        return 0;
    }
    /**
     * getDataType
     *
     * @param  mixed $fileName
     * @return string
     */
    public function getDataType($fileName): string
    {
        return gettype($this->get($fileName));
    }
}
