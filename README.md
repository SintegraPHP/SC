# Sintegra SC

[![Travis](https://travis-ci.org/SintegraPHP/SC.svg?branch=1.0)](https://travis-ci.org/SintegraPHP/SC)
[![Latest Stable Version](https://poser.pugx.org/sintegra-php/sc/v/stable)](https://packagist.org/packages/sintegra-php/sc) 
[![Total Downloads](https://poser.pugx.org/sintegra-php/sc/downloads)](https://packagist.org/packages/sintegra-php/sc)
[![Latest Unstable Version](https://poser.pugx.org/sintegra-php/sc/v/unstable)](https://packagist.org/packages/sintegra-php/sc)
[![License](https://poser.pugx.org/sintegra-php/sc/license)](http://opensource.org/licenses/MIT)

Consulte gratuitamente CNPJ no site do Sintegra/SC

### Como utilizar

Adicione a library

```sh
$ composer require sintegra-php/sc
```

Adicione o autoload.php do composer no seu arquivo PHP.

```php
require_once 'vendor/autoload.php';  
```

Primeiro chame o método `getParams()` para retornar os dados necessários para enviar no método `consulta()` 

```php
$params = SintegraPHP\SC\SintegraSC::getParams();
```

Agora basta chamar o método `consulta()`

```php
$dadosEmpresa = SintegraPHP\SC\SintegraSC::consulta(
    '83646984003710',
    'INFORME_AS_LETRAS_DO_CAPTCHA',
    $params['viewstate'],
    $params['eventvalidation'],
    $params['viewstategenerator']
);
```

### License

The MIT License (MIT)
