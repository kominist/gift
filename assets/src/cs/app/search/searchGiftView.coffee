define [
  "marionette",
  "search/searchGiftModel"
], ((
  Marionette,
  SearchGiftModel
) ->

  ###*
  # Render the name on which
  # the collection is filtered
  #
  # @class SearchGift
  # @constructor
  ###
  class SearchGift extends Marionette.ItemView

    ###*
    # Model to render the filter name
    #
    # @attibute model
    # @default SearchGiftModel
    # @type Backbone.Model
    ###
    model : SearchGiftModel

    ###*
    # Template for the view
    #
    # @attribute template
    # @default "#filter-name"
    # @type String
    ###
    template : "#filter-name"
)
