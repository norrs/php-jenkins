#!/bin/sh

topdir="$(cd "$(dirname "$0")"/..; pwd)"

deps_to_protect='norrs/php-example-config'

for dep in $deps_to_protect; do
    test -d "$topdir/vendor/$dep" || continue
    cd "$topdir/vendor/$dep";
    if [ $(git diff composer/master..master|wc -l) != 0 ]; then
        echo "WARNING: *******************************************************************"
        echo "WARNING:"
        echo "WARNING: $dep seems to have committed but un-pushed changes,"
        echo "WARNING: not updating composer.lock!"
        echo "WARNING:"
        echo "WARNING: *******************************************************************"
        exit 0
    fi
done

exec "$topdir/composer.phar" "$@"

