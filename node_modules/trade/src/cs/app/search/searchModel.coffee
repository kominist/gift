define [
  "marionette"
  "validate"
], ((Marionette, Validate) ->
  class Search extends Backbone.Model
    url : "search"
)
