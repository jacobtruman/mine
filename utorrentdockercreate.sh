#!/usr/bin/env bash
docker run \
--name utorrent \
-v /torrents/data:/data \
-v /torrents/settings:/utorrent \
-p 8080:8080 \
-p 6881:6881 \
ekho/utorrent
