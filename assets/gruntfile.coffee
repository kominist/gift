module.exports = (grunt) ->
  grunt.initConfig
    pkg : grunt.file.readJSON("package.json")

    requirejs :
      dist :
        options :
          baseUrl : "dist/js/app"
          name : "app"
          out : "dist/js/app/build.js"
          optimize : "none"

    coffee :
      dist :
        files : [
          expand: true
          dest : "dist/js"
          cwd : "src/cs"
          src : "**/*.coffee"
          ext : ".js"
        ]
      test :
        options :
          bare : true
        files :
          "test/js/spec.js" : ["test/cs/*-spec.coffee"]

    coffeelint :
      app : ["gruntfile.coffee","src/cs/**/*.coffee", "test/cs/*-spec.coffee"]

    uglify :
      options :
        report : "min"
        preserveComments : no
      compile :
        files : [
          expand : true
          dest : "dist/js/vendor"
          cwd : "src/vendor/js"
          src : "*.js"
          ext : ".js"
        ]
    
    copy :
      dist :
        files :[
          expand : true
          cwd : "src/img/"
          src : ["**"]
          dest : "dist/images/"
        ]
      fonts :
        files : [
          expand : true
          cwd : "src/fonts/"
          src : ["**"]
          dest : "dist/fonts/"
        ]
    
    stylus :
      dist :
        files :
          "src/css/main.css" : "src/styl/main.styl"
    
    cssmin :
      dist :
        files :
          "dist/css/main.min.css" : [
            "src/css/lib/normalize.css",
            "src/css/lib/buttons.css",
            "src/css/lib/font-awesome.css",
            "src/css/main.css"
          ]

    watch :
      css :
        files : [
          "src/css/lib/*.css",
          "src/css/main.css",
          "src/styl/main.styl",
          "src/styl/**/*.styl"
        ]
        tasks : ["stylus", "cssmin"]
    
    clean : [
      "dist/js/app/"
    ]
    
  grunt.loadNpmTasks "grunt-contrib-coffee"
  grunt.loadNpmTasks "grunt-contrib-stylus"
  grunt.loadNpmTasks "grunt-contrib-watch"
  grunt.loadNpmTasks "grunt-contrib-copy"
  grunt.loadNpmTasks "grunt-contrib-cssmin"
  grunt.loadNpmTasks "grunt-coffeelint"
  grunt.loadNpmTasks "grunt-contrib-uglify"
  grunt.loadNpmTasks "grunt-contrib-requirejs"
  grunt.loadNpmTasks "grunt-contrib-clean"

  grunt.registerTask "default", [
    "clean"
    "coffeelint"
    "coffee:dist"
    "requirejs:dist"
    "copy:dist"
    "copy:fonts"
  ]

  grunt.registerTask "vendor", [
    "uglify:compile"
  ]

  grunt.registerTask "css", [
    "stylus:dist"
    "cssmin:dist"
  ]

  grunt.registerTask "test", [
    "coffeelint"
    "coffee:test"
  ]
