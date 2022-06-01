.. include:: /Includes.rst.txt

.. _develop:

=======
Develop
=======

This chapter is of interest when developing this extension further.

Site
====

-  Create `Build/site/bexio/private.yaml` with bexio related properties

Logging
=======

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['LOG']['Buepro']['Bexio']['writerConfiguration'] = [
       \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
           \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
               'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/bexio.log'
           ],
       ],
       // Configuration for WARNING severity, including all
       // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
       \TYPO3\CMS\Core\Log\LogLevel::WARNING => [
           \TYPO3\CMS\Core\Log\Writer\SyslogWriter::class => [],
       ],
   ];

Composer
========

Use local packages:

.. code-block:: json

   {
       "repositories": [
           {
               "type": "path",
               "url": "/mnt/public/package/bexio-api-client"
           }
       ]
   }

References
==========

Bexio API References
--------------------

-  `Github Bexio API PHP Client <https://github.com/onlime/bexio-api-client>`__


