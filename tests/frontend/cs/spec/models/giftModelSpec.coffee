define [
  "../../../../../assets/dist/js/app/gift/giftModel"
], (( GiftModel) ->
  class Gift extends Marionette.ItemView
    model : GiftModel

  describe "Gift Model", ->
    self = @
    before ->
      self.giftModel = new GiftModel(
        id : 1
        status : "initialized"
        getter :
          id : 1
          name : "getter"
        giver :
          id : 2
          name : "giver"
      )
      self.gift = new Gift model : self.giftModel

    it "should initialize a gift", ->
      self.gift.model.get("id").should.equal 1
      self.gift.model.get("status").should.equal "initialized"
      self.gift.model.get("getter").should.eql id : 1, name : "getter"
      self.gift.model.get("giver").should.eql id : 2, name : "giver"

    describe "Attach permissions on gift", ->
      describe "Giver", ->
        it "should be able to cancel his gift trade", ->
          self.gift.model.isCurrentUser "giver"
          self.gift.model.get("cancelable").should.be.true

        it "should not be able to accept/refuse his own gift", ->
          self.gift.model.isCurrentUser "giver"
          self.gift.model.get("refusable").should.be.false

      describe "Getter", ->
        it "should be able to accept/refuse a gift", ->
          self.gift.model.isCurrentUser "getter"
          self.gift.model.get("refusable").should.be.true

        it "should not be able to cancel a gift", ->
          self.gift.model.isCurrentUser "getter"
          self.gift.model.get("cancelable").should.be.false
      
      describe "Not Getter nor giver", ->
        it "should not be able to cancel a gift", ->
          self.gift.model.isCurrentUser "lambda"
          self.gift.model.get("cancelable").should.be.false

        it "should not be able to accept/refuse his own gift", ->
          self.gift.model.isCurrentUser "lambda"
          self.gift.model.get("refusable").should.be.false
)

