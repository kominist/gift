(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(["../../../../../assets/dist/js/app/search/searchGiftModel"], (function(SearchGiftModel) {
    var SearchGift;
    return SearchGift = (function(_super) {
      __extends(SearchGift, _super);

      function SearchGift() {
        return SearchGift.__super__.constructor.apply(this, arguments);
      }

      SearchGift.prototype.model = SearchGiftModel;

      describe("Search Gift Model", function() {
        var self;
        self = this;
        return before(function() {
          self.searchGiftModel = new SearchGiftModel();
          return self.searchGift = new SearchGift({
            model: self.searchGiftModel
          });
        });
      });

      return SearchGift;

    })(Marionette.ItemView);
  }));

}).call(this);
