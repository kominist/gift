define [
  "marionette",
  "gift/giftModel"
  "user/userModel"
], (( Marionette, GiftModel, UserModel) ->
  ###*
  # Single gift view
  #
  # @class Gift
  # @constructor
  # @extends Marionette.ItemView
  ###
  class Gift extends Marionette.ItemView

    ###*
    # Model to render the gift
    #
    # @attribute model
    # @default GiftModel
    # @type Backbone.Model
    ###
    model : GiftModel

    ###*
    # Template for the view
    #
    # @attribute template
    # @default "#gift-view"
    # @type String
    ###
    template : "#gift-view"

    ###*
    # Model to store the current user
    #
    # @attribute currentUser
    # @default UserModel
    # @type Backbone.Model
    ###
    currentUser : UserModel
    
    ###*
    # Bind DOM element as a variable
    #
    # @property ui.create
    # @default "button#add-gift"
    # @type String
    ###
    ui :
      create : "button#add-gift"

    # Bind events to methods
    events :

      ###*
      # Fire when a user have accepted the gift trade
      #
      # @event click:doAccept
      # @requires user:read
      ###
      'click button[name=accept-gift]' : "doAccept"

      ###*
      # Fire when a user have refused the gift trade
      #
      # @event click:doRefuse
      # @requires user:read
      ###
      'click button[name=refuse-gift]' : "doRefuse"

      ###*
      # Fire when a user canceled a trade
      #
      # @event click:doCancel
      # @requires user:create
      ###
      'click button[name=cancel-gift]' : "doCancel"

    ###*
    # Render a template depending on user permissions
    #
    # @method getTemplate
    # @return {String} View
    ###
    getTemplate : ->
      if @model.get("cancelable") is true
        return "#gift-view-current-giver"
      if @model.get("refusable") is true
        return "#gift-view-current-getter"
      "#gift-view"

    ###*
    # Accept a gift
    #
    # @method doAccept
    # @param {Object} jquery.event
    # @beta
    ###
    doAccept : (e) ->
      e.preventDefault()
      @model.set("status", "accepted")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )

    ###*
    # Refuse a gift
    #
    # @method doRefuse
    # @param {Object} jquery.event
    ###
    doRefuse : (e) ->
      e.preventDefault()
      @model.set("status", "refused")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )

    ###*
    # Cancel a gift
    #
    # @method doCancel
    # @param {Object} jquery.event
    ###
    doCancel : (e) ->
      e.preventDefault()
      @model.destroy()

)
