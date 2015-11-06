<?php

/**
 * Imageable.php 04/11/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Phalcon\Behavior;

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
                // NOTE!!!
                // Nothing was validated here!
                // Any validations must be are made in a appropriate validator
                $key = $file->getKey();
                $type = $file->getType();
                if ($key != $this->imageField || !in_array($type, $this->allowedFormats)) {
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
}
