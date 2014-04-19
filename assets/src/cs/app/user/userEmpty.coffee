define [
  "marionette"
  "user/userModel"
], (( Marionette, UserModel) ->
  class UserEmpty extends Marionette.ItemView
    template : "#user-empty-view"
    model : UserModel
    ui :
      registerNick : "input[name=register-nick]"
      registerPassword : "input[name=register-pwd]"
      registerMail : "input[name=register-mail]"
      loginMail : "input[name=login-mail]"
      loginPassword : "input[name=login-pwd]"

    events :
      'click button#register' : "doRegister"
      'click button#login' : "doLogin"

    doRegister : (e) ->
      e.preventDefault()
      @model.set(username : @ui.registerNick.val())
      @model.set(email : @ui.registerMail.val())
      @model.set(password : @ui.registerPassword.val())
      @model.set("status", "register")
      if @model.save() is false
        if @model.validationError.username?
          $(".register-username-error").html(@model.validationError.username[0])
        if @model.validationError.email?
          $(".register-email-error").html(@model.validationError.email[0])
        if @model.validationError.password?
          $(".register-pwd-error").html(@model.validationError.password[0])

    doLogin : (e) ->
      e.preventDefault()
      @model.set(username : "dd")
      @model.set(email : @ui.loginMail.val())
      @model.set(password : @ui.loginPassword.val())
      @model.set("status", "login")
      if @model.save() is false
        if @model.validationError.email?
          $(".login-email-error").html(@model.validationError.email[0])
        if @model.validationError.password?
          $(".login-pwd-error").html(@model.validationError.password[0])

)
