<?php

/**
 * AnnotationsInitializer.php 14/10/2015
 * ----------------------------------------------
 *
 * @author      Phalcon
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Annotations;

use Phalcon\Events\Event,
    Phalcon\Mvc\Model\Manager as ModelsManager;

class AnnotationsInitializer extends \Phalcon\Mvc\User\Plugin
{
    /**
     * This is called after initialize the model
     *
     * @param Event $event
     * @param ModelsManager $manager
     * @param $model
     */
    public function afterInitialize(Event $event, ModelsManager $manager, $model)
    {

        //Reflector
        $reflector = $this->annotations->get($model);

        /**
         * Read the annotations in the class' docblock
         */
        $annotations = $reflector->getClassAnnotations();
        if ($annotations) {

            /**
             * Traverse the annotations
             */
            foreach ($annotations as $annotation) {
                switch ($annotation->getName()) {

                    /**
                     * Initializes the model's source
                     */
                    case 'Source':
                        $arguments = $annotation->getArguments();
                        $manager->setModelSource($model, $arguments[0]);
                        break;

                    /**
                     * Initializes Has-Many relations
                     */
                    case 'HasMany':
                        $arguments = $annotation->getArguments();
                        $manager->addHasMany($model, $arguments[0], $arguments[1], $arguments[2]);
                        break;

                    /**
                     * Initializes Has-Many relations
                     */
                    case 'BelongsTo':
                        $arguments = $annotation->getArguments();
                        if (isset($arguments[3])) {
                            $manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                        } else {
                            $manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2]);
                        }
                        break;
                }
            }
        }
    }
}
