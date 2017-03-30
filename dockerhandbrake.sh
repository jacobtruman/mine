#!/usr/bin/env bash
export HANDBRAKE_OPTIONS="-e x264  -q 20.0 -a 1,1 -E ffaac,copy:ac3 -B 256,256 -6 5point1,auto -R Auto,Auto -D 0.0,0.0 --audio-copy-mask aac,ac3,dtshd,dts,mp3 --audio-fallback ffac3 --decomb --loose-anamorphic --modulus 2 -m --x264-preset medium --h264-profile high --h264-level auto"

docker run -it --rm \
    -v /mine/Movies:/input \
    -v /mine/RIP:/output \
    supercoder/docker-handbrake-cli \
    handbrake-cli \
    -i /input/2001/The\ Lord\ of\ the\ Rings\ The\ Fellowship\ of\ the\ Ring\ \(2001\).mp4 \
    -o /output/The\ Lord\ of\ the\ Rings\ The\ Fellowship\ of\ the\ Ring\ \(2001\).mp4 \
    --srt-file /input/2001/The\ Lord\ of\ the\ Rings\ The\ Fellowship\ of\ the\ Ring\ \(2001\).srt \
    --srt-lang eng \
    $HANDBRAKE_OPTIONS
