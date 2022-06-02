.. include:: /Includes.rst.txt

.. _admin:

==============
Administration
==============

.. _admin-auth:

Authentication
==============

Upon defining the site and bexio related configurations the authentication can
be performed by loading the authentication url in a browser. The url has the
following structure:

`[https://domain.ch]/bexio-auth-[url segment challenge]`

Replace **[https://domain.ch]** with your domain and **[url segment challenge]**
with the value defined for :ref:`config-site-auth-urlSegmentChallenge`.
