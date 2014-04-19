define [
  "marionette",
  "search/searchGiftModel"
], ((
  Marionette,
  SearchGiftModel
) ->
  class SearchGift extends Marionette.ItemView
    model : SearchGiftModel
    template : "#filter-name"
)
