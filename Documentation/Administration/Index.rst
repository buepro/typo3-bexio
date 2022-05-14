.. include:: /Includes.rst.txt

.. _admin:

==============
Administration
==============

.. _admin_site_conf:

Site configuration
==================

#. Define an url segment challenge for the bexio authentication in the site
   configuration for the property `bexio.authUrlSegmentChallenge`. For security
   reasons prefer something cryptic like `wdoufkyrkLoqaarxxvmdxyyj`.

#. Set the properties `bexio.clientId` and `bexio.clientSecret` (see
   :ref:`Admin - Bexio <admin_bexio>`)

#. Set the property `bexio.scopes` (see `Bexio API scopes
   <https://docs.bexio.com/#section/Authentication/API-Scopes>`__)

.. code-block:: yaml
   :caption: Example bexio properties in site configuration

   bexio:
     authUrlSegmentChallenge: muhh
     clientId: 11111111-1111-1111-11111111111111111
     clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
     scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']

.. _admin_bexio:

Bexio
=====

#. Create an app at `developer.bexio.com <https://developer.bexio.com/>`__ with the
   following properties:

   -  Allowed redirect URL: `[https://domain.ch]/bexio-auth-[url segment challenge]`

   .. hint::

      Replace `[url segment challenge]` with the value previously assigned to
      the site configuration property `bexio.authUrlSegmentChallenge`.

#. Assign the "Client ID" and "Client Secret" to the corresponding properties
   `bexio.clientId` and `bexio.clientSecret` from the site configuration.
