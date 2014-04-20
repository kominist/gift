define [
  "marionette"
], (( Marionette) ->

  ###*
  # View when no gift have been found
  #
  # @class ZeroGiftView
  # @constructor
  # @extends Marionette.ItemView
  ###
  class ZeroGiftView extends Marionette.ItemView
    ###*
    # Template for the current view
    #
    # @attribute rendered
    # @default "#gift-empty-view"
    # @type String
    ###
    template : "#gift-empty-view"
)
