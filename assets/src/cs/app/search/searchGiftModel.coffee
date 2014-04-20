define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->

  ###*
  # Search a trade between two users
  #
  # @class SearchGiftModel
  # @constructor
  # @extends Backbone.Model
  ###
  class SearchGiftModel extends Backbone.Model

    ###*
    # Url to talk to the server
    #
    # @attribute url
    # @default "searchtrade"
    # @type String
    ###
    url : "searchtrade"

    # Validate data
    validation :

      ###*
      # validate user filter
      #
      # @property validation.filteron
      # @param {Boolean} required
      # @param {Integer} minlength
      ###
      filterOn :
        required : true

    ###*
    # Set the filter to off
    #
    # @attribute filterOn
    # @default false
    # @type Boolean
    ###
    filterOn : false
)
