define [
  "marionette",
  "gift/giftCollection"
  "gift/gift"
  "gift/zerogift"
  "user/userModel"
], ((
  Marionette,
  GiftCollection,
  Gift,
  ZeroGiftView,
  UserModel,
) ->
  class GiftCollectionView extends Marionette.CollectionView
    collection : GiftCollection
    itemView : Gift
    emptyView : ZeroGiftView

    events :
      "click button[name=gift-seek]" : "doSeekGifts"

    setUser : (currentUser) ->
      @user = currentUser
  
    onBeforeRender : ->
      for model in @collection.models
        model.isCurrentUser(@user)


)
