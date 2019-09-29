#!/bin/sh
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
set -e
if [ ! -d "../swete-admin/templates_c" ]; then
	cd ../swete-admin
	mkdir templates_c
	
fi
if [ ! -d "../swete-admin/xataface" ]; then
    cd ../swete-admin
    echo "Downloading Xataface..."
    curl -Ls https://github.com/shannah/xataface/archive/master.zip > xataface.zip
    echo "Extracting Xataface..."
    unzip -qq xataface.zip
	rm xataface.zip
    mv xataface-master xataface
    cd ../bin
fi
if [ ! -d "../swete-admin/app" ]; then
    cd ../swete-admin
    echo "Downloading SweteApp..."
    curl -Ls https://github.com/shannah/swete-app/raw/master/SweteApp.zip > app.zip
    echo "Extracting SweteApp..."
    mkdir app
	mv app.zip app/app.zip
	cd app
	unzip -qq app.zip
	rm app.zip
    cd ../../bin
fi
if [ ! -f "../swete-admin/modules/ckeditor/ckeditor.php" ]; then
	if [ -d "../swete-admin/modules/ckeditor" ]; then
		rm -rf "../swete-admin/modules/ckeditor"
	fi
    cd ../swete-admin/modules
    echo "Downloading ckeditor module..."
    curl -Ls https://github.com/shannah/xataface-module-ckeditor/archive/master.zip > ckeditor.zip
    echo "Extracting ckeditor module..."
    unzip -qq ckeditor.zip
    mv xataface-module-ckeditor-master ckeditor
	rm ckeditor.zip
    cd ../../bin
fi
if [ ! -f "../swete-admin/modules/uitk/uitk.php" ]; then
	if [ -d "../swete-admin/modules/uitk" ]; then
		rm -rf "../swete-admin/modules/uitk"
	fi
    cd ../swete-admin/modules
    echo "Downloading uitk module..."
    curl -Ls https://github.com/shannah/xataface-module-uitk/archive/master.zip > uitk.zip
    echo "Extracting uitk module..."
    unzip -qq uitk.zip
    mv xataface-module-uitk-master uitk
	rm uitk.zip
    cd ../../bin
fi
if [ ! -f "../swete-admin/modules/excel/excel.php" ]; then
	if [ -d "../swete-admin/modules/excel" ]; then
		rm -rf "../swete-admin/modules/excel"
	fi
    cd ../swete-admin/modules
    echo "Downloading excel module..."
    curl -Ls https://github.com/shannah/xataface-module-excel/archive/master.zip > excel.zip
    echo "Extracting excel module..."
    unzip -qq excel.zip
    mv xataface-module-excel-master excel
	rm excel.zip
    cd ../../bin
fi


