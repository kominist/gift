define [
  "marionette"
  "gift/giftModel"
], (( Marionette, GiftModel) ->
  ###*
  # List of gifts
  #
  # @class GiftCollection
  # @extends Backbone.Collection
  ###
  class GiftCollection extends Backbone.Collection

    ###*
    # Model of the collection
    #
    # @attribute model
    # @default GiftModel
    # @type Backbone.Model
    ###
    model : GiftModel

    ###*
    # Url to talk to the server
    #
    # @attribute url
    # @default "gift"
    # @type String
    ###
    url : "gift"
)
