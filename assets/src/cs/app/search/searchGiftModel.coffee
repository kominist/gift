define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->
  class SearchGiftModel extends Backbone.Model
    url : "searchtrade"
    validations :
      filterOn :
        required : true
        minLength : 2
    filterOn : 0
)
