const productionWebpackConfig = require('./webpack-config/production.js');
const developmentWebpackConfig = require('./webpack-config/development.js');

module.exports = function(grunt) {
  grunt.initConfig({
    cssmin: {
      target: {
        files: [{
          expand: true,
          cwd: 'media/com_eventcalendar/css',
          src: ['*.css', '!*.min.css'],
          dest: 'media/com_eventcalendar/css',
          ext: '.min.css'
        }]
      }
    },
    webpack: {
      production: productionWebpackConfig,
      development: developmentWebpackConfig,
    },
    watch: {
      css: {
        files: [
          'media/com_eventcalendar/css/*.css',
          '!media/com_eventcalendar/css/*.min.css'
        ],
        tasks: [ 'cssmin' ]
      },
      webpack: {
        files: [
          'media/com_eventcalendar/src/js/*.js'
        ],
        tasks: [ 'webpack:production', 'webpack:development' ]
      }
    }
  })

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-webpack');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('default', [ 'watch' ]);
  grunt.registerTask('build', [ 'cssmin', 'webpack:production' ]);
}