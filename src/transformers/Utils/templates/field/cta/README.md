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

## Display

Displays the Label which is a link.