#!/usr/bin/env bash
# All tables of database will be recreated
echo -e "\e[32mEnter Extended Table Name: \e[39m"

read tableName

echo -e "\e[32mSelect Module: "
dirs=(Modules/*)
echo -e "\e[39m"
num=1
array=()
for dir in "${dirs[@]}"

do
    string_to_replace="Modules/"
    string_to_replace_with=""
    module_name="${dir/$string_to_replace/$string_to_replace_with}"
    echo $(echo $num) $(echo ". ") $(echo $module_name)
    array+=($module_name)
    num=$((num + 1))


done
echo -e "\e[39m"
read module_index
module_index=$((module_index - 1))
php artisan module:use "${array[$module_index]}"
echo -e "\e[32mLogin in to MYSQL as root: \e[39m"
mysql -u root -p << EOF
use ems;
Delete from migrations where migration LIKE '%$tableName%'
EOF
php artisan module:make-migration "$tableName" "${array[$module_index]}"
php artisan migrate



