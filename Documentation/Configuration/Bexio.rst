.. include:: /Includes.rst.txt

.. _config-bexio:

=====
Bexio
=====

#. Create an app at `developer.bexio.com <https://developer.bexio.com/>`__ with the
   following properties:

   -  Allowed redirect URL: `[https://domain.ch]/bexio-auth-[url segment challenge]`

      Replace **[https://domain.ch]** with your domain and
      **[url segment challenge]** with the value defined for
      :ref:`config-site-authUrlSegmentChallenge`.

#. Assign the "Client ID" and "Client Secret" to the corresponding properties
   `bexio.clientId` and `bexio.clientSecret` from the site configuration.
