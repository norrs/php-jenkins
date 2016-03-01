#!/bin/sh
set -e 
RELEASE=${RELEASE:-file:///home/xdrift/roysn/public_html/php-jenkins/www-latest.tgz}

RELEASE_TARBALL_NAME="${RELEASE##*/}"

RELEASE_VERSION="${RELEASE##*-}" # Take basename from URL
RELEASE_VERSION="${RELEASE_VERSION%.tgz}" # Strip .tgz

if [ "${RELEASE_VERSION}" = "latest" ];then
    echo "Detected www-latest, fetching version from basedir in tarball!"
    RELEASE_VERSION=$(tar -t --no-recursion -f "/home/xdrift/roysn/public_html/php-jenkins/${RELEASE_TARBALL_NAME}" | head -n1)
    RELEASE_VERSION="${RELEASE_VERSION%/}" ## strip / at the end
    # Move www-latest.tgz to www-releaseversion.tgz to deploy script finds it
    # when refering to sha commit as release version.
		#mv "${RELEASE_TARBALL_NAME}"  "${RELEASE_VERSION}.tgz"
    #RELEASE_VERSION="${RELEASE_VERSION##*-}" # strip prefix www

fi
tar -zxvf "/home/xdrift/roysn/public_html/php-jenkins/${RELEASE_VERSION}.tgz" 
ln -sf "/home/xdrift/roysn/public_html/php-jenkins/${RELEASE_VERSION}" latest
