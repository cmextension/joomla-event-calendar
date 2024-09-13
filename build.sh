#!/bin/bash

buildDir="./build"
packageDir="$buildDir/com_eventcalendar"
packageFile="com_eventcalendar.zip"

if [ ! -d $buildDir ]; then
    echo "buildDir doesn't exist"

    if mkdir -p $buildDir; then
        echo "Created $buildDir successfully"
    else
        echo "Creating $buildDir failed"
        echo "Exit..."
        exit 1
    fi
else
    echo "$buildDir already exists"

    if rm -rf $buildDir; then
        echo "Deleted $buildDir successfully"
    else
        echo "Deleting $buildDir failed"
        echo "Exit..."
        exit 1
    fi

    if mkdir -p $buildDir; then
        echo "Re-created $buildDir successfully"
    else
        echo "Creating $buildDir failed"
        echo "Exit..."
        exit 1
    fi
fi

if mkdir -p $packageDir; then
    echo "Re-created $packageDir successfully"
else
    echo "Creating $packageDir failed"
    echo "Exit..."
    exit 1
fi

adminSrc="./administrator/components/com_eventcalendar"
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

siteSrc="./components/com_eventcalendar"
siteDest="$packageDir/site"

if cp -R $siteSrc $siteDest; then
    echo "Copied $siteSrc to $siteDest"
else
    echo "Copying $siteSrc to $siteDest failed"
    echo "Exit..."
    exit 1
fi

mediaSrc="./media/com_eventcalendar"
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