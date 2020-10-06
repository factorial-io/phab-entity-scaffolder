# CTA

CTA - Call to Action

## Example Usage

```yaml{8-10}
label: Card
id: card
description: "A teaser item"
fields:
  # The key will be used to generate the field's machine name.
  # E.g. `read_more` will gnerate field with machine name `field_card_read_more`.
  read_more:
    type: cta
    label: Call to Action
    description: Enter a catchy label and a link for call to action.
    target: _blank
    rel: nofollow
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `type`        |  Element Type             |  `select` |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `cardinality` | Determines how many values to be captured. Use `-1` cardinality to capture unlimitted number of values. | integer | `>=1` / `-1` |

## Form widget

A combination of text field to enter title and another field to enter the link url.

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `title`       | Allow user to enter a title for the url | Boolean | 1/0 |
| `link_type`   | What types of liks are allowed | `EXTERNAL_ONLY` / `INTERNAL_ONLY` / `EXTERNAL_AND_INTERNAL` | |

## Field formatter

Displays the Label which is a link.

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `rel` | Relation to use for the field formatter | string
| 
|`target` | Target to use for the field formatter | string | |
|`trim_length` | trim_length to use for the field formatter | integer | |
