.. include:: /Includes.rst.txt

.. _user:

===========
User manual
===========

Frontend users
==============

Frontend users can be linked to Bexio contacts by assigning a contact id to the
field `tx_bexio_id`. In many cases a contact representing a person is associated
with a contact representing a company. Such relations are reflected by assigning
the company contact reference to the field `tx_bexio_company_id`.

The `tx_bexio_id` and `tx_bexio_company_id` fields can be assigned manually, by
the bexio:updateusers command or via the API.

Console
=======

.. note::

   Use the `-h` option to show details for a command

.. code-block:: shell
   :caption: Update frontend users

   path/to/bin/typo3 bexio:updateusers
