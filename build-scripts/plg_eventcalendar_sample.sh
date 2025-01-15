#!/bin/sh

while getopts ":v:d:" opt; do
    case $opt in
        v)
            version="$OPTARG"
            ;;
        d)
            buildDir="$PWD/$OPTARG"
            ;;
        \?)
            echo "Invalid option: -$OPTARG" >&2
            exit 1
            ;;
        :)
            echo "Option -$OPTARG requires an argument." >&2
            exit 1
            ;;
    esac
done

packageDir="$buildDir/plg_eventcalendar_sample"
packageFile="$buildDir/plg_eventcalendar_sample_$version.zip"

if mkdir -p $packageDir; then
    echo "Re-created $packageDir successfully"
else
    echo "Creating $packageDir failed"
    echo "Exit..."
    exit 1
fi

plgSrc="$PWD/plugins/eventcalendar/sample"
plgDest="$packageDir"

if cp -R $plgSrc $plgDest; then
    echo "Copied $plgSrc to $plgDest"
else
    echo "Copying $plgSrc to $plgDest failed"
    echo "Exit..."
    exit 1
fi

cd $packageDir

if zip -rq $packageFile *; then
  echo "$packageFile was created successfully"
else
  echo "Creating $packageFile failed"
fi

cd $buildDir

if rm -rf $packageDir; then
    echo "Deleted $packageDir successfully"
else
    echo "Deleting $packageDir failed"
fi

exit 0