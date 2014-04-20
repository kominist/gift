define [
  "marionette",
  "gift/giftCollection"
  "gift/gift"
  "gift/zerogift"
  "user/userModel"
  "search/searchGiftModel"
  "search/searchGiftView"
], ((
  Marionette,
  GiftCollection,
  Gift,
  ZeroGiftView,
  UserModel,
  SearchGiftModel,
  SearchGiftView
) ->

  ###*
  # List of gift collection
  #
  # @class GiftCompositeView
  # @constructor
  # @extends Marionette.CompositeView
  ###
  class GiftCompositeView extends Marionette.CompositeView

    ###*
    # Collection type
    #
    # @attribute collection
    # @default GiftCollection
    # @type Backbone.Collection
    ###
    collection : GiftCollection

    ###*
    # itemView for a single gift
    #
    # @attribute itemView
    # @default Gift
    # @type Marionette.ItemView
    ###
    itemView : Gift

    ###*
    # itemView when no gift is provided
    #
    # @attribute emptyView
    # @default ZeroGiftView
    # @type Marionette.ItemView
    ###
    emptyView : ZeroGiftView

    ###*
    # Filters the collection through this model
    #
    # @attribute model
    # @default SearchGiftModel
    # @type Backbone.Model
    ###
    model : SearchGiftModel

    ###*
    # Template for the model
    #
    # @attribute template
    # @default "#search-result"
    # @type String
    ###
    template : "#search-result"

    ###*
    # Container of the gift collection
    #
    # @attribute itemViewContainer
    # @default "#suggestions"
    # @type String
    ###
    itemViewContainer : "#suggestions"

    ###*
    # Set the current user
    #
    # @method setUser
    # @param {String} currentUser
    # @return {String} this.user
    ###
    setUser : (currentUser) ->
      @user = currentUser
  
    ###*
    # Trigger before rendering the CompositeView
    #
    # @method onBeforeRender
    ###
    onBeforeRender : ->
      for model in @collection.models
        model.isCurrentUser(@user)
      
    ###*
    # Fire when the model SearchGiftModel is changed
    #
    # @event modelEvents.change
    # @param {String} function to call
    ###
    modelEvents :
      "change" : "modelChanged"

    ###*
    # Fire when the colleciton is changed
    #
    # @event collectionEvents.change
    # @param {String} function to call
    ###
    collectionEvents :
      "change" : "collectionChanged"

    ###*
    # Function to filter the collection when
    # the filter SearchGiftModel change
    #
    # @method modelchanged
    # @return {Mixed} render
    ###
    modelChanged : ->
      saved = @model.save(
        {},
        success : (model, response)=>
          @collection.reset response
      )
      @render()

    ###*
    # Rerender the collection
    #
    # @method collectionChanged
    # @return {Mixed} render
    ###
    collectionChanged : ->
      @render()
)
