define [
  "marionette"
  "validate"
], ( (Marionette, Validate) ->

  ###*
  # Autocompletion data
  #
  # @class SearchUserModel
  # @constructor
  ###
  class SearchUserModel extends Backbone.Model

    ###*
    # Url to talk to the server
    #
    # @attribute url
    # @default "searchuser"
    # @type String
    ###
    url : "searchuser"

    # Validation data
    validation :

      ###*
      # validate username
      #
      # @property username
      # @param {Boolean} required
      ###
      username :
        required : true
      
)
