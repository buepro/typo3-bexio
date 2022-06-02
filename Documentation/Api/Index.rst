.. include:: /Includes.rst.txt

.. _api:

===
API
===

.. _api-tasks:

Tasks
=====

.. _api-tasks-user:

User
----

.. code-block:: php
   :caption: Update frontend users

   // use Buepro\Bexio\Task\User\UpdateUsers;
   $result = (new UpdateUsers($site))->initialize()->process();

.. code-block:: php
   :caption: Synchronize bexio contacts to frontend users

   // use Buepro\Bexio\Task\User\UpdateUsers;
   $options = [
      UpdateUsers::OPTION_CREATE => true,
   ];
   $result = (new UpdateUsers($site))->initialize($options)->process();

.. _api-tasks-invoice:

Invoice
-------

.. code-block:: php
   :caption: Create an invoice

   // use Buepro\Bexio\Task\Invoice\CreateInvoice;
   $invoice = [
      'title' => 'Test invoice',
      'positions' => [
         'text' => 'Some service',
         'amount' => 3.2,
         'unitPrice' => 90,
      ],
   ];
   $result = (new CreateInvoice($site))->initialize($invoice)->process();
