ALTER TABLE historie_data ADD wordpress_imported BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE historie_data ADD wordpress_import_attempts INT NOT NULL DEFAULT 0;

ALTER TABLE historie_data ADD wordpress_failed BOOLEAN NOT NULL DEFAULT FALSE;