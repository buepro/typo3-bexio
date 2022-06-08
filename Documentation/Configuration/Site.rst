.. include:: /Includes.rst.txt

.. _config-site:

==================
Site configuration
==================

.. note::

   For changes made in the site configuration to take effect the cache has to
   be cleared.

.. code-block:: yaml
   :caption: Example site configuration for bexio related properties

   bexio:
     auth:
       urlSegmentChallenge: muhh
       clientId: 11111111-1111-1111-11111111111111111
       clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
       scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']
     eventListener:
       emailInvoicePayment:
         to:
           email: bookkeeping@bexio.ddev.site
           name: 'Hans Dampf'
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
     user:
       storageUid: 2
       userGroupUid: 1
       linkMatchProperties: firstName, lastName, email

.. _config-site-auth:

Authentication
==============

.. code-block:: yaml
   :caption: Authentication related site properties

   bexio:
     auth:
       urlSegmentChallenge: muhh
       clientId: 11111111-1111-1111-11111111111111111
       clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
       scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']

.. index:: Site config - Authentication; urlSegmentChallenge
.. _config-site-auth-urlSegmentChallenge:

urlSegmentChallenge
-----------------------

.. container:: table-row

   Property
      bexio.auth.urlSegmentChallenge

   Data type
      string

   Description
      Additional url segment used during the manual authentication process to
      challenge abuses. For security reasons prefer something cryptic like
      `wdoufkyrkLoqaarxxvmdxyyj`.

.. index:: Site config - Authentication; clientId
.. _config-site-auth-clientId:

clientId
--------

.. container:: table-row

   Property
      bexio.auth.clientId

   Data type
      string

   Description
      The client ID provided by the bexio web site (see
      :ref:`Admin - Create an app <admin>`)

.. index:: Site config - Authentication; clientSecret
.. _config-site-auth-clientSecret:

clientSecret
------------

.. container:: table-row

   Property
      bexio.auth.clientSecret

   Data type
      string

   Description
      The client secret provided by the bexio web site (see
      :ref:`Admin - Create an app <admin>`)

.. index:: Site config - Authentication; scopes
.. _config-site-auth-scopes:

scopes
------

.. container:: table-row

   Property
      bexio.auth.scopes

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
     user:
       storageUid = 2
       userGroupUid = 1
       linkMatchProperties = firstName, lastName, email

.. index:: Site config - User; storageUid
.. _config-site-storageUid:

storageUid
----------

.. container:: table-row

   Property
      bexio.user.storageUid

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
      bexio.user.userGroupUid

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
      bexio.user.linkMatchProperties

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
      bexio.invoice.storageUid

   Data type
      int/string

   Description
      Coma separated list of uid's from pages where invoices are located. The
      first item is used to store new records.

.. _config-site-event-listeners:

Event listeners
===============

.. code-block:: yaml
   :caption: Event listeners related properties

   bexio:
     eventListener:
       emailInvoicePayment:
         to:
           email: 'bookkeeping@bexio.ddev.site'
           name: 'Hans Dampf'

.. index:: Site config - Event listeners; emailInvoicePayment
.. _config-site-event-listeners-emailInvoicePayment:

EmailInvoicePayments
--------------------

to.email
~~~~~~~~

.. container:: table-row

   Property
      bexio.eventListener.emailInvoicePayment.to.email

   Data type
      string

   Description
      Email address used in `EmailInvoicePayment` event listener.

to.name
~~~~~~~

.. container:: table-row

   Property
      bexio.eventListener.emailInvoicePayment.to.name

   Data type
      string

   Description
      Email name used in `EmailInvoicePayment` event listener.
