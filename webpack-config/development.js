const { merge } = require('webpack-merge');
const commonConfig = require('./common');

const path = require('path');

const rootDir = path.resolve(__dirname, '..');
const destDir = path.resolve(rootDir, 'media/com_eventcalendar/js');

module.exports = merge(commonConfig, {
  mode: 'production',
  output: {
    filename: '[name].js',
    path: destDir,
  },
  optimization: {
    minimize: false
  }
})