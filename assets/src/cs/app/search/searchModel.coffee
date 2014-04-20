define [
  "marionette"
  "validate"
], ((Marionette, Validate) ->
  ###*
  # Search a gift trade
  #
  # @class Search
  # @constructor
  ###
  class Search extends Backbone.Model

    ###*
    # Url to talk to server
    #
    # @attribute url
    # @default search
    # @type String
    ###
    url : "search"
)
