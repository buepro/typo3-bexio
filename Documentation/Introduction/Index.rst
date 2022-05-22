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

Usage
=====

Following these steps to use the client:

#. Carry out the setup as outlined in the :ref:`administration manual <admin>`

#. Authenticate the usage by loading the authentication url
   (`https://your-domain.ch/bexio-auth-your_challange`). You will be redirected
   to the bexio authentication server. Upon successful authentication a tokens
   file will be obtained.

#. Get a client from the ApiService

   .. code-block:: php

      use Buepro\Bexio\Service\ApiService;

      // Get the ApiService with the GeneralUtility or by dependency injection
      $apiService = GeneralUtility::makeInstance(ApiService::class);
      // Initialize the service for a site and get the client
      $client = $apiService->initialize($site)->getClient();
      // Or get the client from an already initialized service
      $clientInOtherScope = (GeneralUtility::makeInstance(ApiService::class))->getClient();

#. Use the client

   .. code-block:: php

      // @link https://github.com/onlime/bexio-api-client/tree/main/src/Bexio/Resource
      use Bexio\Resource\Contact

      $bexioContact = new Contact($client);
      $contacts = $bexioContact->getContacts();

Prerequisites
=============

This extension requires a composer based installation.

Credits
=======

-  This extension has been started by
   `Philipp Müller from lavitto.ch <https://www.lavitto.ch/>`__
-  It uses the package
   `onlime/bexio-api-client <https://github.com/onlime/bexio-api-client>`__
   from `Philip Iezzi <https://www.onlime.ch/>`__
