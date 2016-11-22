#!/bin/bash

set -ex

VERSION="$1"

[ -z "${VERSION}" ] && {
	echo "Version not set." >&2
	exit 1
}

#git checkout master
#git fetch
#git reset --hard origin/master

#git checkout -b "release/v${VERSION}"
rm -f composer.json composer.lock
sed -i -e "s,[0-9]\.[0-9]-dev,${VERSION},g" ext_emconf.php
#git add ext_emconf.php
rm build-release.sh
#git commit -m"Release version ${VERSION}"
#git tag -s -m"Release version ${VERSION}" v${VERSION}
#git push --tags

zip -r typo3_forum_${VERSION}.zip *
