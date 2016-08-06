#!/bin/bash

BASEDIR=$(cd $(dirname $0) && pwd)

mecab $@ |\
  mecab -p -F '%ps\t%H\t%pw\t%pC\t%pc\t%m\t%F/[0,1,2,3]\n' \
           -E '%ps\t%H\t%pw\t%pC\t%pc\tEOS\n\n' $@
