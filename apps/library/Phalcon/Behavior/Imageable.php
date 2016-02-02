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

use Library\ImageResize;
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
     * Current date dir path
     * @var null
     */
    protected $datePath = null;

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
            throw new \Exception('Invalid parameter type.');
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
                ->processUpload($options['media'], $model);
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
        if (isset($options['media']->allowedMinSize) && is_numeric($options['media']->allowedMinSize)) {
            $this->allowMinSize = $options['media']->allowedMinSize;
        }

        return $this;
    }

    protected function setAllowedMaxSize(array $options)
    {
        if (isset($options['media']->allowedMaxSize) && is_numeric($options['media']->allowedMaxSize)) {
            $this->allowMaxSize = $options['media']->allowedMaxSize;
        }

        return $this;
    }

    protected function setUploadPath(array $options)
    {
        if (!isset($options['media']->imagePath) || !is_string($options['media']->imagePath)) {
            throw new \Exception("The option 'uploadPath' is required and it must be string.");
        }

        $this->uploadPath = ROOT_URL . '/' . $options['media']->imagePath;
        $this->datePath = $this->curDateDir();

        $path = $this->uploadPath . DIRECTORY_SEPARATOR . $this->datePath;

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path);
        }

        return $this;
    }

    protected function processUpload($media, ModelInterface $model)
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

                // Create full path image
                $fullPath = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath;

                // Check upload directory
                if (is_writable($fullPath) === false) {
                    throw new \Exception(sprintf('The specified directory %s is not writable', $fullPath));
                }

                if ($key != $this->imageField) {
                    continue;
                }

                $uniqueFileName = md5($file->getName()) . '-' . uniqid() . '.' . strtolower($file->getExtension());
                $fullPath .= $uniqueFileName;

                if ($file->moveTo($fullPath)) {
                    $model->writeAttribute($this->imageField, $this->datePath . $uniqueFileName);

                    // Resize images big
                    $myImageResize = new ImageResize(
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $uniqueFileName,
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $uniqueFileName,
                        $media->imageMaxWidth,
                        $media->imageMaxHeight,
                        '',
                        $media->imageQuality
                    );
                    $myImageResize->output();
                    unset($myImageResize);

                    // Resize images medium
                    $nameMediumPart = substr($uniqueFileName, 0, strrpos($uniqueFileName, '.'));
                    $nameMedium = $nameMediumPart . '-medium.' . strtolower($file->getExtension());
                    $myImageResize = new ImageResize(
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $uniqueFileName,
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $nameMedium,
                        $media->imageMediumWidth,
                        $media->imageMediumHeight,
                        '',
                        $media->imageQuality
                    );
                    $myImageResize->output();
                    unset($myImageResize);

                    // Resize images small
                    $nameThumbPart = substr($uniqueFileName, 0, strrpos($uniqueFileName, '.'));
                    $nameThumb = $nameThumbPart . '-small.' . strtolower($file->getExtension());
                    $myImageResize = new ImageResize(
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $uniqueFileName,
                        rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->datePath,
                        $nameThumb,
                        $media->imageThumbWidth,
                        $media->imageThumbHeight,
                        '',
                        $media->imageQuality
                    );
                    $myImageResize->output();
                    unset($myImageResize);

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
            // part url image
            $pos = strrpos($this->oldFile, '.');
            $extension = substr($this->oldFile, $pos + 1);
            $name = substr($this->oldFile, 0, $pos);

            $image = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $this->oldFile;
            $imageMedium = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $name . '-medium.' . $extension;
            $imageSmall = rtrim($this->uploadPath, '/\\') . DIRECTORY_SEPARATOR . $name . '-small.' . $extension;

            try {
                $this->filesystem->remove([$image, $imageMedium, $imageSmall]);
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
