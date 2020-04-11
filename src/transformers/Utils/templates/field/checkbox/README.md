# Checkbox

## Example Usage

```yaml{8-10}
label: Card
id: card
description: "A teaser item"
fields:
  # The key will be used to generate the field's machine name.
  # E.g. `has_border` will gnerate field with machine name `field_card_has_border`.
  has_border:
    type: checkbox
    label: Has border
    description: Check this checkbox, if you want to apply a border to the teaser card when it is displayed.
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `type`        |  Element Type             |  `checkbox` |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |

## Form widget

A single checkbox element.

## Display

Displays the Label if checked.