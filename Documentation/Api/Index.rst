.. include:: /Includes.rst.txt

.. _api:

===
API
===

.. _api-tasks:

Tasks
=====

.. code-block:: php
   :caption: Update frontend users

   // use Buepro\Bexio\Task\UpdateUsers;
   $result = (new UpdateUsers($site))->initialize($options)->process();

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
