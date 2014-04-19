require.config
  baseUrl: "/js/"
  paths :
    #jquery : "dist/js/vendor/jquery"
    #backbone : "dist/js/vendor/backbone"
    #underscore : "dist/js/vendor/underscore"
    #marionette : "dist/js/vendor/marionette"
    #validate : "dist/js/vendor/validate"
    mocha : "vendor/mocha"
    chai : "vendor/chai"
    sinon : "vendor/sinon"
    spec : "js/specs/"
  shim :
    #jquery :
      #exports : "$"
    #underscore :
      #exports : "_"
    #backbone :
      #deps : ["underscore", "jquery"]
      #exports: "Backbone"
    #marionette :
      #deps : ["marionette"]
      #exports : "Marionette"
    #validate :
      #deps : ["underscore", "backbone"]
      #exports : "Validate"
    mocha :
      exports : "Mocha"
    chai :
      exports : "Chai"
    sinon :
      exports : "Sinon"

  require [
    "mocha"
    "chai"
    "sinon"
  ], (
    Mocha
    Chai
    Sinon
  ) ->
    @should = Chai.should
    Mocha.setup(
      ui : bdd
    )

    specs = []
    specs.push "spec/models/userModelSpec"

  require specs, ->
    Mocha.run()

