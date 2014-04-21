define [
  "marionette",
  "search/searchModel"
  "search/searchUserModel"
], ((
  Marionette,
  SearchModel,
  SearchUserModel,
) ->
  ###*
  # Search view
  #
  # @class Search
  # @constructor
  # @extends Marionette.ItemView
  ###
  class Search extends Marionette.ItemView

    ###*
    # Model to render the search
    #
    # @attribute model
    # @default SearchModel
    # @type Backbone.Model
    ###
    model : SearchModel

    ###*
    # Template for the view
    #
    # @attribute template
    # @default "#gift-search"
    # @type String
    ###
    template : "#gift-search"

    # Bind DOM element as a variable
    ui :
      ###*
      # User input
      #
      # @property ui.input
      # @default "input[name=gift-search]"
      # @type String
      ###
      input : "input[name=gift-search]"

      ###*
      # Error box, trigger when the validations
      # did not pass
      #
      # @property ui.error
      # @default ".error-search"
      # @type String
      ###
      error : ".error-search"

      ###*
      # Suggestion based on user input
      #
      # @property ui.suggestion
      # @default "#result"
      # @type String
      ###
      suggestion : "#result"

      ###*
      # Send a gift to the user input
      #
      # @property ui.send
      # @default "button[name=gift-add]"
      # @type String
      ###
      send : "button[name=gift-add]"

      
      results : "#gifts"

    # Bind events to methods
    events :

      ###*
      # Fire when a user type something in the search input
      #
      # @event keyUp:doSearch
      ###
      "keyup input[name=gift-search]" : "doSearch"

      ###*
      # Fire when the user clicks on "donner un cadeau"
      #
      # #event click:sendUsername
      ###
      "click button[name=gift-add]" : "sendUsername"

    ###*
    # Autocompletion
    #
    # @method doSearch
    # @param {Object} jquery.event
    # @return {Array} users matching the input
    ###
    doSearch : (e) ->
      if @ui.input.val().length >= 1
        @model.set("value", @ui.input.val())
        @model.save({},
          success : (model, response) =>
            @suggest(response)
        )
    ###*
    # Render the autocompletion
    #
    # @method suggest
    # @param {Array} users
    # @render {Mixed} template
    ###
    suggest : (results) ->
      tpl = $("#search-result").html()
      if results.length is 0 or results is false
        results.username = ""
      $("#result").html(_.template(tpl, {filterOn : "", results : results}))
    
    ###*
    # Give a gift
    #
    # @method sendUsername
    # @param {Object} jquery.event
    # @beta
    ###
    sendUsername : (e) ->
      e.preventDefault()
      searchUserModel = new SearchUserModel(username : @ui.input.val())
      if searchUserModel.save() is false
        if @model.validationError?
          @model.validationError = "champ de rechereche non valide"
        @ui.error.html(@model.validationError)
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )
)

