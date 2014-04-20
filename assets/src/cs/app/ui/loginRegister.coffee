define [], ->
  ###*
  # Manage form tabs
  #
  # @class LoginRegister
  # @constructor
  ###
  class LoginRegister
    constructor : (@login, @register, @btn) ->
      $(@login.form).hide()
      $(@register.form).hide()
      $(@register.btn).addClass("btn #{@btn.passive}")
      $(@login.btn).addClass("btn #{@btn.passive}")
      @

    ###*
    # Show/hide forms depending on user actions
    #
    # @method toggleBtn
    ###
    toggleBtn : ->
      $(@register.form).hide()
      $(@currentActive.btn).removeClass(@btn.passive)
      $(@currentActive.btn).addClass(@btn.active)
      $(@currentPassive.btn).removeClass(@btn.active)
      $(@currentPassive.btn).addClass(@btn.passive)
      $(@currentPassive.form).hide()
      if not @isActive()
        $(@currentActive.form).show()
      else
        $(@currentActive.form).hide()
        $(@currentActive.btn).removeClass(@btn.active)
        $(@currentActive.btn).addClass(@btn.passive)
        @currentActive = {}

    ###*
    # Compare the previous and current status
    # of the form in order to hide the active form
    # if it was previously actie
    #
    # @method isActive
    # @return {Boolean} isActive
    ###
    isActive : ->
      @previousActive.form is @currentActive.form

    ###*
    #  Set the status of the form
    #
    #  @method setActive
    #  @param {String} active
    #  @return {Array} forms
    ###
    setActive : (active) ->
      @previousActive = @currentActive or {}
      if active is "login"
        @currentActive = @login
        @currentPassive = @register
      else
        @currentActive = @register
        @currentPassive = @login
      [@currentActive, @currentPassive]

