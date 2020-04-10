# Select

## Example Usage

```yaml{8-12}
label: Card
id: card
description: "A teaser item"
fields:
  # The key will be used to generate the field's machine name.
  # E.g. `display_options` will gnerate field with machine name `field_card_display_options`.
  display_options:
    type: select
    label: Display Options
    description: Select display options.
    plugin_id: display_options
    cardinality: -1
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `type`        |  Element Type             |  `select` |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `plugin_id`   | Provides list of key-value pairs implemented as [List predefined options](https://github.com/Realityloop/list_predefined_options) | plugin id | List Predefined Option plugin |
| `cardinality` | Determines how many values to be captured. Use `-1` cardinality to capture unlimitted number of values. | integer | `>=1` / `-1` |

## Form widget

A single select element if cardinality is `1`. Otherwise uses multi-select element.

## Display

Displays the Labels of the selected item/s.