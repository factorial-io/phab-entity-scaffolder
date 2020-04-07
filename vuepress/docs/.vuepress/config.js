const glob = require('glob');

let markdownFiles = glob.sync('docs/guide/**/*.md').map(function(path) {
  return '/' + path.replace('docs/', "").replace('README.md', "");
});
markdownFiles.sort();
module.exports = {
  title: "Phab entity scaffolder",
  description: '',
  themeConfig: {
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: markdownFiles[0] },
    ],
    sidebar: markdownFiles
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
