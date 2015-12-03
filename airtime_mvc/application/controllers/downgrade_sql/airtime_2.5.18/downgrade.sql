ALTER TABLE cc_show_instances DROP COLUMN rotation;
ALTER TABLE cc_show_instances DROP CONSTRAINT cc_show_instances_rotation_fkey;

DROP TABLE IF EXISTS rotation CASCADE;
