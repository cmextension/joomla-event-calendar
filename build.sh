#!/bin/sh

buildDir="build"
version="0.3.0"

if [ ! -d $buildDir ]; then
    echo "$buildDir doesn't exist"

    if mkdir -p $buildDir; then
        echo "Created $buildDir successfully"
    else
        echo "Creating $buildDir failed"
        echo "Exit..."
        exit 1
    fi
fi

sh ./build-scripts/com_eventcalendar.sh -v $version -d $buildDir \
    && sh ./build-scripts/mod_eventcalendar.sh -v $version -d $buildDir \
    && sh ./build-scripts/plg_eventcalendar_sample.sh -v $version -d $buildDir
