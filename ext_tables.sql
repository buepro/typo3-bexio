CREATE TABLE fe_users (
	tx_bexio_id int(11) unsigned DEFAULT '0' NOT NULL,
	tx_bexio_company_id int(11) unsigned DEFAULT '0' NOT NULL,
	tx_bexio_language_id int(11) unsigned DEFAULT '0' NOT NULL,
	tx_bexio_invoices int(11) unsigned NOT NULL DEFAULT '0',
);

CREATE TABLE tx_bexio_domain_model_invoice (
  user int(11) unsigned DEFAULT '0' NOT NULL,
  title varchar(255) NOT NULL DEFAULT '',
  document_nr varchar(255) NOT NULL DEFAULT '',
  language_id int(11) unsigned DEFAULT '0' NOT NULL,
	bank_account_id int(11) unsigned DEFAULT '0' NOT NULL,
	currency_id int(11) unsigned DEFAULT '0' NOT NULL,
	total double(11,2) DEFAULT '0.00' NOT NULL,
	is_valid_from int(11) DEFAULT '0' NOT NULL,
	is_valid_to int(11) DEFAULT '0' NOT NULL,
	kb_item_status_id int(11) unsigned DEFAULT '0' NOT NULL,
	reference varchar(255) NOT NULL DEFAULT '',
	api_reference varchar(255) NOT NULL DEFAULT '',
	viewed_by_client_at int(11) DEFAULT '0' NOT NULL,
	esr_id int(11) unsigned DEFAULT '0' NOT NULL,
	qr_invoice_id int(11) unsigned DEFAULT '0' NOT NULL,
	network_link varchar(511) NOT NULL DEFAULT '',
);
