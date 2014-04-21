(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(["../../../../../assets/dist/js/app/user/userModel"], (function(UserModel) {
    var User;
    User = (function(_super) {
      __extends(User, _super);

      function User() {
        return User.__super__.constructor.apply(this, arguments);
      }

      User.prototype.model = UserModel;

      return User;

    })(Marionette.ItemView);
    return describe("User Model", function() {
      var self;
      self = this;
      before(function() {
        self.userModel = new UserModel({
          username: "lambda"
        });
        return self.user = new User({
          model: self.userModel
        });
      });
      return it("should initialize the user", function() {
        return self.user.model.get("username").should.equal("lambda");
      });
    });
  }));

}).call(this);
