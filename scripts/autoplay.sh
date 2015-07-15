#!/bin/bash
#
# autoplay.sh
# Loops newest file in "path" directory
#

path="/usr/local/nginx/html/videos/"

while :
do
  file="$(ls -Art $path | tail -n 1)"
  ffmpeg -re -i "$path/$file" -c copy -f flv rtmp://127.0.0.1/autoplay/autoplay;
done
