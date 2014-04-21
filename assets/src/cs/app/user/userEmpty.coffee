define [
  "marionette"
  "user/userModel"
], (( Marionette, UserModel) ->

  ###*
  # View if the user is not logged in
  #
  # @class UserEmpty
  # @constructor
  ###
  class UserEmpty extends Marionette.ItemView

    ###*
    # Model to store the current user
    #
    # @attibute model
    # @constructor
    # @extends Backbone.Model
    ###
    model : UserModel

    ###*
    # Template for the view
    #
    # @attribute template
    # @default "#user-empty-view"
    # @type String
    ###
    template : "#user-empty-view"

    # Bind DOM element as a variable
    ui :
      ###*
      # Nickname input for registering
      #
      # @property ui.registerNick
      # @default "input[name=register-nick]"
      # @type String
      ###
      registerNick : "input[name=register-nick]"

      ###*
      # Password input for registering
      #
      # @property ui.registerPassword
      # @default "input[name=register-pwd]"
      # @type String
      ###
      registerPassword : "input[name=register-pwd]"

      ###*
      # Mail input for registering
      #
      # @property ui.registerMail
      # @default "input[name=register-mail]"
      # @type String
      ###
      registerMail : "input[name=register-mail]"

      ###*
      # Mail input for login
      #
      # @property ui.loginMail
      # @default "input[name=login-mail]"
      # @type String
      ###
      loginMail : "input[name=login-mail]"

      ###*
      # Password input for login
      #
      # @property ui.loginPassword
      # @default "input[name=login-pwd]"
      # @type String
      ###
      loginPassword : "input[name=login-pwd]"

    # Bind events to methods
    events :

      ###*
      # Fire when a user filled the form and
      # want to register
      #
      # @event click:doRegister
      ###
      'click button#register' : "doRegister"

      ###*
      # Fire when a user filled the form and
      # want to login
      #
      # @event click:doLogin
      ###
      'click button#login' : "doLogin"
    ###*
    # Register a user
    #
    # @method doRegister
    # @param {Object} jquery.event
    ###
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

    ###*
    # Login a user
    #
    # @method doLogin
    # @param {Object} jquery.event
    ###
    doLogin : (e) ->
      e.preventDefault()
      @model.set(username : "   ")
      @model.set(email : @ui.loginMail.val())
      @model.set(password : @ui.loginPassword.val())
      @model.set("status", "login")
      if @model.save() is false
        if @model.validationError.email?
          $(".login-email-error").html(@model.validationError.email[0])
        if @model.validationError.password?
          $(".login-pwd-error").html(@model.validationError.password[0])

)
