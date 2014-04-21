(function() {
  "use strict";
  require.config({
    paths: {
      jquery: "../vendor/jquery",
      backbone: "../vendor/backbone",
      underscore: "../vendor/underscore",
      marionette: "../vendor/marionette",
      validate: "../vendor/validate",
      mocha: "../vendor/mocha",
      chai: "../vendor/chai"
    },
    shim: {
      jquery: {
        exports: "$"
      },
      backbone: {
        deps: ["underscore", "jquery"],
        exports: "Backbone"
      },
      marionette: {
        deps: ["backbone"],
        exports: "Marionette"
      },
      validate: {
        deps: ["underscore", "backbone"],
        exports: "Validate"
      },
      mocha: {
        exports: "Mocha"
      },
      chai: {
        deps: ["mocha"],
        exports: "Chai"
      }
    }
  });

  require(["marionette", "chai"], (function(Marionette, Chai) {
    mocha.setup("bdd");
    window.should = void 0;
    return window.should = Chai.should();
  }));

  require(["spec/models/userModelSpec"], (function(UserModelSpec) {
    if (window.mochaPhantomJS != null) {
      return mochaPhantomJS.run();
    } else {
      return mocha.run();
    }
  }));

}).call(this);
