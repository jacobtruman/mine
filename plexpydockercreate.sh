#!/usr/bin/env bash
libdir="/var/lib/plexmediaserver"
uid=`id -u`
gid=`id -g`
cmd="docker create \
--name plexpy \
-v ${libdir}:/config \
-v ${libdir}/Library/Application\ Support/Plex\ Media\ Server/Logs/:/logs:ro \
-e PGID=${uid} \
-e PUID=${gid} \
-e TZ=\"America/Denver\" \
-p 8181:8181 \
linuxserver/plexpy"

echo $cmd
$cmd