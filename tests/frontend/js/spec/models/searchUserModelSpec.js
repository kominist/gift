(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(["../../../../../assets/dist/js/app/search/searchUserModel"], (function(SearchUserModel) {
    var SearchUser;
    SearchUser = (function(_super) {
      __extends(SearchUser, _super);

      function SearchUser() {
        return SearchUser.__super__.constructor.apply(this, arguments);
      }

      SearchUser.prototype.model = SearchUserModel;

      return SearchUser;

    })(Marionette.ItemView);
    return describe("Search User Model", function() {
      var self;
      self = this;
      before(function() {
        self.searchUserModel = new SearchUserModel({
          username: "giver"
        });
        return self.searchUser = new SearchUser({
          model: self.searchUserModel
        });
      });
      it("should initialize the user search", function() {
        return self.searchUser.model.get("username").should.equal("giver");
      });
      return describe("Validation", function() {
        it("should return false if no username is given", function() {
          this.searchUserModel = new SearchUserModel();
          this.searchUser = new SearchUser({
            model: this.searchUserModel
          });
          this.saving = this.searchUserModel.save();
          return this.saving.should.be["false"];
        });
        return it("should not be false if username is giver", function() {
          this.searchUserModel = new SearchUserModel({
            username: "giver"
          });
          this.searchUser = new SearchUser({
            model: this.searchUserModel
          });
          this.saving = this.searchUserModel.save();
          return this.saving.should.not.be["false"];
        });
      });
    });
  }));

}).call(this);
