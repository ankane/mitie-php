# MITIE PHP

[MITIE](https://github.com/mit-nlp/MITIE) - named-entity recognition, binary relation detection, and text categorization - for PHP

- Finds people, organizations, and locations in text
- Detects relationships between entities, like `PERSON` was born in `LOCATION`

[![Build Status](https://github.com/ankane/mitie-php/workflows/build/badge.svg?branch=master)](https://github.com/ankane/mitie-php/actions)

## Installation

Run:

```sh
composer require ankane/mitie
```

Download the shared library:

```sh
composer exec -- php -r "require 'vendor/autoload.php'; Mitie\Vendor::check(true);"
```

And download the pre-trained models for your language:

- [English](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2.tar.bz2)
- [Spanish](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2-Spanish.zip)
- [German](https://github.com/mit-nlp/MITIE/releases/download/v0.4/MITIE-models-v0.2-German.tar.bz2)

## Getting Started

- [Named Entity Recognition](#named-entity-recognition)
- [Binary Relation Detection](#binary-relation-detection)
- [Text Categorization](#text-categorization)

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

### Training

Load an NER model into a trainer

```php
$trainer = new Mitie\NERTrainer('total_word_feature_extractor.dat');
```

Create training instances

```php
$tokens = ['You', 'can', 'do', 'machine', 'learning', 'in', 'PHP', '!'];
$instance = new Mitie\NERTrainingInstance($tokens);
$instance->addEntity(3, 4, 'topic');    // machine learning
$instance->addEntity(6, 6, 'language'); // PHP
```

Add the training instances to the trainer

```php
$trainer->add($instance);
```

Train the model

```php
$model = $trainer->train();
```

Save the model

```php
$model->saveToDisk('ner_model.dat');
```

## Binary Relation Detection

Detect relationships betweens two entities, like:

- `PERSON` was born in `LOCATION`
- `ORGANIZATION` was founded in `LOCATION`
- `FILM` was directed by `PERSON`

There are 21 detectors for English. You can find them in the `binary_relations` directory in the model download.

Load a detector

```php
$detector = new Mitie\BinaryRelationDetector('rel_classifier_organization.organization.place_founded.svm');
```

And create a document

```php
$doc = $model->doc('Shopify was founded in Ottawa');
```

Get relations

```php
$detector->relations($doc);
```

This returns

```php
[['first' => 'Shopify', 'second' => 'Ottawa', 'score' => 0.17649169745814464]]
```

### Training

Load an NER model into a trainer

```php
$trainer = new Mitie\BinaryRelationTrainer($model);
```

Add positive and negative examples to the trainer

```php
$tokens = ['Shopify', 'was', 'founded', 'in', 'Ottawa'];
$trainer->addPositiveBinaryRelation($tokens, [0, 0], [4, 4]);
$trainer->addNegativeBinaryRelation($tokens, [4, 4], [0, 0]);
```

Train the detector

```php
$detector = $trainer->train();
```

Save the detector

```php
$detector->saveToDisk('binary_relation_detector.svm');
```

## Text Categorization

Load a model into a trainer

```php
$trainer = new Mitie\TextCategorizerTrainer('total_word_feature_extractor.dat');
```

Add labeled text to the trainer

```php
$trainer->add('This is super cool', 'positive');
```

Train the model

```php
$model = $trainer->train();
```

Save the model

```php
$model->saveToDisk('text_categorization_model.dat');
```

Load a saved model

```php
$model = new Mitie\TextCategorizer('text_categorization_model.dat');
```

Categorize text

```php
$model->categorize('What a super nice day');
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
