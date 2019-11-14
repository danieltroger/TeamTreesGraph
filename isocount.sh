#!/bin/bash
# while true
# do
# echo -n "$(date --iso-8601=seconds) " >> op.txt
# #curl -s https://teamtrees.org|grep totalTrees|grep div|awk ' { print $4 } '|cut -d \" -f2 >> op.txt
# curl 'https://teamtrees.org/' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:71.0) Gecko/20100101 Firefox/71.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H "Referer: https://www.$RANDOM.com" -H 'Connection: keep-alive' -H 'Upgrade-Insecure-Requests: 1' -H 'TE: Trailers' -s |grep totalTrees|grep div|awk ' { print $4 } '|cut -d \" -f2 >> op.txt
# sleep 4
# done
while true
do
  dat=$(date --iso-8601=seconds)
  #curl -s https://teamtrees.org|grep totalTrees|grep div|awk ' { print $4 } '|cut -d \" -f2 >> op.txt
  trees=$(curl 'https://teamtrees.org/' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:71.0) Gecko/20100101 Firefox/71.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H "Referer: https://www.$RANDOM.com" -H 'Connection: keep-alive' -H 'Upgrade-Insecure-Requests: 1' -H 'TE: Trailers' -s |grep totalTrees|grep div|awk ' { print $4 } '|cut -d \" -f2)
  if [ -z "$trees" ]
  then
        sleep 1
  else
        echo "$dat $trees" >> op.txt
        sleep 10
  fi
done
