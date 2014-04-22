(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(["../../../../../assets/dist/js/app/gift/giftModel"], (function(GiftModel) {
    var Gift;
    Gift = (function(_super) {
      __extends(Gift, _super);

      function Gift() {
        return Gift.__super__.constructor.apply(this, arguments);
      }

      Gift.prototype.model = GiftModel;

      return Gift;

    })(Marionette.ItemView);
    return describe("Gift Model", function() {
      var self;
      self = this;
      before(function() {
        self.giftModel = new GiftModel({
          id: 1,
          status: "initialized",
          getter: {
            id: 1,
            name: "getter"
          },
          giver: {
            id: 2,
            name: "giver"
          }
        });
        return self.gift = new Gift({
          model: self.giftModel
        });
      });
      it("should initialize a gift", function() {
        self.gift.model.get("id").should.equal(1);
        self.gift.model.get("status").should.equal("initialized");
        self.gift.model.get("getter").should.eql({
          id: 1,
          name: "getter"
        });
        return self.gift.model.get("giver").should.eql({
          id: 2,
          name: "giver"
        });
      });
      return describe("Attach permissions on gift", function() {
        describe("Giver", function() {
          it("should be able to cancel his gift trade", function() {
            self.gift.model.isCurrentUser("giver");
            return self.gift.model.get("cancelable").should.be["true"];
          });
          return it("should not be able to accept/refuse his own gift", function() {
            self.gift.model.isCurrentUser("giver");
            return self.gift.model.get("refusable").should.be["false"];
          });
        });
        describe("Getter", function() {
          it("should be able to accept/refuse a gift", function() {
            self.gift.model.isCurrentUser("getter");
            return self.gift.model.get("refusable").should.be["true"];
          });
          return it("should not be able to cancel a gift", function() {
            self.gift.model.isCurrentUser("getter");
            return self.gift.model.get("cancelable").should.be["false"];
          });
        });
        return describe("Not Getter nor giver", function() {
          it("should not be able to cancel a gift", function() {
            self.gift.model.isCurrentUser("lambda");
            return self.gift.model.get("cancelable").should.be["false"];
          });
          return it("should not be able to accept/refuse his own gift", function() {
            self.gift.model.isCurrentUser("lambda");
            return self.gift.model.get("refusable").should.be["false"];
          });
        });
      });
    });
  }));

}).call(this);
