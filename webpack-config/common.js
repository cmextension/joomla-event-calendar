const path = require('path');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const rootDir = path.resolve(__dirname, '..');
const baseDir = path.resolve(rootDir, 'media/com_eventcalendar/js');

module.exports = {
  mode: 'development',
  entry: path.resolve(baseDir, 'admin-calendar.js'),
  output: {
    filename: 'admin-calendar.min.js',
    path: baseDir,
  },
  plugins: [
    // To strip all locales except "en".
    new MomentLocalesPlugin(),
  ],
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        terserOptions: {
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }),
    ],
  },
};