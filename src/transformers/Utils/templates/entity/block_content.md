# Block Content n

## Example Usage


### Minimal Example

```yaml
label: Card
id: card
```

### Detailed Exampled

An example with fields and description.

```yaml
label: Card
id: card
description: A teaser item
fields:
  headline:
    type: text
    label: Headline
    description: A striking headline to grab attention
  subline:
    type: text
    label: Sub title
    description: A optinal sub title
  image:
    type: image
    label: Image
  cta:
    type: cta
    label: CTA
    description: A call to action
  body:
    type: textarea
    label: Teaser body
    description: Enter just enough text to make readers itching to know more
```

::: details For Frontend

**Planned**
- To do some pre-processing of entities to make rendering of fields easier, namely,
    - Friendlier field names : `cta` instead of `field_block_content_card_cta`
    - Allow use of for loop to render fields, so you can do `{% for image in images %}`

:::

::: details For Backend

If you decide to manage the entities created by scaffolder through Drupal UI then
delete the corresponding config file used to generate the entity. 

Thereby, when you run scaffolder next time, it won't override the Drupal configuration files for the entity.

:::


## References


- [Entity Types in Drupal 8](https://www.drupal.org/docs/8/api/entity-api/entity-types)
- Frontend : [Madness - Ways to render things inside Entity Templates](https://gist.github.com/raphaellarrinaga/c1d71f69873c967ff74f8ec09cbdf9e1)
- Frontend : [Madness - source of sanity](https://blog.usejournal.com/getting-drupal-8-field-values-in-twig-22b80cb609bd)
