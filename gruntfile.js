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
    uglify: {
      files: {
        cwd: 'media/com_eventcalendar/js',
        src: ['*.js','!*.min.js'],
        dest: 'media/com_eventcalendar/js',
        expand: true,
        ext: '.min.js'
      },
    },
    watch: {
      css: {
        files: [
          'media/com_eventcalendar/css/*.css',
          '!media/com_eventcalendar/css/*.min.css'
        ],
        tasks: [ 'cssmin' ]
      },
      js: {
        files: [
          'media/com_eventcalendar/js/*.js',
          '!media/com_eventcalendar/js/*.min.js'
        ],
        tasks: [ 'uglify' ]
      }
    }
  })

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('default', [ 'cssmin', 'uglify' ]);
}