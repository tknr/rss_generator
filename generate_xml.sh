#!/bin/bash

export IFS=$'\n'

for php in `find ./public -iname "*.php"`;do
	echo ${php}
	xml=`basename ${php} | sed -e "s|\.php||g"`
	echo ${xml}
	php ${php} > public/static/${xml}.xml
done

git add --all || exit 1
git commit -m "`date`" || exit 1
git push || exit 1

