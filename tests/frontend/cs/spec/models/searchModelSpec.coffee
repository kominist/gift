define [
  "../../../../../assets/dist/js/app/search/searchModel"
], (( SearchModel) ->
  class Search extends Marionette.ItemView
    model : SearchModel

  describe "Search Model", ->
    self = @
    before ->
      self.searchModel = new SearchModel(
        username : "giver"
      )
      self.search = new Search( model : self.searchModel)

    it "should initialize a search", ->
      self.search.model.get("username").should.equal "giver"
)
