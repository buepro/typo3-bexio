.. include:: /Includes.rst.txt

.. _api:

===
API
===

Tasks
=====

.. code-block:: php
   :caption: Update frontend users

   // use Buepro\Bexio\Task\UpdateUsers;
   $statistics = (new UpdateUsers($site))->process($options);
