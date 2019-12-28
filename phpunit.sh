#!/usr/bin/env bash
echo "criando schema"
php bin/console --env=test doctrine:schema:update --force
echo "rodando testes"
./vendor/bin/simple-phpunit