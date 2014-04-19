define [
  "marionette",
  "gift/giftCollection"
  "gift/gift"
  "gift/zerogift"
  "user/userModel"
  "search/searchGiftModel"
  "search/searchGiftView"
], ((
  Marionette,
  GiftCollection,
  Gift,
  ZeroGiftView,
  UserModel,
  SearchGiftModel,
  SearchGiftView
) ->
  class GiftCompositeView extends Marionette.CompositeView
    collection : GiftCollection
    itemView : Gift
    emptyView : ZeroGiftView
    model : SearchGiftModel
    template : "#search-result"
    itemViewContainer : "#suggestions"

    setUser : (currentUser) ->
      @user = currentUser
  
    onBeforeRender : ->
      for model in @collection.models
        model.isCurrentUser(@user)
      
    modelEvents :
      "change" : "modelChanged"
    collectionEvents :
      "change" : "collectionChanged"

    modelChanged : ->
      saved = @model.save(
        {},
        success : (model, response)=>
          @collection.reset response
      )
      @render()

    collectionChanged : ->
      @render()
)
