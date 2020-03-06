
## Concept

Since the Image style concept was not intuitive for Frontend Developers, Responsive image abstract away from the concept. Based on the brekpoints and image sizes defined, scaffolder
figures out what image styles and effect to use. 

Since the image style names are gnerated based on the height and width parameter, they are re-used if multiple defintions exists.

## TODO

- Right now, the breakpoint group and its definitions are not configurations in Drupal. Which means, we have to either use the one provided by module or theme. And hence is not scaffolded automatically. Got to find a work around over this limitations.

- Mapping of fields to variable names have not been done yet. This means, frontend template have to use drupal field names for accessing data.