# Radios

## Example Usage

```yaml{8-11}
label: Card
id: card
description: "A teaser item"
fields:
  # The key will be used to generate the field's machine name.
  # E.g. `display_options` will gnerate field with machine name `field_card_display_options`.
  display_options:
    type: radios
    label: Display Options
    description: Select display options.
    plugin_id: display_options
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `type`        |  Element Type             |  `radios` |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `plugin_id`   | Provides list of key-value pairs implemented as [List predefined options](https://github.com/Realityloop/list_predefined_options) | plugin id | List Predefined Option plugin |

## Form widget

A multiple radio element.

## Display

Displays the Labels of the selected items.