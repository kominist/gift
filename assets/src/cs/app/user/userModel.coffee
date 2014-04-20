define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->
  ###*
  # Single user
  #
  # @class UserModel
  # @constructor
  # @extends Backbone.Model
  ###
  class UserModel extends Backbone.Model

    ###*
    # Url to talk to the server
    #
    # @attribute url
    # @default "user"
    # @type String
    ###
    url : "user"

    # Validation data
    validation :

      ###*
      # validate email
      #
      # @property email
      # @param {Boolean} required
      # @param {String} format
      # @param {String} message
      ###
      email :
        required: true
        format : 'email'
        message : "addresse de courriel non valide"

      ###*
      # validate username
      #
      # @property username
      # @param {Boolean} required
      # @param {Integer} minLength
      # @param {String} message
      ###
      username :
        required: true
        minLength : 2
        message : "pseudonyme non valide"

      ###*
      # Validate password
      #
      # @property password
      # @param {Boolean} required
      # @param {Integer} minlength
      # @param {String} message
      ###
      password :
        required: true
        minLength : 4
        message : "mot de passe non valide"

)
