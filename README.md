# MITIE PHP

[MITIE](https://github.com/mit-nlp/MITIE) - named-entity recognition - for PHP

- Finds people, organizations, and locations in text

[![Build Status](https://github.com/ankane/mitie-php/workflows/build/badge.svg?branch=master)](https://github.com/ankane/mitie-php/actions)

## Installation

Run:

```sh
composer require ankane/mitie
```

And download the pre-trained models for your language:

- [English](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2.tar.bz2)
- [Spanish](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2-Spanish.zip)
- [German](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2-German.tar.bz2)

## Named Entity Recognition

Load an NER model

```php
$model = new Mitie\NER('ner_model.dat');
```

Create a document

```php
$doc = $model->doc('Nat works at GitHub in San Francisco');
```

Get entities

```php
$doc->entities();
```

This returns

```php
[
    ['text' => 'Nat',           'tag' => 'PERSON',       'score' => 0.3112371212688382, 'offset' => 0],
    ['text' => 'GitHub',        'tag' => 'ORGANIZATION', 'score' => 0.5660115198329334, 'offset' => 13],
    ['text' => 'San Francisco', 'tag' => 'LOCATION',     'score' => 1.3890524313885309, 'offset' => 23]
]
```

Get tokens

```php
$doc->tokens();
```

Get tokens and their offset

```php
$doc->tokensWithOffset();
```

Get all tags for a model

```php
$model->tags();
```

## History

View the [changelog](CHANGELOG.md)

## Contributing

Everyone is encouraged to help improve this project. Here are a few ways you can help:

- [Report bugs](https://github.com/ankane/mitie-php/issues)
- Fix bugs and [submit pull requests](https://github.com/ankane/mitie-php/pulls)
- Write, clarify, or fix documentation
- Suggest or add new features

To get started with development:

```sh
git clone https://github.com/ankane/mitie-php.git
cd mitie-php
composer install
composer test
```
