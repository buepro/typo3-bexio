.. include:: /Includes.rst.txt

.. image:: https://poser.pugx.org/buepro/typo3-bexio/v/stable.svg
   :alt: Latest Stable Version
   :target: https://extensions.typo3.org/extension/bexio/

.. image:: https://img.shields.io/badge/TYPO3-11-orange.svg
   :alt: TYPO3 11
   :target: https://get.typo3.org/version/11

.. image:: https://poser.pugx.org/buepro/typo3-bexio/d/total.svg
   :alt: Total Downloads
   :target: https://packagist.org/packages/buepro/typo3-bexio

.. image:: https://poser.pugx.org/buepro/typo3-bexio/d/monthly
   :alt: Monthly Downloads
   :target: https://packagist.org/packages/buepro/typo3-bexio

.. image:: https://github.com/buepro/typo3-bexio/workflows/CI/badge.svg
   :alt: Continuous Integration Status
   :target: https://github.com/buepro/typo3-bexio/actions?query=workflow%3ACI

.. _introduction:

============
Introduction
============

This extension provides a client for the `Bexio API <https://docs.bexio.com/>`__
by extending the client from the package
`onlime/bexio-api-client <https://github.com/onlime/bexio-api-client>`__.

.. _introduction-usage:

Usage
=====

Following these steps to interact with the bexio api:

#. Carry out the setup as outlined in the :ref:`administration <admin>`
   and :ref:`configuration manual <config>`.

#. Authenticate the usage by loading the authentication url
   (`https://your-domain.ch/bexio-auth-your_challange`). You will be redirected
   to the bexio authentication server. Upon successful authentication a tokens
   file will be obtained.

#. Interact with the bexio api through the console or the API.

.. _introduction-usage-console:

Console
-------

.. code-block:: shell
   :caption: Update all frontend users that are already linked to a bexio contact

   path/to/bin/typo3 bexio:updateusers

.. _introduction-usage-api:

API
---

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
   :caption: Get all bexio contacts

   // @link https://github.com/onlime/bexio-api-client/tree/main/src/Bexio/Resource
   use Bexio\Resource\Contact

   $bexioContact = new Contact($client);
   $contacts = $bexioContact->getContacts();

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

.. _introduction-prerequisites:

Prerequisites
=============

This extension requires a composer based installation.

.. _introduction-credits:

Credits
=======

-  This extension has been started by
   `Philipp Müller from lavitto.ch <https://www.lavitto.ch/>`__
-  It uses the package
   `onlime/bexio-api-client <https://github.com/onlime/bexio-api-client>`__
   from `Philip Iezzi <https://www.onlime.ch/>`__
