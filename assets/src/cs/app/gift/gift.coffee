define [
  "marionette",
  "gift/giftModel"
  "user/userModel"
], (( Marionette, GiftModel, UserModel) ->
  class Gift extends Marionette.ItemView
    model : GiftModel
    template : "#gift-view"
    currentUser : UserModel

    ui :
      create : "button#add-gift"
    events :
      'click button[name=accept-gift]' : "doAccept"
      'click button[name=refuse-gift]' : "doRefuse"
      'click button[name=cancel-gift]' : "doCancel"

    getTemplate : ->
      if @model.get("cancelable") is true
        return "#gift-view-current-giver"
      if @model.get("refusable") is true
        return "#gift-view-current-getter"
      "#gift-view"

    doAccept : (e) ->
      e.preventDefault()
      @model.set("status", "accepted")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )

    doRefuse : (e) ->
      e.preventDefault()
      @model.set("status", "refused")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )
    
    doCancel : (e) ->
      e.preventDefault()
      @model.destroy()

    seekExchange : (e) ->

)