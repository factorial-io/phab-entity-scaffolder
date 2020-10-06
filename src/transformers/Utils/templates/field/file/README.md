# File

A file field to attach a file to an entity. For media use media entities and entity_references.

## Example Usage

```yaml
label: Card
id: card
description: "A teaser item"
fields:
  # The key will be used to generate the field's machine name.
  # E.g. `attachements` will gnerate field with machine name `field_card_attachements`.
  attachements:
    type: file
    label: Attachments
    description: A list of attachements a user can download.
    has_description_field: true # Use a dedicated description field for the file.
    file_extensions: "pdf doc pptx"
    file_directory: "attachements/[date:custom:Y]-[date:custom:m]"
```

## Properties

| Property      | Description    | Value      | Limitations |
| ---           | ---            | ---        | ---         |
| `type`        |  Element Type             |  `select` |
| `label`       |  Label to be used for the field             |  Text | 255 chars |
| `description` |  Description shown to the editors, next to the form item used to capture the value of this field               |   Text | 255 chars |
| `cardinality` | Determines how many values to be captured. Use `-1` cardinality to capture unlimitted number of values. | integer | `>=1` / `-1` |
| `has_description_field` | When uploading a file, the user can also enter a description | boolean | |
| `file_extensions` | A space separated lists of file extensions which are allowed for upload| string | |
| `file_directory` | a subfolder where files should be stored. Can include global tokens.| string | no trailing or leading slash|

