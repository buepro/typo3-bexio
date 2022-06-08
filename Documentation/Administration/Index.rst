.. include:: /Includes.rst.txt

.. _admin:

==============
Administration
==============

Follow these steps to authenticate the app and use the
:ref:`console commands <user-console>` as well as the :ref:`API <api>`.

.. rst-class:: bignums-xxl

#. Url segment challenge

   Create a **url segment challenge** consisting of lower case letters and
   numbers with about 20 characters.

#. Create an app

   -  Login to `developer.bexio.com <https://developer.bexio.com/>`__ and
      create an app.

   -  The redirect URL has the scheme:

      `[https://domain.ch]/bexio-auth-[url segment challenge]`

      Replace **[https://domain.ch]** with your domain and **[url segment challenge]**
      with a cryptic value created in the previous step.

   -  Take note from the `Client ID` and the `Client Secret`.

#. Create site configuration

   -  Create a bexio folder in your site directory. In case the site identifier
      is `default` the directory hierarchy would be `config/sites/default/bexio`.

   -  Create a bexio configuration file `config/sites/default/bexio/site.yaml`.

   -  Include the bexio configuration file in `config/sites/default/config.yaml`
      by adding the following lines on the bottom:

      .. code-block:: yaml

         imports:
           - { resource: './bexio/site.yaml' }

   -  Copy the following configuration into the bexio configuration file
      (`config/sites/default/bexio/site.yaml`) and set the `urlSegmentChallenge`,
      the `clientId` as well as the `clientSecret` with the values obtained in
      the previous steps:

      .. code-block:: yaml

         auth:
           urlSegmentChallenge: muhh
           clientId: 11111111-1111-1111-11111111111111111
           clientSecret: aaaaaaaaaaaaaaaaaaaa-aaaaa-aaaaaaaaaa-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
           scopes: ['openid', 'profile', 'contact_edit', 'offline_access', 'kb_invoice_edit', 'bank_payment_edit']

#. Clear the cache

   This is needed since the site configuration changed.

#. Authenticate the app

   Load the `Redirect URL` defined for the bexio app in a browser. The url has
   the following structure:

   `[https://domain.ch]/bexio-auth-[url segment challenge]`

#. Finalize site configuration

   Add the remaining :ref:`site configuration properties <config-site>` to the
   bexio configuration file `config/sites/default/bexio/site.yaml`.

#. Use it...

   Use the :ref:`console commands <user-console>` as well as the :ref:`API <api>`.
