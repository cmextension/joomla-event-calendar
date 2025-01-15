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

packageDir="$buildDir/com_eventcalendar"
packageFile="$buildDir/com_eventcalendar_$version.zip"

if mkdir -p $packageDir; then
    echo "Re-created $packageDir successfully"
else
    echo "Creating $packageDir failed"
    echo "Exit..."
    exit 1
fi

adminSrc="$PWD/administrator/components/com_eventcalendar"
adminDest="$packageDir/admin"

if cp -R $adminSrc $adminDest; then
    echo "Copied $adminSrc to $adminDest"
else
    echo "Copying $adminSrc to $adminDest failed"
    echo "Exit..."
    exit 1
fi

xmlFile="$adminDest/eventcalendar.xml"

if mv $xmlFile $packageDir; then
    echo "Moved $xmlFile to $packageDir"
else
    echo "Moving $xmlFile to $packageDir failed"
    echo "Exit..."
    exit 1
fi

siteSrc="$PWD/components/com_eventcalendar"
siteDest="$packageDir/site"

if cp -R $siteSrc $siteDest; then
    echo "Copied $siteSrc to $siteDest"
else
    echo "Copying $siteSrc to $siteDest failed"
    echo "Exit..."
    exit 1
fi

mediaSrc="$PWD/media/com_eventcalendar"
mediaDest="$packageDir/media"

if cp -R $mediaSrc $mediaDest; then
    echo "Copied $mediaSrc to $mediaDest"
else
    echo "Copying $mediaSrc to $mediaDest failed"
    echo "Exit..."
    exit 1
fi

cd $buildDir

if zip -rq $packageFile *; then
  echo "$packageFile was created successfully"
else
  echo "Creating $packageFile failed"
fi

cd ..

if rm -rf $packageDir; then
    echo "Deleted $packageDir successfully"
else
    echo "Deleting $packageDir failed"
fi

exit 0