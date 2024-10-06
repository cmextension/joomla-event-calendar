const path = require('path');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

const rootDir = path.resolve(__dirname, '..');
const srcDir = path.resolve(rootDir, 'media/com_eventcalendar/src/js');

module.exports = {
  entry: {
    'admin-calendar': path.resolve(srcDir, 'admin-calendar.js'),
    'calendar': path.resolve(srcDir, 'calendar.js'),
  },
  plugins: [
    // To strip all locales except "en".
    new MomentLocalesPlugin(),
  ]
};