(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(["../../../../../assets/dist/js/app/search/searchModel"], (function(SearchModel) {
    var Search;
    Search = (function(_super) {
      __extends(Search, _super);

      function Search() {
        return Search.__super__.constructor.apply(this, arguments);
      }

      Search.prototype.model = SearchModel;

      return Search;

    })(Marionette.ItemView);
    return describe("Search Model", function() {
      var self;
      self = this;
      before(function() {
        self.searchModel = new SearchModel({
          username: "giver"
        });
        return self.search = new Search({
          model: self.searchModel
        });
      });
      return it("should initialize a search", function() {
        return self.search.model.get("username").should.equal("giver");
      });
    });
  }));

}).call(this);
