.. include:: /Includes.rst.txt

.. _config-site:

==================
Site configuration
==================

.. code-block:: yaml
   :caption: Example site configuration for bexio related properties

   bexio:
     authUrlSegmentChallenge: muhh
     clientId: 11111111-1111-1111-11111111111111111
     clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
     scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']
     storageUid: 2
     userGroupUid: 1
     linkMatchProperties: firstName, lastName, email
     invoice:
       storageUid: 3
       new:
         userId: 1
         bankAccountId: 8
         currencyId: 2
       position:
         new:
           amount: 1
           unitId: 2
           unitPrice: 90.0
           accountId: 278
           taxId: 16
           type: KbPositionCustom

.. _config-site-auth:

Authentication
==============

.. code-block:: yaml
   :caption: Authentication related site properties

   bexio:
     authUrlSegmentChallenge: muhh
     clientId: 11111111-1111-1111-11111111111111111
     clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
     scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']

.. index:: Site config - Authentication; authUrlSegmentChallenge
.. _config-site-authUrlSegmentChallenge:

authUrlSegmentChallenge
-----------------------

.. container:: table-row

   Property
      bexio.authUrlSegmentChallenge

   Data type
      string

   Description
      Additional url segment used during the manual authentication process to
      challenge abuses. For security reasons prefer something cryptic like
      `wdoufkyrkLoqaarxxvmdxyyj`.

.. index:: Site config - Authentication; clientId
.. _config-site-clientId:

clientId
--------

.. container:: table-row

   Property
      bexio.clientId

   Data type
      string

   Description
      The client ID provided by the bexio web site (see
      :ref:`Admin - Bexio <config-bexio>`)

.. index:: Site config - Authentication; clientSecret
.. _config-site-clientSecret:

clientSecret
------------

.. container:: table-row

   Property
      bexio.clientSecret

   Data type
      string

   Description
      The client secret provided by the bexio web site (see
      :ref:`Admin - Bexio <config-bexio>`)

.. index:: Site config - Authentication; scopes
.. _config-site-scopes:

scopes
------

.. container:: table-row

   Property
      bexio.scopes

   Data type
      string

   Description
      See `Bexio API scopes <https://docs.bexio.com/#section/Authentication/API-Scopes>`__

.. _config-site-user:

User
====

.. code-block:: yaml
   :caption: User related site configuration properties

   bexio:
     storageUid = 2
     userGroupUid = 1
     linkMatchProperties = firstName, lastName, email

.. index:: Site config - User; storageUid
.. _config-site-storageUid:

storageUid
----------

.. container:: table-row

   Property
      bexio.storageUid

   Data type
      int/string

   Description
      Coma separated list of uid's from pages where frontend users are located.
      The first item is used to store new records.

.. index:: Site config - User; userGroupUid
.. _config-site-userGroupUid:

userGroupUid
------------

.. container:: table-row

   Property
      bexio.userGroupUid

   Data type
      int/string

   Description
      Coma separated list from user group uid's that should be assigned to new
      frontend users.

.. index:: Site config - User; linkMatchProperties
.. _config-site-linkMatchProperties:

linkMatchProperties
-------------------

.. container:: table-row

   Property
      bexio.linkMatchProperties

   Data type
      string

   Description
      Coma separated list of ields to be used when linking a bexio customer to
      a frontend user. Use a combination from the following properties: company,
      firstName, lastName, address, zip, city, country, telephone, email, www.

.. _config-site-invoice:

Invoice
=======

The properties under the new keys (`bexio.invoice.new`, `bexio.invoice.position.new`)
serve as default values when creating an invoice. When creating an invoice with
the cli these properties can be modified. The meaning from the respective values
can be looked up in the output from the console command :ref:`user-console-settings`.

.. code-block:: yaml
   :caption: Invoice related site configuration properties

   bexio:
     invoice:
       storageUid: 3
       new:
         userId: 1
         bankAccountId: 8
         currencyId: 2
       position:
         new:
           amount: 1
           unitId: 2
           unitPrice: 90.0
           accountId: 278
           taxId: 16
           type: KbPositionCustom

.. index:: Site config - Invoice; storageUid
.. _config-site-invoice.storageUid:

invoice.storageUid
------------------

.. container:: table-row

   Property
      invoice.storageUid

   Data type
      int/string

   Description
      Coma separated list of uid's from pages where invoices are located. The
      first item is used to store new records.
