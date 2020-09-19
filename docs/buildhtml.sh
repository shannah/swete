#!/bin/bash
set -e
asciidoctor swete-manual.adoc
cp swete-manual.html index.html