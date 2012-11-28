ALTER TABLE reactions ADD wordpress_imported BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE reactions ADD wordpress_import_attempts INT NOT NULL DEFAULT 0;

ALTER TABLE reactions ADD wordpress_failed BOOLEAN NOT NULL DEFAULT FALSE;