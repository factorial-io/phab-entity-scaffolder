const glob = require('glob');

function cleanPath(path) {
  return '/' + path.replace('docs/', "").replace('README.md', "");
}

let introDocs = glob.sync('docs/intro/*.md').map(cleanPath).sort();
let entityDocs = glob.sync('docs/guide/entity/*.md').map(cleanPath).sort();
let fieldDocs = glob.sync('docs/guide/field/**/*.md').map(cleanPath).sort();
let imageDocs = glob.sync('docs/guide/image/*.md').map(cleanPath).sort();

module.exports = {
  base: "/phab-entity-scaffolder",
  title: "Phab Entity Scaffolder",
  description: 'Scaffold drupal entities and configuration with ease and fun!',
  theme: require.resolve("@factorial/vuepress-theme"),
  themeConfig: {
    repo: "factorial-io/phab-etity-scaffolder",
    editLinks: true,
    editLinkText: "Help us improve this page!",
    docsDir: "docs",
    nav: [
      { text: 'Intro', link: introDocs[0] },
      { text: 'Guide', link: entityDocs[0] },
    ],
    sidebarDepth: 1,
    sidebar: [
      {
        title: 'Intro',
        collapsable: false,
        children: introDocs
      },
      {
        title: 'Entity',
        collapsable: false,
        sidebarDepth: 2,
        children: entityDocs
      },
      {
        title: 'Fields',
        collapsable: false,
        sidebarDepth: 2,
        children: fieldDocs
      },
      {
        title: 'Image',
        collapsable: false,
        sidebarDepth: 2,
        children: imageDocs
      },
    ]
  },

  markdown: {
    // options for markdown-it-anchor
    anchor: { permalink: true },
    // options for markdown-it-toc
    toc: { includeLevel: [1, 2] },
    linkify: true,
    extendMarkdown: md => {
      // use more markdown-it plugins!
      md.use(require('markdown-it-footnote')),
      md.use(require('markdown-it-deflist'))
    }
  }
}
