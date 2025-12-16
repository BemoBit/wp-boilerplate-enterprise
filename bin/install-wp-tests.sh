#!/usr/bin/env bash

if [ $# -lt 3 ]; then
    echo "usage: $0 <db-name> <db-user> <db-pass> [db-host] [wp-version] [skip-database-creation]"
    exit 1
fi

DB_NAME=$1
DB_USER=$2
DB_PASS=$3
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}
SKIP_DB_CREATE=${6-false}

TMPDIR=${TMPDIR-/tmp}
TMPDIR=$(echo $TMPDIR | sed -e "s/\/$//")
WP_TESTS_DIR=${WP_TESTS_DIR-$TMPDIR/wordpress-tests-lib}
WP_CORE_DIR=${WP_CORE_DIR-$TMPDIR/wordpress}

download() {
    if [ `which curl` ]; then
        curl -s "$1" > "$2";
    elif [ `which wget` ]; then
        wget -nv -O "$2" "$1"
    fi
}

if [[ $WP_VERSION =~ ^[0-9]+\.[0-9]+\-(beta|RC)[0-9]+$ ]]; then
    WP_BRANCH=${WP_VERSION%\-*}
    WP_TESTS_TAG="branches/$WP_BRANCH"

elif [[ $WP_VERSION =~ ^[0-9]+\.[0-9]+$ ]]; then
    WP_TESTS_TAG="branches/$WP_VERSION"
elif [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0-9]+ ]]; then
    if [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0] ]]; then
        WP_TESTS_TAG="tags/${WP_VERSION%??}"
    else
        WP_TESTS_TAG="tags/$WP_VERSION"
    fi
elif [[ $WP_VERSION == 'nightly' || $WP_VERSION == 'trunk' ]]; then
    WP_TESTS_TAG="trunk"
else
    download http://api.wordpress.org/core/version-check/1.7/ /tmp/wp-latest.json
    grep '[0-9]+\.[0-9]+(\.[0-9]+)?' /tmp/wp-latest.json
    LATEST_VERSION=$(grep -o '"version":"[^"]*' /tmp/wp-latest.json | sed 's/"version":"//' | head -1)
    if [[ -z "$LATEST_VERSION" ]]; then
        echo "Latest WordPress version could not be found"
        exit 1
    fi
    WP_TESTS_TAG="tags/$LATEST_VERSION"
fi
set -ex

install_wp() {
    if [ -d $WP_CORE_DIR ]; then
        return;
    fi

    mkdir -p $WP_CORE_DIR

    if [[ $WP_VERSION == 'nightly' || $WP_VERSION == 'trunk' ]]; then
        mkdir -p $TMPDIR/wordpress-trunk
        rm -rf $TMPDIR/wordpress-trunk/*
        svn export --quiet https://core.svn.wordpress.org/trunk $TMPDIR/wordpress-trunk/wordpress
        mv $TMPDIR/wordpress-trunk/wordpress/* $WP_CORE_DIR
    else
        if [ $WP_VERSION == 'latest' ]; then
            local ARCHIVE_NAME='latest'
        elif [[ $WP_VERSION =~ [0-9]+\.[0-9]+ ]]; then
            local ARCHIVE_NAME="wordpress-$WP_VERSION"
        else
            local ARCHIVE_NAME="wordpress-$LATEST_VERSION"
        fi
        download https://wordpress.org/${ARCHIVE_NAME}.tar.gz $TMPDIR/wordpress.tar.gz
        tar --strip-components=1 -zxmf $TMPDIR/wordpress.tar.gz -C $WP_CORE_DIR
    fi

    download https://raw.githubusercontent.com/marber/wp-config-for-tests/master/wp-config.php $WP_CORE_DIR/wp-config.php
}

install_test_suite() {
    if [ -d $WP_TESTS_DIR ]; then
        return;
    fi

    mkdir -p $WP_TESTS_DIR

    rm -rf $WP_TESTS_DIR/{includes,data}

    svn export --quiet --ignore-externals https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/includes/ $WP_TESTS_DIR/includes
    svn export --quiet --ignore-externals https://develop.svn.wordpress.org/${WP_TESTS_TAG}/tests/phpunit/data/ $WP_TESTS_DIR/data
}

install_db() {
    if [ ${SKIP_DB_CREATE} = "true" ]; then
        return 0
    fi

    local PARTS=(${DB_HOST//\:/ })
    local DB_HOSTNAME=${PARTS[0]};
    local DB_SOCK_OR_PORT=${PARTS[1]};
    local EXTRA=""

    if ! [ -z $DB_HOSTNAME ] ; then
        if [ $(echo $DB_SOCK_OR_PORT | grep -e '^[0-9]\{1,\}$') ]; then
            EXTRA=" --host=$DB_HOSTNAME --port=$DB_SOCK_OR_PORT --protocol=tcp"
        elif ! [ -z $DB_SOCK_OR_PORT ] ; then
            EXTRA=" --socket=$DB_SOCK_OR_PORT"
        elif ! [ -z $DB_HOSTNAME ] ; then
            EXTRA=" --host=$DB_HOSTNAME --protocol=tcp"
        fi
    fi

    mysqladmin create $DB_NAME --user="$DB_USER" --password="$DB_PASS"$EXTRA
}

install_wp
install_test_suite
install_db
