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

packageDir="$buildDir/mod_eventcalendar"
packageFile="$buildDir/mod_eventcalendar_$version.zip"

if mkdir -p $packageDir; then
    echo "Re-created $packageDir successfully"
else
    echo "Creating $packageDir failed"
    echo "Exit..."
    exit 1
fi

modSrc="$PWD/modules/mod_eventcalendar"
modDest="$packageDir"

if cp -R $modSrc $modDest; then
    echo "Copied $modSrc to $modDest"
else
    echo "Copying $modSrc to $modDest failed"
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