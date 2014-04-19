define [
  "marionette",
  "search/searchModel"
  "search/searchUserModel"
], ((
  Marionette,
  SearchModel,
  SearchUserModel,
) ->
  class Search extends Marionette.ItemView
    model : SearchModel
    template : "#gift-search"
    ui :
      input : "input[name=gift-search]"
      suggestion : "#result"
      send : "button[name=gift-add]"
      results : "#gifts"

    events :
      "keyup input[name=gift-search]" : "doSearch"
      "click button[name=gift-add]" : "sendUsername"

    doSearch : (e) ->
      if @ui.input.val().length >= 1
        @model.set("value", @ui.input.val())
        @model.save({},
          success : (model, response) =>
            @suggest(response)
        )

    suggest : (results) ->
      tpl = $("#search-result").html()
      if results.length is 0 or results is false
        results.username = ""
      $("#result").html(_.template(tpl, {filterOn : "", results : results}))

    sendUsername : (e) ->
      e.preventDefault()
      searchUserModel = new SearchUserModel(username : @ui.input.val())
      if searchUserModel.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )
)

