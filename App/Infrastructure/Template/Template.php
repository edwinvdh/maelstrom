<?php

namespace App\Infrastructure\Template;

use Bootstrap;

/**
 * Loading HTML files that I can render using PHP classes.
 */
class Template
{
    private string $templateDir;

    private array $dataSet;

    const DEFAULT_EXTENSION = '.html';

    public function __construct(string $templateDir)
    {
        // I am not happy with this...
        $bootstrapDir = Bootstrap::getRootDir();
        $this->templateDir = $bootstrapDir . $templateDir .  DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $key
     * @param $dataSet
     */
    public function set(string $key, $dataSet)
    {
        $this->dataSet[$key] = $dataSet;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if (!isset($this->dataSet[$key])) {
            return false;   // will not output anything
        }

        return $this->dataSet[$key];
    }

    public function render(string $templateFile)
    {
        $fileToLoad = $this->templateDir . $templateFile;
        $fileToLoad = $this->setDefaultExtension($fileToLoad);

        if (!$this->isFile($fileToLoad)) {
            return false;
        }

        $this->getOutput($fileToLoad);
    }

    public function include($file)
    {
        $file = $this->setDefaultExtension($file);

        /** @noinspection PhpIncludeInspection */
        require_once ($this->templateDir . $file);
    }

    private function getOutput(string $outputFile)
    {
        ob_start();

        /** @noinspection PhpIncludeInspection */
        require_once ($outputFile);

        $output = ob_get_clean();
        print $output;
    }

    /**
     * @param string $fileToLoad
     * @return string
     */
    private function setDefaultExtension(string $fileToLoad): string
    {
        $fileParts = pathinfo($fileToLoad);
        if (!isset($fileParts['extension'])) {
            $fileToLoad .= self::DEFAULT_EXTENSION;
        }
        return $fileToLoad;
    }
    /**
     * @param string $fileToLoad
     * @return bool
     */
    private function isFile(string $fileToLoad): bool
    {
        if (!file_exists($fileToLoad)) {
            return false;
        }

        /**
         * Todo: might need some additional checks here.
         */
        return true;
    }
}