CREATE TABLE IF NOT EXISTS rotation (
  id SERIAL NOT NULL,
  name VARCHAR(128) NOT NULL,
  criteria TEXT NOT NULL,
  playlist INTEGER,
  seed FLOAT,
  PRIMARY KEY (id)
);

ALTER TABLE rotation ADD CONSTRAINT rotation_cc_playlist_fkey
  FOREIGN KEY (playlist)
  REFERENCES cc_playlist (id)
  ON DELETE SET NULL;

INSERT INTO rotation (name, criteria) VALUES ('Airtime Mix', '[{"column":"length","comparison":">","value":"00:01:00"},{"column":"length","comparison":"<","value":"00:10:00"}]');

ALTER TABLE cc_show_instances ADD COLUMN rotation INTEGER;
ALTER TABLE cc_show_instances ADD COLUMN rotation_scheduled BOOLEAN;
ALTER TABLE cc_show_instances ADD CONSTRAINT cc_show_instances_rotation_fkey
  FOREIGN KEY (rotation)
  REFERENCES rotation (id)
  ON DELETE SET NULL;
