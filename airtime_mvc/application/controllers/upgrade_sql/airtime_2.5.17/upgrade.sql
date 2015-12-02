CREATE TABLE IF NOT EXISTS rotation (
  id SERIAL NOT NULL,
--   schedule_status BOOLEAN NOT NULL,
  type VARCHAR(128) NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE cc_show_instances ADD COLUMN rotation INTEGER;
ALTER TABLE cc_show_instances ADD COLUMN rotation_scheduled BOOLEAN;
ALTER TABLE cc_show_instances ADD CONSTRAINT rotation_fkey
  FOREIGN KEY (rotation)
  REFERENCES rotation (id)
  ON DELETE CASCADE;
