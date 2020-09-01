#!/bin/bash

curl --location --request POST 'https://veiculos.fipe.org.br/api/veiculos/ConsultarMarcas' \
--header 'Content-Type: application/json' \
--header 'Host: veiculos.fipe.org.br' \
--header 'Referer: http://veiculos.fipe.org.br' \
--header 'Cookie: __cfduid=d1af69a637f68dd3e636bfdd263fa7b5a1598897767; ROUTEID=.5' \
--data-raw '{
  "codigoTabelaReferencia": 231,
  "codigoTipoVeiculo": 1
}'