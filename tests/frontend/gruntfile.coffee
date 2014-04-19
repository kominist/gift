module.exports = (grunt) ->
  grunt.initConfig
    pkg : grunt.file.readJSON("package.json")

    coffee :
      test :
        files : [
          expand: true
          dest : "js"
          cwd : "cs"
          src : "**/*.coffee"
          ext : ".js"
        ]

    coffeelint :
      app : ["gruntfile.coffee","cs/**/*.coffee", "cs/spec/*-spec.coffee"]

  grunt.loadNpmTasks "grunt-contrib-coffee"
  grunt.loadNpmTasks "grunt-coffeelint"

  grunt.registerTask "default", [
    "coffeelint"
    "coffee:test"
  ]
