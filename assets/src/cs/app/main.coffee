"use strict"

require.config
  deps : ["app"]

  paths :
    jquery : "../vendor/jquery"
    backbone : "../vendor/backbone"
    underscore : "../vendor/underscore"
    marionette : "../vendor/marionette"
    validate : "../vendor/validate"
  
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

require [
  "marionette",
  "router",
  "ui/loginRegister"
], ((
  Marionette,
  App,
  LoginRegister
) ->
  $ ->
    App.start()
    $(document).on "click", ".selectable", (e) ->
      $("input[name=gift-search]").val(
        $(e.target).html().trim()
      )

    # login - register tab
    @tab = new LoginRegister(
      {
        form : "#form-login"
        btn : ".tab-login"
      }
    ,
      {
        form : "#form-register"
        btn : ".tab-register"
      }
    ,
      {
        active: "btn-purple"
        passive: "btn-navy"
      }
    )
    $(document).on "click", @tab.login.btn, =>
      @tab.setActive("login")
      @tab.toggleBtn()
    $(document).on "click", @tab.register.btn, =>
      @tab.setActive("register")
      @tab.toggleBtn()
)
