.. include:: /Includes.rst.txt

.. _develop:

=======
Develop
=======

This chapter is of interest when developing this extension further.

Site
====

-  Create `Build/site/bexio/private.yaml` with bexio related properties
-  Create the key files in `Build/site/bexio`

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


Various
=======

-  Expose dev server: `ddev share`

References
==========

Bexio API References
--------------------


PHP References
--------------


