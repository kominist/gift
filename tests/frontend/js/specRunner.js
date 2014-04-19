(function() {
  require.config({
    baseUrl: "/assets/js",
    urlArgs: "cb=" + Math.random(),
    paths: {
      jquery: "dist/js/vendor/jquery",
      backbone: "dist/js/vendor/backbone",
      underscore: "dist/js/vendor/underscore",
      marionette: "dist/js/vendor/marionette",
      validate: "dist/js/vendor/validate",
      mocha: "tests/frontend/vendor/mocha",
      chai: "tests/frontend/vendor/chai",
      sinon: "tests/frontend/vendor/sinon",
      spec: "tests/frontend/js/specs/"
    },
    shim: {
      jquery: {
        exports: "$"
      },
      underscore: {
        exports: "_"
      },
      backbone: {
        deps: ["underscore", "jquery"],
        exports: "Backbone"
      },
      marionette: {
        deps: ["marionette"],
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
        exports: "Chai"
      },
      sinon: {
        exports: "Sinon"
      }
    }
  }, require(["underscore", "jquery", "mocha", "chai", "sinon"], function(_, $, Mocha, Chai, Sinon) {
    var specs;
    this.should = Chai.should;
    Mocha.setup({
      ui: bdd
    });
    specs = [];
    return specs.push("spec/models/userModelSpec");
  }), require(specs, function() {
    return $(function() {
      return Mocha.run();
    });
  }));

}).call(this);
