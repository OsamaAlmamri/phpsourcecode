#!/bin/bash
#
# check PHP code syntax error and standard with phpcs
# https://blog.csdn.net/xsgnzb/article/details/52222366?locationNum=4&fps=1
# https://blog.csdn.net/ljihe/article/details/80826071
# =================== how to use ====================
# cp ./build/pre-commit.sh ./.git/hooks/pre-commit
# chmod 777 ./.git/hooks/pre-commit
# git commit -h
# git commit -n -m 'pass hook' #bypass pre-commit and commit-msg hooks
# ==================== end ==========================

PROJECT=$(git rev-parse --show-toplevel)
cd $PROJECT
SFILES=$(git diff --cached --name-only --diff-filter=ACMR HEAD | grep \\.php$)

# Determine if a file list is passed
if [ "$#" -ne 0 ]
then
    exit 0
fi

echo "Checking PHP Lint..."

for FILE in $SFILES
do
    php -l -d display_errors=0 $FILE
    if [ $? != 0 ]
    then
        echo "Fix the php error before commit."
        exit 1
    fi
    FILES="$FILES $PROJECT/$FILE"
done

phpcsfixer_path=$(cd `dirname $0`; pwd)"/../../build/php-cs-fixer"
gulp_path=$(cd `dirname $0`; pwd)"/../../frontend/node_modules/.bin/gulp"
prettier_path=$(cd `dirname $0`; pwd)"/../../frontend/node_modules/.bin/prettier"

# format code style
if [ "$FILES" != "" ]
then
    echo "Running Code Sniffer..."

    isCheck=""

    for FILE in $SFILES
    do

        IGNORE_PATH=(
            'vendor'
        )

        for IGNORE_ITEM in ${IGNORE_PATH[@]}
        do
            if [[ ${FILE} =~ ${IGNORE_ITEM} ]]
            then
                echo "Ignore file of "${IGNORE_ITEM}
                continue 2
            fi 
        done

        #result=`~/.composer/vendor/bin/php-cs-fixer fix $FILE --config=.php_cs.dist`
        #result=`php-cs-fixer fix $FILE --config=.php_cs.dist`
        result=`php $phpcsfixer_path fix $FILE --config=.php_cs.dist`

        if [ "$result" != "" ]
        then
            echo $result
            isCheck=$result
            git add $FILE
        fi
    done

    if [ "$isCheck" != "" ]
    then
        echo "The file has been automatically formatted."
    fi
fi

# for js
jsfiles=$(git diff --cached --name-only --diff-filter=ACM "*.js" "*.jsx" "*.vue" "*.css" "*.less" | tr '\n' ' ')
[ -z "$jsfiles" ] && exit 0

# format iview
$gulp_path iview --gulpfile frontend/gulpfile.js

# Prettify all staged .js files
echo "$jsfiles" | xargs $prettier_path --config frontend/.prettierrc.js --ignore-path frontend/.prettierignore --write

# Add back the modified/prettified files to staging
echo "$jsfiles" | xargs git add

git update-index -g

exit $?
