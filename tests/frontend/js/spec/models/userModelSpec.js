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
          username: "lambda",
          password: "      ",
          email: "lambda@user.com"
        });
        return self.user = new User({
          model: self.userModel
        });
      });
      it("should initialize the user", function() {
        self.user.model.get("username").should.equal("lambda");
        this.pwd = self.user.model.get("password").replace(/^\s+|\s+$/g, "");
        this.pwd.should.equal("");
        return self.user.model.get("email").should.equal("lambda@user.com");
      });
      return describe("Validation", function() {
        it("should return false if non-valid username", function() {
          this.userModel = new UserModel({
            username: "w"
          });
          this.user = new User({
            model: this.userModel
          });
          this.saving = this.userModel.save();
          return this.saving.should.be["false"];
        });
        it("should return false if non-valid password", function() {
          this.userModel = new UserModel({
            password: "w"
          });
          this.user = new User({
            model: this.userModel
          });
          this.saving = this.userModel.save();
          return this.saving.should.be["false"];
        });
        it("should return false if non-valid email", function() {
          this.userModel = new UserModel({
            email: "notvalid"
          });
          this.user = new UserModel({
            model: this.userModel
          });
          this.saving = this.userModel.save();
          return this.saving.should.be["false"];
        });
        return it("should not be false if everything valid", function() {
          this.userModel = new UserModel({
            username: "lambda",
            password: "      ",
            email: "lambda@user.com"
          });
          this.user = new User({
            model: this.userModel
          });
          this.saving = this.userModel.save();
          return this.saving.should.not.be["false"];
        });
      });
    });
  }));

}).call(this);
