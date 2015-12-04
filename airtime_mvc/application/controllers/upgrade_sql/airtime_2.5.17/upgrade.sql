CREATE TABLE IF NOT EXISTS "listener_stats"
(
    "id" serial NOT NULL,
    "disconnect_timestamp" TIMESTAMP NOT NULL,
    "ip" VARCHAR(256) NOT NULL,
    "city" VARCHAR(256),
    "country" VARCHAR(256),
    "session_duration" INTEGER NOT NULL,
    "mount" VARCHAR(256) NOT NULL,
    "bytes" INTEGER NOT NULL,
    "referrer" VARCHAR(4096),
    "user_agent" VARCHAR(4096),
    PRIMARY KEY ("id")
);