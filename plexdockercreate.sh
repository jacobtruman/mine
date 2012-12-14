docker create \
--name plex \
--net=host \
-e TZ="America/Denver" \
-e PLEX_CLAIM="claim-UPWbRQHkQx6jRZEjsEm7" \
-h wheatley \
-v /var/lib/plexmediaserver:/config \
-v /var/lib/plexmediaserver/Library/Application\ Support\/Plex\ Media\ Server/Cache/Transcode:/transcode \
-v /mine:/data \
plexinc/pms-docker
