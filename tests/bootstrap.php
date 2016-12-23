<?php

error_reporting(E_ALL | E_STRICT);

set_include_path(dirname(__DIR__) . '/library' . PATH_SEPARATOR . __DIR__ . PATH_SEPARATOR . get_include_path());

require __DIR__ . '/../vendor/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation', __DIR__ . '/../vendor/jms/serializer/src');
