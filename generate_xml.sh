#!/bin/bash
cd `dirname $0`
export IFS=$'\n'

RSS_LIST=''
for php in `find ./public -iname "*.php"`;do
	echo ${php}
	xml=`basename ${php} | sed -e "s|\.php||g"`
	echo ${xml}
	php ${php} > public/static/${xml}.xml
	RSS_LIST+="<li><a href='static/${xml}.xml'>${xml}</li>\n";
done
cat src/index.html | sed -e "s|#RSS_LIST#|${RSS_LIST}|g" > public/index.html

git add --all || exit 1
git commit -m "`date`" || exit 1
git push || exit 1

