.. include:: /Includes.rst.txt

.. _user:

===========
User manual
===========

.. _user-frontend-user:

Frontend users
==============

Frontend users can be linked to Bexio contacts by assigning a contact id to the
field `tx_bexio_id`. In many cases a contact representing a person is associated
with a contact representing a company. Such relations are reflected by assigning
the company contact reference to the field `tx_bexio_company_id`.

The `tx_bexio_id` and `tx_bexio_company_id` fields can be assigned manually, by
the bexio:updateusers command or via the API.

.. _user-console:

Console
=======

.. note::

   Use the `-h` option to show details for a command

.. _user-console-invoice:

Invoice
-------

.. index:: Command; bexio:createinvoice
.. code-block:: shell
   :caption: Create an invoice interactively for frontend user with uid 1

   path/to/bin/typo3 bexio:createinvoice default invoice 1

.. index:: Command; bexio:processpayments
.. code-block:: shell
   :caption: Process invoice payments by emitting an event

   path/to/bin/typo3 bexio:processpayments

.. index:: Command; bexio:updateinvoices
.. code-block:: shell
   :caption: Update paid and get pending invoices

   path/to/bin/typo3 bexio:updateinvoices

.. _user-console-updateusers:

Users
-----

.. index:: Command; bexio:updateusers
.. code-block:: shell
   :caption: Update all frontend users that are already linked to a bexio contact

   path/to/bin/typo3 bexio:updateusers

.. _user-console-other:

Other
-----

.. index:: Command; bexio:query
.. _user-console-query:

Query
~~~~~

.. note::

   When using the option `-a` you will be asked to provide the arguments for the
   method call. Separate each argument by a line. Each argument will be json
   decoded.

.. code-block:: shell
   :caption: Send an invoice with the arguments below

   path/to/bin/typo3 bexio:query -a default invoice sendInvoice

.. code-block:: json
   :caption: Json encoded arguments to send the invoice 137 to somebody@ik.me

   137
   {"recipient_email": "somebody@ik.me", "subject": "Invoice from command", "message": "Here it is: [Network Link]", "mark_as_open": false}

.. code-block:: shell
   :caption: Get invoice and save it as test.pdf

   path/to/bin/typo3 bexio:query -a -f test.pdf -r default invoice getPdf

.. code-block:: shell
   :caption: Get all available languages

   path/to/bin/typo3 bexio:query default other getLanguages

.. index:: Command; bexio:settings
.. _user-console-settings:

Settings
~~~~~~~~

.. code-block:: shell
   :caption: Write bexio settings in yaml format to settings.yaml

   path/to/bin/typo3 bexio:settings -f settings.yaml default
