define [
  "../../../../../assets/dist/js/app/user/userModel"
], (( UserModel) ->
  class User extends Marionette.ItemView
    model : UserModel

  describe "User Model", ->
    self = @
    before ->
      self.userModel = new UserModel(username : "lambda")
      self.user = new User(model : self.userModel)
    it "should initialize the user", ->
      self.user.model.get("username").should.equal "lambda"
)
