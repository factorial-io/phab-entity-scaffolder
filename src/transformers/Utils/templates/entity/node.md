# Nodes

## Example Usage

### Minimal Example

```yaml
label: Article
id: article
```

### Detailed Example

An example with fields and description.

```yaml
label: Article
id: article
description: An article
help: This content type is useful for editorial content. It includes a teaser-image, a teaser text and a rte.
fields:
  teaser_image:
    type: image
    label: teaser image
  teaser_text:
    type: text_long
    allowed_formats:
      - plain_text
    label: Teaser Text
    description: A teaser text
  body:
    type: text_long
    label: Article body
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `help`        |   help text  |  Text ||
| `new_revision`        |   create automatically new revision when saving content.|  Boolean | true/false, default is true |

