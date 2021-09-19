// import critical from 'critical';
const critical = require('critical');
// const log  = require('log');
// const logSymbols  = require('logSymbols');


let criticalCssConfig = {
  concurrency: 5, //this is the number of tasks that run concurrently, large numbers could lead to errors / memory leaks
  baseUrl: 'http://127.0.0.1:5500/',
  suffix: '_critical.min.css',
  criticalWidth: 1920,
  criticalHeight: 1440,
  criticalIgnore: [
    '@font-face'
  ],
  pages: [
    {
      url: '/',
      template: 'home' // the final file name will be nameoftemplate+suffix, for example home_critical.min.css
    },
    {
      url: '/about-us/',
      template: 'page'
    },
  ]
};

criticalCssConfig.pages.forEach(page => {
  const url = criticalCssConfig.baseUrl + page.url + '?criticalcss=false';
  // log(logSymbols.info, `Generating critical CSS for template ${page.template} with URL ${url}`);
  // critical.generate returns a Promise.
  critical.generate({
    // Inline the generated critical-path CSS
    // - true generates HTML
    // - false generates CSS
    inline: false,

    // Your base directory
    base: './',

    // HTML source

    // HTML source file
    src: url,

    // Your CSS Files (optional)
    // css: ['dist/styles/main.css'],

    // Viewport width
    width: criticalCssConfig.criticalWidth,

    // Viewport height
    height: criticalCssConfig.criticalHeight,

    // Output results to file
    target: {
      css: "./public/css/" + page.template + criticalCssConfig.suffix,

      // html: 'index-critical.html',
      // uncritical: 'uncritical.css',
    },

    // Minify critical-path CSS when inlining
    minify: false,

    // Extract inlined styles from referenced stylesheets
    // extract: true,

    // ignore CSS rules
    ignore: {
      atrule: ['@font-face'],
      // rule: [/some-regexp/],
      // decl: (node, value) => /big-image\.png/.test(value),
    },
  });
});