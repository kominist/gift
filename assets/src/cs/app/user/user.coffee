define [
  "marionette"
  "user/userModel"
], (( Marionette, UserModel) ->
  ###*
  # Single user view
  #
  # @class User
  # @constructor
  # @extends Marionette.ItemView
  ###
  class User extends Marionette.ItemView

    ###*
    # Model to render a user
    #
    # @attribute model
    # @default UserModel
    # @type Backbone.Model
    ###
    model : UserModel

    ###*
    # Template for the view
    #
    # @attribute template
    # @default "#user-view"
    # @string String
    ###
    template : "#user-view"

    itemViewContainer : "user"

    # Bind events to methods
    events :

      ###*
      # Fire when a logged clicks on logout
      #
      # @event click:doLogout
      # @requires user:islogged
      ###
      'click button#logout' : "doLogout"

    ###*
    # Logout the user
    #
    # @method dologout
    # @param {Object} jquery.event
    ###
    doLogout : (e) ->
      e.preventDefault()
      @model.set("status", "inactive")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )
)
