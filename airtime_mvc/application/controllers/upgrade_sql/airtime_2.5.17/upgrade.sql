CREATE TABLE IF NOT EXISTS rotation (
  id SERIAL NOT NULL,
  name VARCHAR(128) NOT NULL,
  criteria TEXT NOT NULL,
  playlist INTEGER,
  PRIMARY KEY (id)
);

ALTER TABLE rotation ADD CONSTRAINT rotation_cc_playlist_fkey
  FOREIGN KEY (playlist)
  REFERENCES cc_playlist (id)
  ON DELETE SET NULL;

-- TODO decide on how to implement criteria
INSERT INTO rotation (name, criteria) VALUES ('Airtime Mix', '');

ALTER TABLE cc_show_instances ADD COLUMN rotation INTEGER;
ALTER TABLE cc_show_instances ADD COLUMN rotation_scheduled BOOLEAN;
ALTER TABLE cc_show_instances ADD CONSTRAINT cc_show_instances_rotation_fkey
  FOREIGN KEY (rotation)
  REFERENCES rotation (id)
  ON DELETE SET NULL;
