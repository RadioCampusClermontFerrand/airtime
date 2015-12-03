CREATE TABLE IF NOT EXISTS "listener_stats"
(
    "id" serial NOT NULL,
    "disconnect_timestamp" TIMESTAMP NOT NULL,
    "geo_ip" VARCHAR(256) NOT NULL,
    "session_duration" INTEGER NOT NULL,
    "mount" VARCHAR(256) NOT NULL,
    "bytes" INTEGER NOT NULL,
    "referrer" VARCHAR(4096),
    "user_agent" VARCHAR(4096),
    PRIMARY KEY ("id")
);