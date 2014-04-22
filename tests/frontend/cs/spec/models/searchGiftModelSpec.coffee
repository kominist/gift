define [
  "../../../../../assets/dist/js/app/search/searchGiftModel"
], (( SearchGiftModel) ->
  class SearchGift extends Marionette.ItemView
    model : SearchGiftModel

    describe "Search Gift Model", ->
      self = @
      before ->
        self.searchGiftModel = new SearchGiftModel()
        self.searchGift = new SearchGift(model : self.searchGiftModel)

      #it "should initialize the gift search", ->
        #self.searchGift.model.get("filterOn").should.exists
)
