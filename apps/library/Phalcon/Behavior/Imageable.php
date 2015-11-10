<?php

/**
 * Imageable.php 04/11/2015
 * ----------------------------------------------
 *
 * @author      Phalcon
 * @customize   Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Phalcon\Behavior;

use Phalcon\Http\Request\File;
use Phalcon\Logger;
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\ModelInterface;
use Symfony\Component\Filesystem\Filesystem;

class Imageable extends Behavior implements BehaviorInterface
{
    /**
     * Upload image path
     * @var string
     */
    protected $uploadPath = null;

    /**
     * Model field
     * @var null
     */
    protected $imageField = null;

    /**
     * Old model image
     * @var string
     */
    protected $oldFile = null;

    /**
     * Application logger
     * @var \Phalcon\Logger\Adapter\File
     */
    protected $logger = null;

    /**
     * Filesystem Utils
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem = null;

    /**
     * Allow min size
     * @var int
     */
    protected $allowMinSize = null;

    /**
     * Allow max size
     * @var int
     */
    protected $allowMaxSize = null;

    /**
     * Allowed types
     * @var array
     */
    protected $allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];


    public function notify($eventType, ModelInterface $model)
    {
        if (!is_string($eventType)) {
            throw new Exception('Invalid parameter type.');
        }

        // Check if the developer decided to take action here
        if (!$this->mustTakeAction($eventType)) {
            return;
        }

        $options = $this->getOptions($eventType);

        if (is_array($options)) {
            $this->filesystem = new Filesystem;

            $this->setImageField($options, $model)
                ->setAllowedFormats($options)
                ->setAllowedMinSize($options)
                ->setAllowedMaxSize($options)
                ->setUploadPath($options)
                ->processUpload($model);
        }
    }

    protected function setImageField(array $options, ModelInterface $model)
    {
        if (!isset($options['field']) || !is_string($options['field'])) {
            throw new \Exception("The option 'field' is required and it must be string.");
        }

        $this->imageField = $options['field'];
        $this->oldFile = $model->{$this->imageField};

        return $this;
    }

    protected function setAllowedFormats(array $options)
    {
        if (isset($options['allowedFormats']) && is_array($options['allowedFormats'])) {
            $this->allowedFormats = $options['allowedFormats'];
        }

        return $this;
    }

    protected function setAllowedMinSize(array $options)
    {
        if (isset($options['allowedMinSize']) && is_numeric($options['allowedMinSize'])) {
            $this->allowMinSize = $options['allowedMinSize'];
        }

        return $this;
    }

    protected function setAllowedMaxSize(array $options)
    {
        if (isset($options['allowedMaxSize']) && is_numeric($options['allowedMaxSize'])) {
            $this->allowMaxSize = $options['allowedMaxSize'];
        }

        return $this;
    }

    protected function setUploadPath(array $options)
    {
        if (!isset($options['uploadPath']) || !is_string($options['uploadPath'])) {
            throw new \Exception("The option 'uploadPath' is required and it must be string.");
        }

        $path = ROOT_URL . '/' . $options['uploadPath'] . DIRECTORY_SEPARATOR . $this->curDateDir();

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path);
        }

        $this->uploadPath = $path;

        return $this;
    }

    protected function processUpload(ModelInterface $model)
    {
        $request = $model->getDI()->getRequest();

        if (true == $request->hasFiles(true)) {
            foreach ($request->getUploadedFiles() as $file) {
                $key = $file->getKey();
                $type = $file->getType();

                // Check extension allowed
                if (!in_array($type, $this->allowedFormats)) {
                    throw new \Exception(sprintf('File %s has invalid extension. Allowable only: %s',
                        $file->getName(), str_replace('image/', ' ', implode(',', $this->allowedFormats))));
                }

                // Check allowed min size
                $this->checkMinSize($file, $this->allowMinSize);

                // Check allowed max size
                $this->checkMaxsize($file, $this->allowMaxSize);

                // Check upload directory
                if (is_writable($this->uploadPath) === false) {
                    throw new \Exception(sprintf('The specified directory %s is not writable', $this->uploadPath));
                }

                if ($key != $this->imageField) {
                    continue;
                }

                $uniqueFileName = time() . '-' . uniqid() . '.' . strtolower($file->getExtension());
                $fullPath = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $uniqueFileName;

                if ($file->moveTo($fullPath)) {
                    $model->writeAttribute($this->imageField, $this->curDateDir() . $uniqueFileName);

                    // Delete old file
                    $this->processDelete();
                }
            }
        }

        return $this;
    }

    protected function processDelete()
    {
        if ($this->oldFile) {
            $fullPath = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->oldFile;

            try {
                $this->filesystem->remove($fullPath);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }


    /**
     * Check minimum file size
     *
     * @param File $file
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function checkMinSize(File $file, $value)
    {
        if ($file->getSize() < (int) $value && $value !== null) {
            throw new \Exception(sprintf('The %s file is small. The minimum allowable %s',
                $file->getName(), $this->bytes($value)));
        }
        return true;
    }

    /**
     * Check maximum file size
     *
     * @param File $file
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function checkMaxsize(File $file, $value)
    {
        if ($file->getSize() > (int) $value && $value !== null) {
            throw new \Exception(sprintf('The %s file is big. The maximum allowable %s',
                $file->getName(), $this->bytes($value)));
        }
        return true;
    }

    protected function curDateDir($includeDay = true)
    {
        $dateArr = getdate();

        if ($includeDay) {
            $path = $dateArr['year'] . '/' . $dateArr['month'] . '/' . $dateArr['mday'] . '/';
        } else {
            $path = $dateArr['year'] . '/' . $dateArr['month'] . '/';
        }

        return $path;
    }

    /**
     * Format byte code to human understand
     *
     * @param int $bytes number of bytes
     * @param int $precision after comma numbers
     * @return string
     */
    public function bytes($bytes, $precision = 2)
    {
        $size = array('bytes', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

}
