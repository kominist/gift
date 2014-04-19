define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->
  class GiftModel extends Backbone.Model
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

      true
)
