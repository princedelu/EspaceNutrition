(function(){
"use strict";

module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
	clean: ['js/<%= pkg.name %>.min.js', 'js/<%= pkg.name %>.js','css/<%= pkg.name %>.min.css', 'css/<%= pkg.name %>.css'],
    concat: {
      options: {
        separator: ''
      },
      js: {
        src: [  'src/js/**/*.js'],
        dest: 'js/<%= pkg.name %>.js'
      },
      css: {
        src: [  'src/css/*.css'],
        dest: 'css/<%= pkg.name %>.css'
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n',
        report: 'min',
        mangle: false
      },
      js: {
        files: {
          'js/<%= pkg.name %>.min.js': ['<%= concat.js.dest %>']
        }
      }
    },
    jshint: {
      files: ['Gruntfile.js', 'src/js/**/*.js'],
      options: {
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          module: true,
          document: true
        }
      }
    },
    watch: {
      files: ['<%= jshint.files %>'],
      tasks: ['jshint', 'qunit']
    },
    complexity: {
        generic: {
            src: ['Gruntfile.js', 'src/js/**/*.js'],
            options: {
                breakOnErrors: false,
                jsLintXML: 'report.xml',         // create XML JSLint-like report
                checkstyleXML: 'checkstyle.xml', // create checkstyle report
                errorsOnly: false,               // show only maintainability errors
                cyclomatic: [3, 7, 12],          // or optionally a single value, like 3
                halstead: [8, 13, 20],           // or optionally a single value, like 8
                maintainability: 100,
                hideComplexFunctions: true      // only display maintainability
            }
        }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-complexity');

  grunt.registerTask('test', ['jshint']);

  grunt.registerTask('default', ['clean','jshint', 'concat', 'uglify','complexity']);

};

})();
