define [
  "marionette"
  "user/user"
  "user/userEmpty"
  "user/userModel"
  "search/search"
  "search/searchModel"
  "search/searchGiftModel"
  "gift/giftCompositeView"
  "gift/giftCollection"
], ((
  Marionette,
  User,
  UserEmpty,
  UserModel,
  Search,
  SearchModel,
  SearchGiftModel,
  GiftCompositeView,
  GiftCollection
) ->
  app = new Marionette.Application()
  app.addRegions
    page : "#page"
    content : "#content"
    login : "#login"
    searchInput : "#search"
    searchResult : "#result"
    giftList : "#gifts"

  app.on "initialize:after", ->
    Backbone.history.start() unless Backbone.history.started
    $("#init").remove()
    @searchInput.show(@search.render())
    @login.show(@user.render())

  app.on "initialize:before", ->
    Backbone.emulateHTTP = true
    userModel = new UserModel()
    userModel.fetch()
    searchModel = new SearchModel()
    @search = new Search(model : searchModel)
    giftCollection = new GiftCollection()
    giftCollection.fetch()
    userModel.on "sync",
    =>
      @user = new User(model : userModel)
      @login.show(@user.render())
    ,
    giftCollection.on "sync", =>
      @filters = new GiftCompositeView(
        collection : giftCollection,
        model : new SearchGiftModel(filterOn : false)
      )
      @filters.setUser(@user.model.get("username"))
      @giftList.show(@filters.render())
      $("button[name=gift-seek]").on("click", (e) =>
        e.preventDefault()
        @filters.model.set("filterOn", $("input[name=gift-search]").val())

      )
    @user = new UserEmpty(model : userModel)

  app
)
