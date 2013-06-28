#!/bin/sh
DIR="$(dirname $0)"
OUTDIR="$(dirname $1)"
XALAN_PATH=/Users/shannah/Documents/Shared/xataface/xalan-j_2_7_1/xalan.sh
"$XALAN_PATH" -IN "$DIR/swete-manual.xml" -OUT $1 -XSL "$DIR/html/manual.html.xsl"
mkdir "$OUTDIR/images" "$OUTDIR/js"
cp -r "$DIR/images/"* "$OUTDIR/images/"
cp -r "$DIR/html/js/"* "$OUTDIR/js/"
cp -r "$DIR/html/images/"* "$OUTDIR/images/"
cp "$DIR/html/style.css" "$OUTDIR/style.css"
