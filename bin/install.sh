#!/bin/bash
set -e
SWETE_ROOT=$HOME/.swete
SWETE_BIN=$SWETE_ROOT/bin
if [ ! -d "$SWETE_ROOT" ]; then
	cd $HOME
	if [ -x "$(command -v git)" ]; then
		git clone https://github.com/shannah/swete .swete
	else
		echo "git was not found.  Using curl to download swete from github master"
		curl -fsSL https://github.com/shannah/swete/archive/master.zip > .swete-master.zip
		unzip .swete-master.zip
		rm .swete-master.zip
		mv .swete-master .swete
	fi
	cd .swete/bin
	sh setup.sh
fi
if [[ ":$PATH:" == *":$SWETE_BIN:"* ]]; then
	echo "$SWETE_BIN is already in your PATH"
else
	echo "Adding $SWETE_BIN to your path in $HOME/.bash_profile"
  	echo "export PATH=$SWETE_BIN:$PATH" >> $HOME/.bash_profile
fi

