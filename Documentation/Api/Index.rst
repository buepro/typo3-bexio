.. include:: /Includes.rst.txt

.. _api:

===
API
===

.. _api_resources:

Resources
=========

The package `onlime/bexio-api-client` provides various resource classes to
interact with the bexio api (see namespace `Bexio\Resource`) where this package
extends some of them and adds additional ones (see namespace
`Buepro\Bexio\Api\Resource`). Resource objects are instantiated with a client
object that can be obtained by the ApiService object:

.. code-block:: php
   :caption: Get a client from the ApiService

   use Buepro\Bexio\Service\ApiService;

   // Get the ApiService with the GeneralUtility or by dependency injection
   $apiService = GeneralUtility::makeInstance(ApiService::class);
   // Initialize the service for a site and get the client
   $client = $apiService->initialize($site)->getClient();
   // Or get the client from an already initialized service
   $clientInOtherScope = (GeneralUtility::makeInstance(ApiService::class))->getClient();

.. code-block:: php
   :caption: Get all bexio contacts using the Contact resource object

   // @link https://github.com/onlime/bexio-api-client/tree/main/src/Bexio/Resource
   use Bexio\Resource\Contact

   $bexioContact = new Contact($client);
   $contacts = $bexioContact->getContacts();

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
