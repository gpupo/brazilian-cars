# brazilian-cars

Acesso a lista de automóveis comercializados no Brasil

[![Paypal Donations](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK6F2WRKG7GNN&item_name=brazilian-cars)

[![Build Status](https://secure.travis-ci.org/gpupo/brazilian-cars.png?branch=master)](http://travis-ci.org/gpupo/brazilian-cars)


## Requisitos para uso

- PHP >= *7.3*
- [Composer Dependency Manager](http://getcomposer.org)
- [PHP Curl extension](http://php.net/manual/en/intro.curl.php)
- PHP Soap extension


Este componente **não é uma aplicação Stand Alone** e seu objetivo é ser utilizado como biblioteca.
Sua implantação deve ser feita por desenvolvedores experientes.

**Isto não é um Plugin!**

As opções que funcionam no modo de comando apenas servem para depuração em modo de
desenvolvimento.

A documentação mais importante está nos testes unitários. Se você não consegue ler os testes unitários, eu recomendo que não utilize esta biblioteca.


## Direitos autorais e de licença

This project is licensed under the terms of the MIT license.

Este componente está sob a [licença MIT](https://github.com/gpupo/common-sdk/blob/master/LICENSE)

Para a informação dos direitos autorais e de licença você deve ler o arquivo
de [licença](https://github.com/gpupo/common-sdk/blob/master/LICENSE) que é distribuído com este código-fonte.

### Resumo da licença

Exigido:

- Aviso de licença e direitos autorais

Permitido:

- Uso comercial
- Modificação
- Distribuição
- Sublicenciamento

Proibido:

- Responsabilidade Assegurada

## Instalação

Adicione o pacote ``brazilian-cars`` ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/brazilian-cars


Acesso ao componente:

```php

use Gpupo\BrazilianCars\Factory;

$service = Factory::getInstance()->getClient();


```