define [
  "marionette"
  "user/userModel"
], (( Marionette, UserModel) ->
  class User extends Marionette.ItemView
    template : "#user-view"
    model : UserModel
    itemViewContainer : "user"
      
    events :
      'click button#logout' : "doLogout"

    onBeforRender : ->
      @model.set("status", true)

    doLogout : (e) ->
      e.preventDefault()
      @model.set("status", "inactive")
      if @model.save() is false
        console.log @model.validationError
      else
        # we're going dirty baby
        setTimeout(
          -> window.location.reload()
        , 500
        )
        
)
