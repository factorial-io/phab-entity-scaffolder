
# Phab Entity Scaffolder


[![factorial-io](https://circleci.com/gh/factorial-io/phab-entity-scaffolder.svg?style=shield)](https://circleci.com/gh/factorial-io/phab-entity-scaffolder)

Port of entity-scaffolder for D8 using phabalicious for scaffolding files and configuration.

## Installation

As long as this project is not available at packagist.org, add this repo to your composer.json:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:factorial-io/phab-entity-scaffolder.git"
    }
  ]
}
```

Then require this project via composer as dev dependency:

```shell
composer require --dev factorial-io/phab-entity-scaffolder:dev-master
```

This should download the project and install it into vendor.

## Configuration

Add this to your yml-file which controls the scaffolding:

```yaml
questions: []
assets: []

plugins:
  - vendor/factorial-io/phab-entity-scaffolder/src/transformers

scaffold:
  # transform yaml files using the ImageStyleTransformer.
  - transform(imagestyles, image_styles, config/sync)
```

## Usage

Then you can scaffold the configuration via phabalicious:

```
phab scaffold <path-to-your-scaffold.yml>
```

### Contribute

Contribution are more than welcome. Please fork the repo, add a test-case and an
implementation and create a pull-request. Note that we are using **github-flow** as a
merge-strategy.
