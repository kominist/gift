"use strict"

require.config

  paths :
    jquery : "../vendor/jquery"
    backbone : "../vendor/backbone"
    underscore : "../vendor/underscore"
    marionette : "../vendor/marionette"
    validate : "../vendor/validate"
    mocha : "../vendor/mocha"
    chai : "../vendor/chai"
  
  shim :
    jquery :
      exports : "$"
    backbone :
      deps : ["underscore", "jquery"]
      exports : "Backbone"
    marionette :
      deps : ["backbone"]
      exports : "Marionette"
    validate :
      deps : ["underscore", "backbone"]
      exports : "Validate"
    mocha :
      exports : "Mocha"
    chai :
      deps : ["mocha"]
      exports : "Chai"

# Config mocha and chai
require [
  "marionette"
  "chai"
], ((
  Marionette,
  Chai
) ->
  mocha.setup("bdd")
  window.should = undefined
  window.should = Chai.should()
)

# Run tests
require [
  "spec/models/userModelSpec"
], ((
  UserModelSpec
) ->
  if window.mochaPhantomJS?
    mochaPhantomJS.run()
  else
    mocha.run()
)
