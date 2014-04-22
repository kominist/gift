define [
  "../../../../../assets/dist/js/app/search/searchUserModel"
], (( SearchUserModel) ->
  class SearchUser extends Marionette.ItemView
    model : SearchUserModel

  describe "Search User Model", ->
    self = @
    before ->
      self.searchUserModel = new SearchUserModel(
        username : "giver"
      )
      self.searchUser = new SearchUser( model : self.searchUserModel)

    it "should initialize the user search", ->
      self.searchUser.model.get("username").should.equal "giver"

    describe "Validation", ->
      it "should return false if no username is given", ->
        @searchUserModel = new SearchUserModel()
        @searchUser = new SearchUser( model : @searchUserModel)
        @saving = @searchUserModel.save()
        @saving.should.be.false
     
      it "should not be false if username is giver", ->
        @searchUserModel = new SearchUserModel(
          username : "giver"
        )
        @searchUser = new SearchUser( model : @searchUserModel)
        @saving = @searchUserModel.save()
        @saving.should.not.be.false
)
