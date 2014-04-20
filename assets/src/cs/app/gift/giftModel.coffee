define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->

  ###*
  # Single gift data
  #
  # @class GiftModel
  # @constructor
  # @extends Backbone.Model
  ###
  class GiftModel extends Backbone.Model

    ###*
    # Check the status of the gift trade
    #
    # @method isCurrentUser
    # @param {String} user
    # @return {Boolean} assert that the
    #   status variable is instancied
    ###
    isCurrentUser : (user) ->
      if @get("status") is "initialized"
        if user is @get("giver").name
          @set("cancelable", true)
        else
          @set("cancelable", false)
        if user is @get("getter").name
          @set("refusable", true)
        else
          @set("refusable", false)
        return true
      false
      
)
