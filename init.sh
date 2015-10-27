#!/usr/bin/env bash

gestaoRoot=~/.gestao

mkdir -p "$gestaoRoot"

cp -i src/stubs/Gestao.yaml "$gestaoRoot/Gestao.yaml"
cp -i src/stubs/after.sh "$gestaoRoot/after.sh"
cp -i src/stubs/aliases "$gestaoRoot/aliases"

echo "[ Gestao initialized! ]"
