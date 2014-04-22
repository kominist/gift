define [
  "../../../../../assets/dist/js/app/user/userModel"
], (( UserModel) ->
  class User extends Marionette.ItemView
    model : UserModel

  describe "User Model", ->
    self = @
    before ->
      self.userModel = new UserModel(
        username : "lambda",
        password : "      ",
        email : "lambda@user.com"
      )
      self.user = new User(model : self.userModel)

    it "should initialize the user", ->
      self.user.model.get("username").should.equal "lambda"
      @pwd = self.user.model.get("password").replace /^\s+|\s+$/g, ""
      @pwd.should.equal ""
      self.user.model.get("email").should.equal "lambda@user.com"
    
    describe "Validation", ->
      it "should return false if non-valid username", ->
        @userModel = new UserModel username : "w"
        @user = new User(model : @userModel)
        @saving = @userModel.save()
        @saving.should.be.false

      it "should return false if non-valid password", ->
        @userModel = new UserModel password : "w"
        @user = new User(model : @userModel)
        @saving = @userModel.save()
        @saving.should.be.false

      it "should return false if non-valid email", ->
        @userModel = new UserModel email : "notvalid"
        @user = new UserModel(model : @userModel)
        @saving = @userModel.save()
        @saving.should.be.false

      it "should not be false if everything valid", ->
        @userModel = new UserModel(
          username : "lambda",
          password : "      ",
          email : "lambda@user.com"
        )
        @user = new User(model : @userModel)
        @saving = @userModel.save()
        @saving.should.not.be.false
)
