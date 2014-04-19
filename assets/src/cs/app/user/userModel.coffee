define [
  "marionette"
  "validate"
], (( Marionette, Validate) ->
  class UserModel extends Backbone.Model
    url : "user"
    validation :
      email :
        required: true
        format : 'email'
        message : "addresse de courriel non valide"
      username :
        required: true
        minLength : 2
        message : "pseudonyme non valide"
      password :
        required: true
        minLength : 4
        message : "mot de passe non valide"

)
