#!/usr/bin/env bash
if [ $1 ] ; then
    libdir="/var/lib/plexmediaserver"
    uid=`id -u`
    gid=`id -g`
    hostname=`hostname`
    cmd="docker create \
    --name plex \
    --net=host \
    -e TZ=\"America/Denver\" \
    -e PLEX_CLAIM=\"${1}\" \
    -e PLEX_UID=${uid} \
    -e PLEX_GID=${gid} \
    -h ${hostname} \
    -v ${libdir}:/config \
    -v ${libdir}/transcode:/transcode \
    -v /mine:/data \
    plexinc/pms-docker:public"

    echo $cmd
    $cmd
else
    echo "A claim code must be provided"
    echo "Ex:  ${BASH_SOURCE} claim-1234567890abcdefghi"
fi