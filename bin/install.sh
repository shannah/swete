#!/bin/sh
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
set -e
if [ ! -d "../swete-admin/xataface" ]; then
    cd ../swete-admin
    echo "Downloading Xataface..."
    curl -Ls https://github.com/shannah/xataface/archive/master.zip > xataface.zip
    echo "Extracting Xataface..."
    unzip -qq xataface.zip
    mv xataface-master xataface
    cd ../bin
fi


