define [
  "marionette"
  "validate"
], ( (Marionette, Validate) ->
  class SearchUserModel extends Backbone.Model
    url : "searchuser"
    validation :
      username :
        required : true
      
)
