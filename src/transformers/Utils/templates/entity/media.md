# Media

## Example Usage

### Minimal Example

```yaml
label: Image
id: image
source: image
source_field: image
```

### Detailed Example

An example with fields and description.

```yaml
label: Image
id: image
source: image
source_field: image
description: An image
fields:
  image:
    type: image
    label: Image
    allowed_extensions: "jpg png"
  caption:
    type: text_long
    label: Caption
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `source`        |   The media source  |  string | image/video/etc ||
| `source_field`        |   Name of the field which holds the actual media resource. Use the internal name |  string | should be contained in your `fields`-definition|

