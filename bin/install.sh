#!/bin/bash
set -e
SWETE_ROOT=$HOME/.swete
SWETE_BIN=$SWETE_ROOT/bin
if [ ! -d "$SWETE_ROOT" ]; then
	cd $HOME
	git clone https://github.com/shannah/swete .swete
	cd .swete/bin
	sh setup.sh
fi
if [[ ":$PATH:" == *":$SWETE_BIN:"* ]]; then
	echo "$SWETE_BIN is already in your PATH"
else
	echo "Adding $SWETE_BIN to your path in $HOME/.bash_profile"
  	echo "export PATH=$SWETE_BIN:$PATH" >> $HOME/.bash_profile
fi

