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

.. index:: API; Client
.. code-block:: php
   :caption: Get a client from the ApiService

   use Buepro\Bexio\Service\ApiService;

   // Get the ApiService with the GeneralUtility or by dependency injection
   $apiService = GeneralUtility::makeInstance(ApiService::class);
   // Initialize the service for a site and get the client
   $client = $apiService->initialize($site)->getClient();
   // Or get the client from an already initialized service
   $clientInOtherScope = (GeneralUtility::makeInstance(ApiService::class))->getClient();

.. index:: API - Resource; Contact
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

.. index:: API - Tasks; UpdateUsers
.. code-block:: php
   :caption: Update frontend users

   // use Buepro\Bexio\Task\User\UpdateUsers;
   $result = GeneralUtility::makeInstance(UpdateUsers::class)
      ->initialize($site)
      ->process();

.. code-block:: php
   :caption: Synchronize bexio contacts to frontend users

   // use Buepro\Bexio\Task\User\UpdateUsers;
   $options = [
      UpdateUsers::OPTION_CREATE => true,
   ];
   $result = GeneralUtility::makeInstance(UpdateUsers::class)
      ->initialize($site, $options)
      ->process();

.. _api-tasks-invoice:

Invoice
-------

.. index:: API - Tasks; CreateInvoice
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
   $result = GeneralUtility::makeInstance(CreateInvoice::class)
      ->initialize($site, $invoice)
      ->process();

.. index:: API - Tasks; UpdateInvoices
.. code-block:: php
   :caption: Update paid and get pending invoices

   // use Buepro\Bexio\Task\Invoice\UpdateInvoices;
   $result = GeneralUtility::makeInstance(UpdateInvoices::class)
      ->initialize($site)
      ->process();

.. index:: API - Tasks; ProcessPayments
.. code-block:: php
   :caption: Process invoice payments by emitting an event

   // use Buepro\Bexio\Task\Invoice\ProcessPayments;
   $result = GeneralUtility::makeInstance(ProcessPaymentsTask::class)
      ->initialize($site)
      ->process();

.. _api-events:

Events
======

.. index:: API - Events; InvoicePaymentEvent
.. _api-events-InvoicePaymentEvent:

InvoicePaymentEvent
-------------------

:php:`\Buepro\Bexio\Event\InvoicePaymentEvent`

Event to listen to after an invoice payment has been detected.

:getSite(): Returns the :php:`TYPO3\CMS\Core\Site\Entity\Site` the invoice belongs to.
:getInvoice(): Returns the :php:`\Buepro\Bexio\Domain\Model\Invoice` that has been paid.
:requestProcessing(string $reason): Use this method to request this event to be emitted again.
:getReprocessingRequested(): Returns :php:`boolean` indicating that one event.
   handler requested this event to be emitted again.
:getReprocessingRequestReasons(): Returns a string array. Each event handler can add its reason.

.. _api-event-listeners:

Event listeners
===============

.. index:: API - Event listeners; EmailInvoicePayment
.. _api-event-listeners-EmailInvoicePayment:

EmailInvoicePayment
-------------------

:php:`\Buepro\Bexio\EventListener\EmailInvoicePayment`
