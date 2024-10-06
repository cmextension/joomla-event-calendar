const { merge } = require('webpack-merge');
const commonConfig = require('./common');

const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');

const rootDir = path.resolve(__dirname, '..');
const destDir = path.resolve(rootDir, 'media/com_eventcalendar/js');

module.exports = merge(commonConfig, {
    mode: 'production',
    output: {
      filename: '[name].min.js',
      path: destDir,
    },
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
})