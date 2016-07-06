#!/usr/bin/env bash

#For Linux users -> if its not working please set your vagrant directory path
#	like: /home/USER_NAME/PROJECT_NAME/vagrant/vagrant_config
homesteadRoot=$PWD/vagrant_config

mkdir -p "$homesteadRoot"

cp -i src/stubs/Homestead.yaml "$homesteadRoot/Homestead.yaml"
cp -i src/stubs/after.sh "$homesteadRoot/after.sh"
cp -i src/stubs/aliases "$homesteadRoot/aliases"

echo "Homestead initialized!"
