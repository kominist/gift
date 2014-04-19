define [
  "marionette"
  "gift/giftModel"
], (( Marionette, GiftModel) ->
  class GiftCollection extends Backbone.Collection
    model : GiftModel
    url : "gift"
)
