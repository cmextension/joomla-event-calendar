const webpackConfig = require('./webpack-config/production.js');

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
      myConfig: webpackConfig,
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
          'media/com_eventcalendar/js/*.js',
          '!media/com_eventcalendar/js/*.min.js'
        ],
        tasks: [ 'webpack' ]
      }
    }
  })

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-webpack');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('default', [ 'cssmin', 'watch' ]);
}